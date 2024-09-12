<?php

namespace App\Http\Controllers;

use App\Models\ProgramaFormacion;
use App\Models\Sede;
use App\Models\TipoPrograma;
use Illuminate\Http\Request;

class ProgramaFormacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programas = ProgramaFormacion::with(['sede', 'tipoPrograma'])->get();
        if(count($programas) == 0){
            $programas = null;
        }



        return view('programas.index', compact('programas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sedes = Sede::all();
        $tipos = TipoPrograma::all();


        if(count($sedes) == 0){
            $sedes = null; 
        }
        
        if(count($tipos) == 0){
            $tipos = null; 
        }

        return view('programas.create', compact('sedes', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_programa' => 'required|string|max:255|unique:programas_formacion,nombre',
            'sede_id' => 'required|exists:sedes,id',
            'tipo_programa_id' => 'required|exists:tipos_programas,id',
        ]);

        $programaFormacion = new ProgramaFormacion();
        $programaFormacion->nombre = $request->input('nombre_programa');
        $programaFormacion->sede_id = $request->input('sede_id');
        $programaFormacion->tipo_programa_id = $request->input('tipo_programa_id');
        $programaFormacion->save();

        return redirect('programa/index')->with('success', 'Programa de formación creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
