<?php

namespace App\Http\Controllers;

use App\Models\FichaCaracterizacion;
use App\Http\Requests\StoreFichaCaracterizacionRequest;
use App\Http\Requests\UpdateFichaCaracterizacionRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('ficha.create');

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
            if($request->input->ficha = "" && $request->input->nombre_curso = ""){
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
                'user_id' => Auth::user()->id,
                'ficha_caracterizacion' => $request->input('ficha_caracterizacion'),
                'ambiente_id' => $request->input('ambiente_id'),
            ]);
            return redirect()->route('entradaSalida.index')->with('success', '¡Registro Exitoso!');
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
        //
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
