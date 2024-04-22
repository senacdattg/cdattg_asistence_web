<?php

namespace App\Http\Controllers;


use App\Models\parametro;
use App\Http\Requests\StoreparametroRequest;
use App\Http\Requests\UpdateparametroRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

class ParametroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parametros = Parametro::paginate(10);
        return view('parametros.index', compact('parametros'));
    }
    public function apiIndex(){
        $parametros = Parametro::all();
        return response()->json($parametros);

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function cambiarEstado(Parametro $parametro)
    {
        if ($parametro->status === 1) {
            $parametro->update(['status' => 0]);
        } else {
            $parametro->update(['status' => 1]);
        }
        // return redirect()->back()->with('success', 'Estado cambiado exitosamente');
        return redirect()->back();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreparametroRequest $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:parametros',
            'status' => 'required|string|max:255',

        ]);
        $data['user_create_id'] = auth()->id();
        $data['user_edit_id'] = auth()->id();
        // Crear el parámetro
        $parametro = Parametro::create($data);


        return redirect()->back()->with('success', '¡Parámetro creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(parametro $parametro)
    {

        return view('parametros.show', compact('parametro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(parametro $parametro)
    {
        return view('parametros.edit', compact('parametro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateparametroRequest $request, parametro $parametro)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Actualizar los datos del modelo
        $parametro->update($data);

        return redirect()->route('parametro.show', $parametro->id)->with('success', 'Parámetro actualizado exitosamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(parametro $parametro)
    {
        $parametro->delete();

        return redirect()->route('parametro.index')->with('success', 'Parámetro eliminado exitosamente');

    }
    public function crearParametro(Request $request)
    {

    }



}
