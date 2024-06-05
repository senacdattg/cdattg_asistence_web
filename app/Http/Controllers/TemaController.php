<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use App\Http\Requests\StoreTemaRequest;
use App\Http\Requests\UpdateTemaRequest;
use App\Models\parametro;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);
        try {

            DB::beginTransaction();
            $tema->update($data);
            DB::commit();
            return redirect()->route('tema.show', $tema->id)->with('success', 'Tema Actualizado exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'No se pudo actualizar el tema');
        }
    }
    public function updateParametrosTemas(Request $request)
    {
        $tema_id = $request->input('tema_id');
        $parametros = $request->input('parametros');

        // Obtén el modelo del tema
        $tema = Tema::find($tema_id);

        // Crea un array para sincronizar los parámetros con valores específicos

        $dataToSync = [];
        foreach ($parametros as $parametro_id) {
            $dataToSync[$parametro_id] = [
                'user_create_id' => auth()->id(),
                'user_edit_id' => auth()->id(),
            ];
        }

        // Sincroniza los parámetros en la tabla pivote sin eliminar los existentes
        $tema->parametros()->sync($dataToSync);

        // Resto del código según tus necesidades

        return redirect()->back()->with('success', 'Parámetros actualizados exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tema $tema)
    {
        try {
            DB::beginTransaction();
            $tema->delete();
            DB::commit();
            return redirect()->route('tema.index')->with('success', 'Tema eliminado exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {

                return redirect()->back()->with('error', 'El tema se encuentra en uso en estos momentos, no se puede eliminar');
            }
        }

    }
    public function cambiarEstado(Tema $tema)
    {
        if ($tema->status === 1) {
            $tema->update(['status' => 0]);
        } else {
            $tema->update(['status' => 1]);
        }
        // return redirect()->back()->with('success', 'Estado cambiado exitosamente');
        return redirect()->back();
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
