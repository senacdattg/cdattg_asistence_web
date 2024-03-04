<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Http\Requests\StoreAmbienteRequest;
use App\Http\Requests\UpdateAmbienteRequest;
use App\Models\Piso;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ambientes = Ambiente::paginate(10);
        return view('ambiente.index', compact('ambientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pisos = Piso::all();
        return view('ambiente.create', compact('pisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmbienteRequest $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required',
                'piso_id' => 'required',
            ]);
            // @dd($validator);
            if ($validator->fails()) {
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $ambiente = Ambiente::create([
                'descripcion' => $request->input('descripcion'),
                'piso_id' => $request->input('piso_id'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
            ]);
            return redirect()->route('ambiente.index')->with('success', '¡Registro Exitoso!');
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
    public function show(Ambiente $ambiente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ambiente $ambiente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmbienteRequest $request, Ambiente $ambiente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ambiente $ambiente)
    {
        //
    }
    public function cargarAmbientes(Request $request, $pisoId)
    {
        $ambientes = Ambiente::where('piso_id', $pisoId)->pluck('descripcion', 'id');

        return response()->json($ambientes);
    }
}
