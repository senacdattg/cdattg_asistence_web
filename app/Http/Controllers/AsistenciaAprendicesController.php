<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaAprendiz;
use App\Models\FichaCaracterizacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class AsistenciaAprendicesController extends Controller
{

    public function index (){
        $fichas = FichaCaracterizacion::select('id', 'ficha')->get();
        return view('asistencias.index', compact('fichas')); 
    }
   
    public function getAttendanceByFicha (Request $request){
       
        try {
            $fichaId = $request->input('ficha');
            if (!$fichaId) {
                return response()->json(['message' => 'ID de ficha no proporcionado'], 400);
            }
            
            $asistencias = AsistenciaAprendiz::whereHas('caracterizacion', function ($query) use ($fichaId) {
                $query->where('ficha_id', $fichaId);
            })->get();

            if ($asistencias->isEmpty()) {
                return response()->json(['message' => 'No se encontraron asistencias para la ficha proporcionada'], 404);
            }
            return view('asistencias.asistencia_by_ficha', ['asistencias' => $asistencias]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error obteniendo asistencias', 'error' => $e->getMessage()], 500);
        }
    
    }

    public function getAttendanceByDateAndFicha(Request $request){
       $ficha = $request->input('ficha');
       $fecha_inicio = $request->input('fecha_inicio');
       $fecha_fin = $request->input('fecha_fin');

         if (!$ficha || !$fecha_inicio || !$fecha_fin) {
              return response()->json(['message' => 'Datos incompletos'], 400);
         }
            $asistencias = AsistenciaAprendiz::whereHas('caracterizacion', function ($query) use ($ficha) {
                $query->where('ficha_id', $ficha);
            })->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->get();
        if ($asistencias->isEmpty()) {
            return response()->json(['message' => 'No se encontraron asistencias para la ficha y fechas proporcionadas'], 404);
        }

        return view('asistencias.asistencia_by_date', ['asistencias' => $asistencias]);
    }

    public function getDocumentsByFicha(Request $request)
    {
        $ficha_id = $request->input('ficha'); 
        
        try {
            if (!$ficha_id) {
                return response()->json(['message' => 'ID de ficha no proporcionado'], 400);
            }

            $documentos = AsistenciaAprendiz::select('numero_identificacion')
            ->whereHas('caracterizacion', function ($query) use ($ficha_id) {
                $query->where('ficha_id', $ficha_id);
            })
            ->get();
            
            if ($documentos->isEmpty()) {
                return response()->json(['message' => 'No se encontraron documentos para la ficha proporcionada'], 404);
            }

            return view('asistencias.consulta_by_document', ['documentos' => $documentos]);


            return response()->json(['documentos' => $documentos], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error obteniendo documentos', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAttendanceByDocument(Request $request){
        $document = $request->input('documento'); 
       
            if ( !$document) {
                return response()->json(['message' => 'Datos incompletos'], 400);
            }

            $asistencias = AsistenciaAprendiz::where('numero_identificacion', $document)->get();
           
            if ($asistencias->isEmpty()) {
                return response()->json(['message' => 'No se encontraron asistencias para la ficha y documento proporcionados'], 404);
            }

            return view('asistencias.asistencia_by_document', ['asistencias' => $asistencias]);

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Obtener todos los datos de la solicitud
            $data = $request->all();

            // Registrar los datos en el log
            Log::info('Datos de la solicitud HTTP:', $data);

            // Guardar los datos en la base de datos
            foreach ($data['attendance'] as $attendance) {
                // Convertir el valor de hora_ingreso al formato correcto
                $horaIngreso = Carbon::parse($attendance['hora_ingreso'])->format('Y-m-d H:i:s');

                AsistenciaAprendiz::create([
                    'caracterizacion_id' => $data['caracterizacion_id'],
                    'nombres' => $attendance['nombres'],
                    'apellidos' => $attendance['apellidos'],
                    'numero_identificacion' => $attendance['numero_identificacion'],
                    'hora_ingreso' => $horaIngreso,
                ]);
            }

            return response()->json(['message' => 'Lista de asistencia guardada con éxito'], 200);
        } catch (Exception $e) {
            // Registrar el error en el log
            Log::error('Error saving attendance:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Error saving attendance', 'error' => $e->getMessage()], 500);
        }
    }

    
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('Datos de la solicitud HTTP para actualizar:', $data);

            if (!isset($data['caracterizacion_id']) || !isset($data['hora_salida']) || !isset($data['fecha'])) {
                return response()->json(['message' => 'Datos incompletos'], 400);
            }

            $horaSalida = Carbon::parse($data['hora_salida'])->format('Y-m-d H:i:s');

            $asistencias = AsistenciaAprendiz::where('caracterizacion_id', $data['caracterizacion_id'])
                ->whereDate('created_at', $data['fecha'])
                ->select('id', 'hora_salida')
                ->get();
            
          
            Log::info('Asistencias encontradas:', ['asistencias' => $asistencias]);

            if ($asistencias->isEmpty()) {
                return response()->json(['message' => 'Asistencias no encontradas'], 404);
            }

            foreach ($asistencias as $asistencia) {
                $asistencia->hora_salida = $horaSalida;
                $asistencia->save();
            }

            return response()->json(['message' => 'Asistencias actualizadas con éxito'], 200);
        } catch (Exception $e) {
            
            Log::error('Error actualizando asistencias:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Error actualizando asistencias', 'error' => $e->getMessage()], 500);
        }
    }

    public function assistenceNovedad (Request $request){
        $data = $request->all();

        Log::info('Datos de la solicitud HTTP para actualizar:', $data);

        return response()->json(['message' => 'Novedad registrada con éxito'], 200);
    }
    
   
}
