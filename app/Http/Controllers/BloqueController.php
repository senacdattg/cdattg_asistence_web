<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Http\Requests\StoreBloqueRequest;
use App\Http\Requests\UpdateBloqueRequest;
use App\Models\Sede;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BloqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloques = Bloque::paginate(10);
        return view('bloque.index', compact('bloques'));
    }
    public function cargarBloques($sede_id){
        // DB::enableQueryLog();
        $bloques = Bloque::where('sede_id', $sede_id)->get();
        return response()->json(['success' => true, 'bloques' => $bloques]);
        // dd(DB::getQueryLog());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sedes = Sede::all();
        return view('bloque.create', compact('sedes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBloqueRequest $request)
    {
        // @dd($request);
        try {
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required',
                'sede_id' => 'required',
            ]);
            // @dd($validator);
            if ($validator->fails()) {
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $bloque = Bloque::create([
                'descripcion' => $request->input('descripcion'),
                'sede_id' => $request->input('sede_id'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
            ]);
            return redirect()->route('bloque.index')->with('success', '¡Registro Exitoso!');
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
    public function show(Bloque $bloque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bloque $bloque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueRequest $request, Bloque $bloque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bloque $bloque)
    {
        $bloque->delete();

        return redirect()->route('bloque.index')->with('success', 'Bloque eliminado exitosamente');
    }
}
