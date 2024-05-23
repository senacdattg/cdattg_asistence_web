<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use App\Http\Requests\StoreTemaRequest;
use App\Http\Requests\UpdateTemaRequest;
use App\Models\parametro;

class TemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $temas = Tema::paginate(10);
        return view('temas.index', compact('temas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemaRequest $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:temas',
            'status' => 'required|boolean',
        ]);

        $data['user_create_id'] = auth()->id();
        $data['user_edit_id'] = auth()->id();

        try {
            $tema = Tema::create($data);
            return redirect()->back()->with('success', '¡Tema creado exitosamente!');
        } catch (\Exception $e) {
            dd($e); // para obtener más información sobre la excepción
            return redirect()->back()->with('danger', 'Error al crear el parámetro: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tema $tema)
    {
        return view('temas.show', compact('tema'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tema $tema)
    {
        $parametros = parametro::where('status', 1)->get();
        return view('temas.edit', compact('tema', 'parametros'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemaRequest $request, Tema $tema)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tema $tema)
    {
        $tema->delete();
        return redirect()->route('tema.index')->with('success', 'Tema eliminado exitosamente');

    }
    public function cambiarEstadoParametro(parametro $parametro)
    {

        if ($parametro->status === 1) {
            $parametro->update(['status' => 0]);
        } else {
            $parametro->update(['status' => 1]);
        }
        return redirect()->back();
    }
}
