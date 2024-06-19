<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Http\Requests\StorePisoRequest;
use App\Http\Requests\UpdatePisoRequest;
use App\Models\Bloque;
use App\Models\Regional;
use App\Models\Sede;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function apiCargarPisos(Request $request)
    {
        $bloque_id = $request->bloque_id;
        // DB::enableQueryLog();
        $pisos = Piso::where('bloque_id', $bloque_id)->get();
        return response()->json($pisos, 200);
        // dd(DB::getQueryLog());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regionales = Regional::where('status', 1)->get();
        return view('piso.create', compact('regionales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePisoRequest $request)
    {
        try {
            DB::beginTransaction();
            $piso = Piso::create([
                'piso' => $request->input('piso'),
                'bloque_id' => $request->input('bloque_id'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
            ]);
            DB::commit();
            return redirect()->route('piso.index')->with('success', '¡Registro Exitoso!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            DB::rollBack();
            return redirect()->back()->with('error','Error de base de datos. Por favor, inténtelo de nuevo.');
        } catch (\Exception $e) {
            // Manejar otras excepciones
            DB::rollBack();
            return redirect()->back()->with('error','Se produjo un error. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Piso $piso)
    {
        return view('piso.show', ['piso' => $piso]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Piso $piso)
    {
        $regionales = Regional::where('status', 1)->get();
        return view('piso.edit', ['piso' => $piso, 'regionales' => $regionales]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePisoRequest $request, Piso $piso)
    {
        try{
            DB::beginTransaction();
            $piso->update([
                'piso' => $request->piso,
                'bloque_id' => $request->bloque_id,
                'status' => $request->status,
            ]);
            DB::commit();
            return redirect()->route('piso.show', ['piso' => $piso->id])->with('success', 'Piso Actualizado con éxito!');
        }catch(QueryException $e){
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error al actualizar el piso.' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Piso $piso)
    {
        try{
            DB::beginTransaction();
            $piso->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Piso Eliminado exitosamente');
        }catch(QueryException $e){
            DB::rollBack();

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'El piso esta siendo usado y no puede ser eliminado. ');
            }
        }

        return redirect()->route('piso.index')->with('success', 'Piso eliminado exitosamente');
    }
    public function cambiarEstado(Piso $piso){
        try{
            DB::beginTransaction();
            if ( $piso->status == 1){
                $piso->update([
                    'status' => 0,
                ]);
            }else{
                $piso->update([
                    'status' => 1,
                ]);
            }
            DB::commit();
            return redirect()->back();
        }catch (QueryException $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Ha ocurrido un error al actualizar el estado del bloque');
        }
    }
}
