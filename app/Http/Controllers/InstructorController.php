<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Models\FichaCaracterizacion;
use App\Models\Persona;
use App\Models\Regional;
use App\Models\Tema;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Middleware de autenticación para todos los métodos del controlador

        // Middleware específico para métodos individuales
        $this->middleware('can:VER INSTRUCTOR')->only('index');
        $this->middleware('can:VER INSTRUCTOR')->only('show');
        $this->middleware('can:CREAR INSTRUCTOR')->only(['create', 'store']);
        $this->middleware('can:EDITAR INSTRUCTOR')->only(['edit', 'update']);
        $this->middleware('can:ELIMINAR INSTRUCTOR')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructores = Instructor::paginate(10);
        return view('Instructores.index', compact('instructores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // llamar los tipos de documentos
        $documentos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(2);
        // llamar los generos
        $generos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(3);
        $regionales = Regional::where('status', 1)->get();


        return view('Instructores.create', compact('documentos', 'generos', 'regionales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequest $request)
    {
        try {
            DB::beginTransaction();
            // Crear Persona
            $persona = Persona::create([
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre'),
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido'),
                'fecha_de_nacimiento' => $request->input('fecha_de_nacimiento'),
                'genero' => $request->input('genero'),
                'email' => $request->input('email'),
            ]);

            $instructor = Instructor::create([
                'persona_id' => $persona->id,
                'regional_id' => $request->regional_id,
            ]);
            // Crear Usuario asociado a la Persona
            $user = User::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('numero_documento')),
                'persona_id' => $persona->id,
            ]);
            $user->assignRole('INSTRUCTOR');
            DB::commit();
            return redirect()->route('instructor.index')->with('success', '¡Registro Exitoso!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            // @dd($e);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error de base de datos. Por favor, inténtelo de nuevo.' . $e->getMessage());
        }
        // catch (\Exception $e) {
        //     // Manejar otras excepciones
        //     @dd($e);
        //     return redirect()->back()->withErrors(['error' => 'Se produjo un error. Por favor, inténtelo de nuevo.']);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        $fichasCaracterizacion = FichaCaracterizacion::all();
        $instructor->persona->edad = Carbon::parse($instructor->persona->fecha_de_nacimiento)->age;
        $instructor->persona->fecha_de_nacimiento = Carbon::parse($instructor->persona->fecha_de_nacimiento)->format('d/m/Y');
        return view('Instructores.show', compact('instructor', 'fichasCaracterizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        // llamar los tipos de documentos
        $documentos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(2);
        // llamar los generos
        $generos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(3);
        $regionales = Regional::where('status', 1)->get();
        return view('Instructores.edit', ['instructor' => $instructor], compact('documentos', 'generos', 'regionales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor)
    {
        try {
            DB::beginTransaction();
            $persona = Persona::find($instructor->persona_id);
            $persona->update([
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre'),
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido'),
                'fecha_de_nacimiento' => $request->input('fecha_de_nacimiento'),
                'genero' => $request->input('genero'),
                'email' => $request->input('email'),
            ]);

            $instructor->update([
                'persona_id' => $persona->id,
                'regional_id' => $request->regional_id,
            ]);
            // actualizar Usuario asociado a la Persona
            $user = User::where('persona_id', $persona->id);
            $user->update([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('numero_documento')),
            ]);
            DB::commit();
            return redirect()->route('instructor.index')->with('success', '¡Actualización Exitosa!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            // @dd($e);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error de base de datos. Por favor, inténtelo de nuevo.' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        try {
            DB::beginTransaction();
            $instructor->delete();
            DB::commit();
            return redirect()->route('instructor.index')->with('success', 'Instructor eliminado exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {

                return redirect()->back()->with('error', 'El instructor se encuentra en uso en estos momentos, no se puede eliminar');
            }
        }
    }
    public function createImportarCSV()
    {
        return view('Instructores.createImportarCSV');
    }
    public function storeImportarCSV(Request $request)
    {
        try {
            $request->validate([
                'archivoCSV' => 'required|file|mimes:csv,txt',
            ]);

            $archivo = $request->file('archivoCSV');
            $csvData = file_get_contents($archivo);

            // Eliminar el BOM si está presente
            if (substr($csvData, 0, 3) === "\u{FEFF}") {
                $csvData = substr($csvData, 3);
            }

            // Usar el delimitador de punto y coma
            $rows = array_map(function ($row) {
                return str_getcsv($row, ';');
            }, explode("\n", $csvData));

            $header = array_shift($rows);

            // Trimming spaces and converting headers to uppercase for consistency
            $header = array_map('trim', $header);
            $header = array_map('strtoupper', $header);

            // Debugging: Print the header
            // dd('Header from CSV:', $header);

            // Expected header keys
            $expectedHeader = ['TITLE', 'ID_PERSONAL', 'CORREO INSTITUCIONAL'];

            // Check if the header matches the expected header
            if ($header !== $expectedHeader) {
                return redirect()->back()->with('error', 'El encabezado del archivo CSV no coincide con el formato esperado.');
            }

            DB::beginTransaction();

            $errores = [];
            $procesados = 0;

            foreach ($rows as $row) {
                if (count($row) != count($header)) {
                    $errores[] = $row; // Save the row that couldn't be processed
                    continue; // Skip rows with incorrect number of columns
                }

                $data = array_combine($header, $row);

                // Debugging: Print the data row
                // dd('Data row:', $data);

                try {
                    $persona = Persona::create([
                        'tipo_documento' => 8,
                        'numero_documento' => $data['ID_PERSONAL'],
                        'primer_nombre' => $data['TITLE'],
                        'genero' => 11,
                        'email' => $data['CORREO INSTITUCIONAL'],
                    ]);

                    $user = User::create([
                        'email' => $data['CORREO INSTITUCIONAL'],
                        'password' => Hash::make($data['ID_PERSONAL']),
                        'persona_id' => $persona->id,
                    ]);

                    Instructor::create([
                        'persona_id' => $persona->id,
                        'regional_id' => 1,
                    ]);

                    $procesados++;
                } catch (Exception $e) {
                    $errores[] = $data; // Save the data that couldn't be processed
                    continue; // Continue processing the next row
                }
            }

            DB::commit();

            $mensaje = 'Instructores creados exitosamente: ' . $procesados;

            if (count($errores) > 0) {
                $mensaje .= '. Algunos registros no pudieron ser procesados.';
            }

            return view('Instructores.errorImport', compact('errores'))->with('success', $mensaje);
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error en la base de datos: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}
