<?php

namespace App\Http\Controllers;

use App\Models\FichaCaracterizacion;
use App\Models\ProgramaFormacion;
use Illuminate\Http\Request;

class FichaCaracterizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $fichas = FichaCaracterizacion::with('programaFormacion')->get();

        //dd($fichas)
     
        return view('fichas.index', compact('fichas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
        $programas = ProgramaFormacion::orderBy('nombre', 'asc')->get();
       
        return view('fichas.create', compact('programas')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'programa_id' => 'required|exists:programas_formacion,id',
            'numero_ficha' => 'required|numeric|unique:fichas_caracterizacion,ficha',
        ]);

        $ficha = new FichaCaracterizacion();
        $ficha->programa_formacion_id = $request->input('programa_id');
        $ficha->ficha = $request->input('numero_ficha');

    
        $ficha->save();

        return redirect()->route('fichaCaracterizacion.index')->with('success', 'Caracterización creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ficha = FichaCaracterizacion::findOrFail($id);
        $programas = ProgramaFormacion::orderBy('nombre', 'asc')->get();

        return view('fichas.edit', compact('ficha', 'programas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'programa_id' => 'required|exists:programas_formacion,id',
            'numero_ficha' => 'required|numeric|unique:fichas_caracterizacion,ficha,' . $id,
        ]);

        $ficha = FichaCaracterizacion::findOrFail($id);
        $ficha->programa_formacion_id = $request->input('programa_id');
        $ficha->ficha = $request->input('numero_ficha');

        $ficha->save();

        return redirect()->route('fichaCaracterizacion.index')->with('success', 'Caracterización actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ficha = FichaCaracterizacion::findOrFail($id);
        $ficha->delete();

        return redirect()->route('fichaCaracterizacion.index')->with('success', 'Caracterización eliminada exitosamente.');
    }
}
