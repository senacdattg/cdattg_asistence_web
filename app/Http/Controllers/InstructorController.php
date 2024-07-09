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
    public function index(Request $request)
    {
        // $instructores = Instructor::paginate(10);
        $search = $request->input('search');

        $instructores = Instructor::whereHas('persona', function ($query) use ($search) {
            if ($search) {
                $query->where('primer_nombre', 'like', "%{$search}%")
                ->orWhere('segundo_nombre', 'like', "%{$search}%")
                ->orWhere('primer_apellido', 'like', "%{$search}%")
                ->orWhere('segundo_apellido', 'like', "%{$search}%")
                ->orWhere('numero_documento', 'like', "%{$search}%");
            }
        })->paginate(10);
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
        $fechaNacimiento = $request->fecha_de_nacimiento;
        // @dd($fechaNacimiento);
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
    public function ApiUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            $personaD = Persona::find($request->persona_id);
            $personaD->update([
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'fecha_de_nacimiento' => $request->fecha_de_nacimiento,
                'genero' => $request->genero,
                'email' => $request->email,
            ]);
            // actualizar Usuario asociado a la Persona
            // Actualizar Usuario asociado a la Persona
            $user = User::where('persona_id', $request->persona_id)->first();
            if ($user) {
                $user->update([
                    'email' => $request->email,
                    'password' => Hash::make($request->numero_documento),
                ]);
                // Refrescar el modelo del usuario
                $user = $user->fresh();
            }
            DB::commit();
            $user->refresh();
            $token = $user->createToken('Token Name')->plainTextToken; // Generar el token
            $personaD->refresh();
            $persona = [
                "id" => $personaD->id,
                "tipo_documento" => $personaD->tipoDocumento->name,
                "numero_documento" => $personaD->numero_documento,
                "primer_nombre" => $personaD->primer_nombre,
                "segundo_nombre" => $personaD->segundo_nombre,
                "primer_apellido" => $personaD->primer_apellido,
                "segundo_apellido" => $personaD->segundo_apellido,
                "fecha_de_nacimiento" => $personaD->fecha_de_nacimiento,
                "genero" => $personaD->tipoGenero->name,
                "email" => $personaD->email,
                "created_at" => $personaD->created_at,
                "updated_at" => $personaD->updated_at,
                "instructor_id" => $personaD->instructor->id,
                "regional_id" => $personaD->instructor->regional->id,
            ];
            // Retornar la respuesta JSON incluyendo el token
            return response()->json(['user' => $user, 'persona' => $persona, 'token' => $token], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
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
            // Validar que el archivo subido sea un archivo CSV o TXT
            $request->validate([
                'archivoCSV' => 'required|file|mimes:csv,txt',
            ]);

            // Obtener el archivo subido
            $archivo = $request->file('archivoCSV');
            $csvData = file_get_contents($archivo);

            // Eliminar el BOM (Byte Order Mark) si está presente al inicio del archivo
            if (substr($csvData, 0, 3) === "\u{FEFF}") {
                $csvData = substr($csvData, 3);
            }

            // Convertir el contenido del archivo CSV en un array de filas, usando punto y coma como delimitador
            $rows = array_map(function ($row) {
                return str_getcsv($row, ';');
            }, explode("\n", $csvData));

            // Extraer el encabezado (primera fila) del CSV
            $header = array_shift($rows);

            // Eliminar espacios en blanco alrededor de cada elemento del encabezado y convertirlo a mayúsculas
            $header = array_map('trim', $header);
            $header = array_map('strtoupper', $header);

            // Definir el encabezado esperado
            $expectedHeader = ['TITLE', 'ID_PERSONAL', 'CORREO INSTITUCIONAL'];

            // Comprobar si el encabezado del CSV coincide con el encabezado esperado
            if ($header !== $expectedHeader) {
                return redirect()->back()->with('error', 'El encabezado del archivo CSV no coincide con el formato esperado.');
            }

            // Iniciar una transacción de base de datos
            DB::beginTransaction();

            $errores = [];
            $procesados = 0;

            // Procesar cada fila de datos del CSV
            foreach ($rows as $row) {
                // Verificar que la cantidad de columnas en la fila coincida con la cantidad de columnas en el encabezado
                if (count($row) != count($header)) {
                    $errores[] = $row;
                    continue;
                }

                // Combinar el encabezado con los datos de la fila
                $data = array_combine($header, $row);

                try {
                    // Crear una nueva entrada en la tabla `Persona`
                    $persona = Persona::create([
                        'tipo_documento' => 8,
                        'numero_documento' => $data['ID_PERSONAL'],
                        'primer_nombre' => $data['TITLE'],
                        'genero' => 11,
                        'email' => $data['CORREO INSTITUCIONAL'],
                    ]);

                    // Crear una nueva entrada en la tabla `User`
                    $user = User::create([
                        'email' => $data['CORREO INSTITUCIONAL'],
                        'password' => Hash::make($data['ID_PERSONAL']),
                        'persona_id' => $persona->id,
                    ]);

                    // Asignar el rol de 'INSTRUCTOR' al usuario
                    $user->assignRole('INSTRUCTOR');

                    // Crear una nueva entrada en la tabla `Instructor`
                    Instructor::create([
                        'persona_id' => $persona->id,
                        'regional_id' => 1,
                    ]);

                    // Incrementar el contador de registros procesados con éxito
                    $procesados++;
                } catch (Exception $e) {
                    // Si ocurre un error, agregar la fila a la lista de errores y continuar con la siguiente
                    $errores[] = $data;
                    continue;
                }
            }

            // Confirmar la transacción de base de datos
            DB::commit();

            // Preparar el mensaje de éxito
            $mensaje = 'Instructores creados exitosamente: ' . $procesados;

            // Si hubo errores, agregar una nota al mensaje de éxito
            if (count($errores) > 0) {
                $mensaje .= '. Algunos registros no pudieron ser procesados.';
            }

            // Mostrar la vista con los errores y el mensaje de éxito
            return view('Instructores.errorImport', compact('errores'))->with('success', $mensaje);
        } catch (QueryException $e) {
            // Si ocurre un error en la base de datos, revertir la transacción
            DB::rollBack();
            return redirect()->back()->with('error', 'Error en la base de datos: ' . $e->getMessage());
        } catch (Exception $e) {
            // Si ocurre un error general, revertir la transacción
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


}
