<?php

namespace App\Http\Controllers;


use App\Models\Parametro;
use App\Http\Requests\StoreparametroRequest;
use App\Http\Requests\UpdateparametroRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\Tema;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ParametroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Middleware de autenticación para todos los métodos del controlador

        // Middleware específico para métodos individuales
        $this->middleware('can:VER PARAMETRO')->only('index');
        $this->middleware('can:VER PARAMETRO')->only('show');
        $this->middleware('can:CREAR PARAMETRO')->only(['create', 'store']);
        $this->middleware('can:EDITAR PARAMETRO')->only(['edit', 'update']);
        $this->middleware('can:ELIMINAR PARAMETRO')->only('destroy');
    }

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
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            // Actualizar los datos del modelo
            $parametro->update($data);
            DB::commit();
            return redirect()->route('parametro.show', $parametro->id)->with('success', 'Parámetro actualizado exitosamente');
        }catch(QueryException $e){
            DB::rollBack();
            if( $e->getCode() == 23000){
                return redirect()->back()->withErrors(['error' => 'El nombre asignado al parámetro ya existe.']);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(parametro $parametro)
    {
        try{
            DB::beginTransaction();
            $parametro->delete();
            DB::commit();
            return redirect()->route('parametro.index')->with('success', 'Parámetro eliminado exitosamente');
        }catch (QueryException $e){
            DB::rollBack();
            if ($e->getCode() == 23000){
                return redirect()->back()->withErrors(['error' => 'El Parametro esta siendo usado y no es posible eliminarlo.']);
            }
        }

    }
    public function crearParametro(Request $request)
    {

    }
    public function apiGetTipoDocumentos(){
        // llamar los tipos de documentos
        $consultaDocumentos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(2);
        foreach($consultaDocumentos->parametros as $consultaDocumento){
            $documentos[] = [
                'id' => $consultaDocumento->id,
                'name' => $consultaDocumento->name,
            ];
        }
        return response()->json($documentos, 200);
    }
    public function apiGetGeneros(){
        // llamar los generos
        $consultaGeneros = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1)->get();
        }])->findOrFail(3);
        foreach ($consultaGeneros->parametros as $consultaGenero) {
            $generos[]= [
                'id' => $consultaGenero->id,
                'name' => $consultaGenero->name,
            ];
        }
        return response()->json($generos, 200);
    }


}
