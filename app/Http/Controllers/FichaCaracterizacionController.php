<?php

namespace App\Http\Controllers;

use App\Models\FichaCaracterizacion;
use App\Http\Requests\StoreFichaCaracterizacionRequest;
use App\Http\Requests\UpdateFichaCaracterizacionRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
        $userId = $request->input('user_id');
        // Obtener las fichas de caracterización asociadas al usuario
        $fichas = FichaCaracterizacion::where('instructor_asignado', $userId)->get();

        return response()->json($fichas);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

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
            $validator = Validator::make($request->all(),[
                'ficha' => 'nullable',
                'nombre_curso' => 'nullable',
                'ambiente_id' => 'required',
            ]);
            if (!$request->filled('ficha') && !$request->filled('nombre_curso')) {
                return redirect()->back()->withErrors(['error' => 'Debe ingresar el número de ficha o nombre del programa.']);
            }


            // 'ficha', 'nombre_curso','codigo_programa', 'horas_formacion', 'cupo', 'dias_de_formacion', 'municipio_id', 'instructor_asignado', 'ambiente_id'
            // estos son los nuevos campos que se debe de poner
            // ajustar la vista tambien
            if($validator->fails()){
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $fichaCaracterizacion = FichaCaracterizacion::create([
                'ficha' => $request->input('ficha'),
                'nombre_curso' => $request->input('nombre_curso'),
                'instructor_asignado' => Auth::user()->id,
                'ambiente_id' => $request->input('ambiente_id'),
            ]);
            return redirect()->route('entradaSalida.registros', ['fichaCaracterizacion' => $fichaCaracterizacion->id])->with('success', '¡Registro Exitoso!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            @dd($e);
            return redirect()->back()->withErrors(['error' => 'Error de base de datos. Por favor, inténtelo de nuevo.']);
        } catch (\Exception $e) {
            // Manejar otras excepciones
            @dd($e);
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
