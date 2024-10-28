<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use GuzzleHttp\Client;
use App\Mail\CarnetMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Intervention\Image\Facades\Image;

class CarnetController extends Controller
{
    private $backgroundRemovalApiUrl = 'http://127.0.0.1:5000/remove_background';

    public function index()
    {
        return view('carnet.index');
    }

    public function processCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $csvFile = $request->file('csv_file');
        $photos = $request->file('photos') ?? [];

        $csvData = array_map(function ($line) {
            return str_getcsv($line, ';');
        }, file($csvFile->getPathname()));

        array_shift($csvData);

        $carnets = [];

        foreach ($csvData as $index => $row) {
            if (count($row) < 5 || empty(array_filter($row))) {
                continue;
            }

            $Aprendiz = trim($row[0]) ?? 'Sin nombre';
            $Documento = trim($row[1]) ?? 'Sin documento';
            $Correo = trim($row[2]) ?? 'Sin correo';
            $Ficha = trim($row[3]) ?? 'Sin ficha';
            $Programa = trim($row[4]) ?? 'Sin programa';

            $nombrePartes = explode(' ', $Aprendiz);
            $nombres = implode(' ', array_slice($nombrePartes, 0, -2));
            $apellidos = implode(' ', array_slice($nombrePartes, -2));

            $qrData = "{$nombres} | {$apellidos} | {$Documento}";
            $qrCode = new QrCode($qrData);
            $qrCode->setSize(120);
            $writer = new SvgWriter();
            $qrCodeSvg = $writer->write($qrCode)->getString();

            $photoData = null;
            if (isset($photos[$index])) {
                $photoData = $this->removeImageBackground($photos[$index]);
            }

            $carnet = [
                'aprendiz' => $Aprendiz,
                'documento' => $Documento,
                'correo' => $Correo,
                'ficha' => $Ficha,
                'programa' => $Programa,
                'qr_code' => $qrCodeSvg,
                'photo' => $photoData,
            ];

            $carnets[] = $carnet;
        }

        if (empty($carnets)) {
            return back()->with('error', 'No se pudo procesar ningún dato del CSV. Por favor, verifica el formato del archivo.');
        }

        // Guarda los carnets en la sesión
        session(['carnets' => $carnets]);

        return view('carnet.result', ['carnets' => $carnets]);
    }

    private function removeImageBackground($photo)
    {
        $client = new Client();

        try {
            // Verifica si $photo es una instancia válida de UploadedFile
            if (!$photo instanceof \Illuminate\Http\UploadedFile) {
                throw new \Exception("Invalid file provided");
            }

            // Asegúrate de que el archivo existe y es legible
            if (!$photo->isValid()) {
                throw new \Exception("Invalid or unreadable file");
            }

            $response = $client->post($this->backgroundRemovalApiUrl, [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($photo->getRealPath(), 'r'),
                        'filename' => $photo->getClientOriginalName()
                    ]
                ]
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $imageData = $response->getBody()->getContents();
                return base64_encode($imageData);
            } else {
                throw new \Exception("API returned status code: " . $statusCode);
            }
        } catch (\Exception $e) {
            // Log the error with more details
            Log::error('Error removing image background: ' . $e->getMessage(), [
                'file' => $photo ? $photo->getClientOriginalName() : 'No file',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // If there's an error, return the original image
            return $photo ? base64_encode(file_get_contents($photo->getRealPath())) : null;
        }
    }

    public function sendCarnetByEmail(Request $request)
    {
        $carnetData = $request->validate([
            'aprendiz' => 'required',
            'documento' => 'required',
            'correo' => 'required|email',
            'ficha' => 'required',
            'programa' => 'required',
            'qr_code' => 'required',
            'photo' => 'required'
        ]);

        Log::info('Datos del carnet a enviar: ', $carnetData);

        try {
            Mail::send('emails.carnet', $carnetData, function ($message) use ($carnetData) {
                $message->to($carnetData['correo'], $carnetData['aprendiz'])
                    ->subject('Tu Carnet Digital SENA');
            });

            Log::info('Correo enviado a: ' . $carnetData['correo']);
            return response()->json(['message' => 'Carnet enviado con éxito']);
        } catch (\Exception $e) {
            Log::error('Error al enviar el carnet por correo: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo enviar el carnet: ' . $e->getMessage()], 500);
        }
    }

    public function sendAll(Request $request)
    {
        $carnets = session('carnets', []);
        $capturedImages = $request->input('capturedImages', []);
        Log::info('Iniciando envío masivo de carnets. Total de carnets: ' . count($carnets));

        if (empty($carnets)) {
            Log::warning('No hay carnets en la sesión para enviar.');
            return redirect()->route('carnet.index')->with('warning', 'No hay carnets para enviar.');
        }

        $successCount = 0;
        $failCount = 0;
        $failedEmails = [];

        foreach (array_keys($carnets) as $index) {
            $carnet = $carnets[$index];
            // Verificar que todos los datos necesarios estén presentes 
            if (!isset($carnet['aprendiz'], $carnet['documento'], $carnet['correo'], $carnet['ficha'], $carnet['programa'], $carnet['qr_code'], $carnet['photo'])) {
                Log::error('Datos de carnet incompletos:', $carnet);
                $failCount++;
                $failedEmails[] = $carnet['correo'] ?? 'Correo no especificado';
                continue;
            }

            try {
                // Generar el PDF del carnet 
                $pdfPath = $this->generateCarnetPDF($carnet);

                // Guardar la imagen capturada
                $imageData = $capturedImages[$index] ?? null;
                $imagePath = null;
                if ($imageData) {
                    $imagePath = $this->saveBase64Image($imageData, $carnet['documento']);
                }

                if ($pdfPath && file_exists($pdfPath)) {
                    // Enviar el carnet por correo 
                    Mail::to($carnet['correo'])
                        ->send(new CarnetMail($carnet, $pdfPath, $imagePath));

                    Log::info('Correo enviado a: ' . $carnet['correo']);
                    $successCount++;

                    // Eliminar el archivo PDF temporal 
                    unlink($pdfPath);
                } else {
                    throw new \Exception("No se pudo generar el PDF para el carnet");
                }
            } catch (\Exception $e) {
                Log::error('Error al enviar carnet a: ' . $carnet['correo'] . '. Error: ' . $e->getMessage());
                $failCount++;
                $failedEmails[] = $carnet['correo'];
            }
        }

        Log::info("Proceso de envío completado. Éxitos: $successCount. Fallos: $failCount");

        if ($failCount > 0) {
            Log::warning('Algunos carnets no pudieron ser enviados: ' . implode(', ', $failedEmails));
            return redirect()->route('carnet.index')->with('warning', "Se enviaron $successCount carnets. $failCount carnets no pudieron ser enviados.");
        } else {
            return redirect()->route('carnet.index')->with('success', "Todos los carnets ($successCount) fueron enviados con éxito.");
        }
    }

    private function saveBase64Image($base64Image, $documento)
    {
        try {
            // Decodificar la imagen base64
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

            // Definir la ruta
            $directory = storage_path('app/public/carnets');

            // Crear el directorio si no existe
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Ruta completa del archivo
            $imagePath = $directory . "/{$documento}.png";

            // Guardar el archivo
            if (file_put_contents($imagePath, $image)) {
                Log::info("Imagen guardada exitosamente para documento: " . $documento);
                return $imagePath;
            }

            throw new \Exception("No se pudo guardar la imagen");
        } catch (\Exception $e) {
            Log::error('Error guardando imagen: ' . $e->getMessage());
            throw $e;
        }
    }


    private function generateCarnetPDF($carnet)
    {
        try {
            Log::info('Datos del carnet antes de generar PDF:', $carnet);
            // Asegúrese de que el directorio exista
            $tempDir = storage_path('app/temp_carnets');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            // Asegúrese de que todos los datos necesarios estén presentes
            $data = [
                'aprendiz' => $carnet['aprendiz'] ?? 'Sin nombre',
                'documento' => $carnet['documento'] ?? 'Sin documento',
                'ficha' => $carnet['ficha'] ?? 'Sin ficha',
                'programa' => $carnet['programa'] ?? 'Sin programa',
                'qr_code' => $carnet['qr_code'] ?? '',  // Asegúrese de que esto esté presente
                'photo' => $carnet['photo'] ?? null
            ];

            // Generar el PDF pasando los datos correctamente
            $pdf = PDF::loadView('carnet.carnet', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'chroot' => public_path(),
                ]);

            $tempPath = $tempDir . '/' . uniqid() . '.pdf';
            $pdf->save($tempPath);

            return $tempPath;
        } catch (\Exception $e) {
            Log::error('Error generando PDF: ' . $e->getMessage());
            return null;
        }
    }

    public function result()
    {
        $carnets = session('carnets', []);
        return view('carnet.result', compact('carnets'));
    }
    // public function testEmail()
    // {
    //     try {
    //         Mail::raw('Test email content', function ($message) {
    //             $message->to('jhonnygonsalez7@gmail.com')
    //                 ->subject('Test Email');
    //         });
    //         return "Email enviado correctamente";
    //     } catch (\Exception $e) {
    //         return "Error al enviar email: " . $e->getMessage();
    //     }
    // }
}