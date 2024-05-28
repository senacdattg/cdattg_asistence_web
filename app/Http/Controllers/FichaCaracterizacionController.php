<?php

namespace App\Http\Controllers;

use App\Models\FichaCaracterizacion;
use App\Http\Requests\StoreFichaCaracterizacionRequest;
use App\Http\Requests\UpdateFichaCaracterizacionRequest;
use App\Models\Regional;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaCaracterizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fichas = FichaCaracterizacion::where('instructor_asignado', Auth::user()->id)->paginate(10);
        return view('ficha.index', compact('fichas'));
    }
    public function apiIndex(Request $request)
    {
        // Obtener el ID del usuario de la solicitud
        $userId = $request->user_id;
        // Obtener las fichas de caracterización asociadas al usuario
        $fichas = FichaCaracterizacion::where('instructor_asignado', $userId)->get();
        $fichasArray = [];

        // Iterar sobre las fichas obtenidas de la consulta
        foreach ($fichas as $ficha) {
            // Crear un array para cada ficha con los atributos deseados
            $fichaArray = [
                "id" => $ficha->id,
                "ficha" => $ficha->ficha,
                "nombre_curso" => $ficha->nombre_curso,
                "codigo_programa" => $ficha->codigo_programa,
                "horas_formacion" => $ficha->horas_formacion,
                "cupo" => $ficha->cupo,
                "dias_de_formacion" => $ficha->dias_de_formacion,
                "Instructor" => [
                    "id" => $ficha->instructor_asignado,
                    "primer_nombre" => $ficha->instructor->persona->primer_nombre,
                    "segundo_nombre" => $ficha->instructor->persona->segundo_nombre,
                    "primer_apellido" => $ficha->instructor->persona->primer_apellido,
                    "segundo_apellido" => $ficha->instructor->persona->segundo_apellido,
                ],
                "created_at" => $ficha->created_at->toIso8601String(),
                "updated_at" => $ficha->updated_at->toIso8601String(),
                "deleted_at" => $ficha->deleted_at,
                "ambiente" => $ficha->ambiente->title,
                "municipio" => $ficha->ambiente->piso->bloque->sede->municipio->municipio,
            ];

            // Agregar el array de la ficha al array de fichas
            $fichasArray[] = $fichaArray;
        }
        return response()->json(['fichas' => $fichasArray],200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regionales = Regional::where('status', 1)->get();
        return view('ficha.create', compact('regionales'));

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
                return redirect()->back()->withErrors(['error' => 'Debe ingresar el número de ficha o nombre del programa.']);
            }
            DB::beginTransaction();

            $fichaCaracterizacion = FichaCaracterizacion::create([
                'ficha' => $request->input('ficha'),
                'nombre_curso' => $request->input('nombre_curso'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
                'regional_id' => $request->input('regional_id'),
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
            "ambiente" => $fichaCaracterizacion->ambiente->title,


        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FichaCaracterizacion $fichaCaracterizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFichaCaracterizacionRequest $request, FichaCaracterizacion $fichaCaracterizacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FichaCaracterizacion $fichaCaracterizacion)
    {
        //
    }
}
