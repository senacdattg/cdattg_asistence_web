<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Http\Requests\StoreSedeRequest;
use App\Http\Requests\UpdateSedeRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sedes = Sede::paginate(10);
        return view('sede.index', compact('sedes'));
    }
    public function cargarSedes($municipio_id)
    {
        $sedes = Sede::where('municipio_id', $municipio_id)
        ->where('status', 1)->get();
        return response()->json(['success' => true, 'sedes' => $sedes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sede.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSedeRequest $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required',
                'direccion' => 'required',
                'ciudad' => 'required',
            ]);
            if ($validator->fails()) {
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $sede = Sede::create([
                'descripcion' => $request->input('descripcion'),
                'direccion' => $request->input('direccion'),
                'ciudad' => $request->input('ciudad'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
            ]);
            return redirect()->route('sede.index')->with('success', '¡Registro Exitoso!');
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
    public function show(Sede $sede)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sede $sede)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSedeRequest $request, Sede $sede)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sede $sede)
    {
        //
    }
}
