<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Http\Requests\StoreRegionalRequest;
use App\Http\Requests\UpdateRegionalRequest;
use App\Models\Tema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Middleware de autenticación para todos los métodos del controlador

        // Middleware específico para métodos individuales
        $this->middleware('can:VER REGIONAL')->only('index');
        $this->middleware('can:VER REGIONAL')->only('show');
        $this->middleware('can:CREAR REGIONAL')->only(['create', 'store']);
        $this->middleware('can:CREAR REGIONAL')->only(['edit', 'update']);
        $this->middleware('can:ELIMINAR REGIONAL')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regionales = Regional::paginate();
        return view('regional.index', compact('regionales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('regional.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionalRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'regional' => 'required|string|unique:regionals'
        ]);
        if ($validator->fails()){
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        try{
            DB::beginTransaction();
            $regional = Regional::create([
                'regional' => $request->regional,
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
                'status' => 1,

            ]);
            DB::commit();

            return redirect()->route('regional.show', $regional->id)->with('success', 'Regional creada con éxito');
        }catch(QueryException $e){
            DB::rollBack();
            if($e->getCode() == 23000){
                return redirect()->back()->withInput()->withErrors(['error' => 'Error al momento de crear la regional' . $e->getMessage()]);

            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Error al momento de crear la regional' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Regional $regional)
    {
        return view('regional.show', ['regional' => $regional]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Regional $regional)
    {

        return view('regional.edit', ['regional' => $regional]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionalRequest $request, Regional $regional)
    {
        try {
            DB::beginTransaction();

            $regional->update([
                'regional' => $request->input('regional'),
                'user_edit_id' => Auth::id(),
                'status' => $request->input('status'),
            ]);

            DB::commit();

            return redirect()->route('regional.show', $regional->id)
                ->with('success', 'Regional actualizada con éxito');
        } catch (QueryException $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Error al momento de actualizar la regional: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regional $regional)
    {
        try {
            DB::beginTransaction();
            $regional->delete();
            DB::commit();
            return redirect()->route('regional.index')->with('success', 'Regional eliminada exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {

                return redirect()->back()->with('error', 'La regional se encuentra en uso en estos momentos, no se puede eliminar');
            }
        }
    }
    public function cambiarEstadoRegional(Regional $regional){
        if ($regional->status === 1) {
            $regional->update(['status' => 0]);
        } else {
            $regional->update(['status' => 1]);
        }
        return redirect()->back();
    }
}
