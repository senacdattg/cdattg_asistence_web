<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Http\Requests\StoreBloqueRequest;
use App\Http\Requests\UpdateBloqueRequest;
use App\Models\Sede;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
    public function apiCargarBloques(Request $request)
    {
        // DB::enableQueryLog();
        $sede_id = $request->sede_id;
        $bloques = Bloque::where('sede_id', $sede_id)->get();
        return response()->json($bloques, 200);
        // dd(DB::getQueryLog());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sedes = Sede::where('status', 1)->get();
        return view('bloque.create', compact('sedes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBloqueRequest $request)
    {
        // @dd($request);
        try {
            $bloque = Bloque::create([
                'bloque' => $request->input('bloque'),
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
        return view('bloque.show', ['bloque' => $bloque]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bloque $bloque)
    {
        $sedes = Sede::where('status', 1)->get();
        return view('bloque.edit', ['bloque' => $bloque, 'sedes' => $sedes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueRequest $request, Bloque $bloque)
    {
        try{
            DB::beginTransaction();
            $bloque->update([
                'bloque' => $request->bloque,
                'sede_id' => $request->sede_id,
                'status' => $request->status,
            ]);
            DB::commit();
            return redirect()->route('bloque.show', ['bloque' => $bloque->id])->with('success', 'Bloque Actualizado con éxito.');
        }catch (QueryException $e){
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar el bloque. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bloque $bloque)
    {
        try {
            DB::beginTransaction();
            $bloque->delete();
            DB::commit();
            return redirect()->route('bloque.index')->with('success', 'Bloque eliminado exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error','El bloque esta siendo usado y no es posible eliminarlo.');
            }
        }
    }
    public function cambiarEstado(Bloque $bloque){
        try{
            DB::beginTransaction();
            if($bloque->status == 1){

                $bloque->update(['status' => 0]);
            }else{
                $bloque->update(['status' => 1]);
            }
            DB::commit();
            return redirect()->back();
        }catch (QueryException $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Ha ocurrido un error al actualizar el estado del bloque' . $e);
        }
    }
}
