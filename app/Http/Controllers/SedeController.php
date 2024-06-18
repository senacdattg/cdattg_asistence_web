<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Http\Requests\StoreSedeRequest;
use App\Http\Requests\UpdateSedeRequest;
use App\Models\Regional;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function cargarSedesByMunicipio($municipio_id)
    {
        $sedes = Sede::where('municipio_id', $municipio_id)
        ->where('status', 1)->get();
        return response()->json(['success' => true, 'sedes' => $sedes]);
    }
    public function cargarSedesByRegional($regional_id)
    {
        $sedes = Sede::where('regional_id', $regional_id)
            ->where('status', 1)->get();
        return response()->json(['success' => true, 'sedes' => $sedes]);
    }
    public function apiCargarSedes(Request $request)
    {
        $municipio_id = $request->municipio_id;
        $sedes = Sede::where('municipio_id', $municipio_id)
            ->where('status', 1)->get();
        return response()->json($sedes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regionales = Regional::where('status', 1)->get();
        return view('sede.create', compact('regionales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSedeRequest $request)
    {
        try {
            $sede = Sede::create([
                'sede' => $request->input('sede'),
                'direccion' => $request->input('direccion'),
                'municipio_id' => $request->input('municipio_id'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
                'regional_id' => $request->input('regional_id'),
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
        return view('sede.show', ['sede' => $sede]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sede $sede)
    {
        $regionales = Regional::where('status', 1)->get();
        return view('sede.edit', ['sede' => $sede], compact('regionales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSedeRequest $request, Sede $sede)
    {
        try{
            DB::beginTransaction();
            $sede->update([
                'sede' => $request->sede,
                'direccion' => $request->direccion,
                'user_edit_id' => Auth::user()->id,
                'status' => $request->status,
                'municipio_id' => $request->municipio_id,
                'regional_id' => $request->regional_id,
            ]);
            DB::commit();
            return redirect()->route('sede.show', $sede->id)->with('success', 'Sede actualizada con éxito!');
        }catch(QueryException $e){
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al momento de actualizar la sede, ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sede $sede)
    {
        try {
            DB::beginTransaction();
            $sede->delete();
            DB::commit();
            return redirect()->route('sede.index')->with('success', 'Sede eliminada exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {

                return redirect()->back()->with('error', 'La sede se encuentra en uso en estos momentos, no se puede eliminar');
            }
        }
    }
    public function cambiarEstadoSede(Sede $sede)
    {
        if ($sede->status === 1) {
            $sede->update(['status' => 0]);
        } else {
            $sede->update(['status' => 1]);
        }
        return redirect()->back();
    }
}
