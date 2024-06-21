<?php

namespace App\Http\Controllers;

use App\Models\FichaCaracterizacion;
use App\Http\Requests\StoreFichaCaracterizacionRequest;
use App\Http\Requests\UpdateFichaCaracterizacionRequest;
use App\Models\Instructor;
use App\Models\Regional;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaCaracterizacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Middleware de autenticación para todos los métodos del controlador

        // Middleware específico para métodos individuales
        $this->middleware('can:VER FICHA DE CARACTERIZACION')->only('index');
        $this->middleware('can:VER FICHA DE CARACTERIZACION')->only('show');
        $this->middleware('can:CREAR FICHA DE CARACTERIZACION')->only(['create', 'store']);
        $this->middleware('can:EDITAR FICHA DE CARACTERIZACION')->only(['edit', 'update']);
        $this->middleware('can:ELIMINAR FICHA DE CARACTERIZACION')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fichas = FichaCaracterizacion::paginate(10);
        return view('ficha.index', compact('fichas'));
    }
    public function apiIndex(Request $request)
    {
        // @dd('hola mundo');
        // Obtener el ID del usuario de la solicitud
        $instructorID = $request->instructor_id;
        $instructor = Instructor::find($instructorID);
        if (!$instructor){
            return response()->json('Ocurrio un error al momento de encontrar el instructor!', 400);
        }
        // Obtener las fichas de caracterización asociadas al usuario
        // $fichas = FichaCaracterizacion::all();
        $fichasArray = [];

        // Iterar sobre las fichas obtenidas de la consulta
        foreach ($instructor->fichas as $ficha) {
            // Crear un array para cada ficha con los atributos deseados
            $fichaArray = [
                "id" => $ficha->id,
                "ficha" => $ficha->ficha,
                "nombre_curso" => $ficha->nombre_curso,
                "codigo_programa" => $ficha->codigo_programa,
                "horas_formacion" => $ficha->horas_formacion,
                "cupo" => $ficha->cupo,
                "dias_de_formacion" => $ficha->dias_de_formacion,
                "user_create_id" => [
                    'primer_nombre' => $ficha->userCreate->persona->primer_nombre,
                    'segundo_nombre' => $ficha->userCreate->persona->segundo_nombre,
                    'primer_apellido' => $ficha->userCreate->persona->primer_apellido,
                    'segundo_apellido' => $ficha->userCreate->persona->segundo_apellido,
                ],
                "created_at" => $ficha->created_at->toIso8601String(),
                "updated_at" => $ficha->updated_at->toIso8601String(),
                "deleted_at" => $ficha->deleted_at,
                3
                // "municipio" => $ficha->ambiente->piso->bloque->sede->municipio->municipio,
            ];

            // Agregar el array de la ficha al array de fichas
            $fichasArray[] = $fichaArray;
        }
        return response()->json($fichasArray,200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $regionales = Regional::where('status', 1)->get();
        return view('ficha.create');

    }
    public function apiStore(){
        return response()->json();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFichaCaracterizacionRequest $request)
    {
        try{
            if (!$request->filled('ficha') && !$request->filled('nombre_curso')) {
                return redirect()->back()->withErrors(['error' => 'Debe ingresar el número de ficha o nombre del programa.', 'ficha' => ' ', 'nombre_curso' => ' ']);
            }
            DB::beginTransaction();

            $fichaCaracterizacion = FichaCaracterizacion::create([
                'ficha' => $request->input('ficha'),
                'nombre_curso' => $request->input('nombre_curso'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
                'status' => 1,
            ]);
            DB::commit();
            return redirect()->route('fichaCaracterizacion.show', ['fichaCaracterizacion' => $fichaCaracterizacion->id])->with('success', '¡Registro Exitoso!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            @dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error de base de datos. Por favor, inténtelo de nuevo.']);
        } catch (\Exception $e) {
            // Manejar otras excepciones
            @dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Se produjo un error. Por favor, inténtelo de nuevo.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FichaCaracterizacion $fichaCaracterizacion)
    {
        return view('ficha.show', compact('fichaCaracterizacion'));
    }
    public function apiShow(Request $request){
        $id_ficha = $request->id_ficha_caracterizacion;
        $fichaCaracterizacion = FichaCaracterizacion::find($id_ficha);
        if (!$fichaCaracterizacion) {
            return response()->json(['error' => 'Ficha de caracterización no encontrada'], 404);
        }

        return response()->json([
            "id" => $fichaCaracterizacion->id,
            "ficha" => $fichaCaracterizacion->ficha,
            "nombre_curso" => $fichaCaracterizacion->nombre_curso,
            "instructor_asignado" => [
                "id" => $fichaCaracterizacion->instructor_asignado,
                "primer_nombre" => $fichaCaracterizacion->instructor->persona->primer_nombre,
                "segundo_nombre" => $fichaCaracterizacion->instructor->persona->segundo_nombre,
                "primer_apellido" => $fichaCaracterizacion->instructor->persona->primer_apellido,
                "segundo_apellido" => $fichaCaracterizacion->instructor->persona->segundo_apellido,
            ],
            "created_at" => $fichaCaracterizacion->created_at,
            "updated_at" => $fichaCaracterizacion->updated_at,


        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FichaCaracterizacion $fichaCaracterizacion)
    {
        $instructores = Instructor::all();
        // $regionales = Regional::where('status', 1)->get();
        return view('ficha.edit', ['fichaCaracterizacion' => $fichaCaracterizacion,  'instructores' => $instructores]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFichaCaracterizacionRequest $request, FichaCaracterizacion $fichaCaracterizacion)
    {
        try {
            if (!$request->filled('ficha') && !$request->filled('nombre_curso')) {
                return redirect()->back()->withErrors(['error' => 'Debe ingresar el número de ficha o nombre del programa.', 'ficha' => ' ', 'nombre_curso' => ' ']);
            }
            DB::beginTransaction();

            $fichaCaracterizacion->update([
                'ficha' => $request->input('ficha'),
                'nombre_curso' => $request->input('nombre_curso'),
                'user_edit_id' => Auth::user()->id,
                'status' => $request->status,
            ]);
            DB::commit();
            return redirect()->route('fichaCaracterizacion.show', ['fichaCaracterizacion' => $fichaCaracterizacion->id])->with('success', '¡Registro Exitoso!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            // @dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Error de base de datos. Por favor, inténtelo de nuevo.');
        } catch (\Exception $e) {
            // Manejar otras excepciones
            // @dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Se produjo un error. Por favor, inténtelo de nuevo.');
        }
    }
    public function updateinstructoresFichaCaracterizacion(Request $request){
        try{
            DB::beginTransaction();
            $ficha_id = $request->input('ficha_id');
            $instructores = $request->input('instructores');

            // Obtén el modelo del fichaCaracterizacion
            $fichaCaracterizacion = FichaCaracterizacion::find($ficha_id);

            // Crea un array para sincronizar los parámetros con valores específicos

            $dataToSync = [];
            if($instructores){

                foreach ($instructores as $instructor_id) {
                    $dataToSync[$instructor_id] = [
                        'status' => 1,
                    ];
                }
            }

            // Sincroniza los parámetros en la tabla pivote sin eliminar los existentes
            $fichaCaracterizacion->instructores()->sync($dataToSync);

            DB::commit();

            return redirect()->route('fichaCaracterizacion.show', $ficha_id)->with('success', 'Parámetros actualizados exitosamente');
        }catch(QueryException $e){
            DB::rollBack();
        }
    }
    public function cambiarEstadoFichaCaracterizacion(FichaCaracterizacion $fichaCaracterizacion){
        if ($fichaCaracterizacion->status === 1) {
            // @dd($fichaCaracterizacion->update(['status' => 0]));

            $fichaCaracterizacion->update(['status' => 0]);
        } else {
            $fichaCaracterizacion->update(['status' => 1]);
        }
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FichaCaracterizacion $fichaCaracterizacion)
    {
        try {
            DB::beginTransaction();
            $fichaCaracterizacion->delete();
            DB::commit();
            return redirect()->route('fichaCaracterizacion.index')->with('success', 'fichaCaracterizacion eliminado exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {

                return redirect()->back()->with('error', 'la Ficha de Caracteriación se encuentra en uso en estos momentos, no se puede eliminar');
            }
        }
    }
}
