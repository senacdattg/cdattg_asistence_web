<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Http\Requests\StorePisoRequest;
use App\Http\Requests\UpdatePisoRequest;
use App\Models\Bloque;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pisos = Piso::paginate(10);
        return view('piso.index', compact('pisos'));
    }
    public function cargarPisos($bloque_id)
    {
        // DB::enableQueryLog();
        $pisos = Piso::where('bloque_id', $bloque_id)->get();
        return response()->json(['success' => true, 'pisos' => $pisos]);
        // dd(DB::getQueryLog());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloques = Bloque::all();
        return view('piso.create', compact('bloques'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePisoRequest $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required',
                'bloque_id' => 'required',
            ]);
            // @dd($validator);
            if ($validator->fails()) {
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $piso = Piso::create([
                'descripcion' => $request->input('descripcion'),
                'bloque_id' => $request->input('bloque_id'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
            ]);
            return redirect()->route('piso.index')->with('success', '¡Registro Exitoso!');
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
    public function show(Piso $piso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Piso $piso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePisoRequest $request, Piso $piso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Piso $piso)
    {
        $piso->delete();

        return redirect()->route('piso.index')->with('success', 'Piso eliminado exitosamente');
    }
}
