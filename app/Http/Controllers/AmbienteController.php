<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Http\Requests\StoreAmbienteRequest;
use App\Http\Requests\UpdateAmbienteRequest;
use App\Models\Piso;
use App\Models\Regional;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function cargarAmbientes($piso_id)
    {
        // DB::enableQueryLog();
        $ambientes = Ambiente::where('piso_id', $piso_id)->get();
        return response()->json(['success' => true, 'ambientes' => $ambientes]);
        // dd(DB::getQueryLog());
    }
    public function apiCargarAmbientes(Request $request)
    {
        $regional_id = $request->regional_id;

        // Encontrar la regional por su ID
        $regional = Regional::find($regional_id);

        // Verificar si la regional existe
        if (!$regional) {
            return response()->json(['error' => 'Regional no encontrada'], 404);
        }

        // Inicializar el array para almacenar los ambientes
        $ambientes = array();

        // Recorrer las relaciones para obtener todos los ambientes
        foreach ($regional->sedes as $sede) {
            foreach ($sede->bloques as $bloque) {
                foreach ($bloque->piso as $piso) {

                //     // Combinar todos los ambientes en el array
                // $ambientes = array($piso->ambientes);
                    $ambientes = array_merge($ambientes, $piso->ambientes->toArray());
                }
            }
        }
        // return response()->json($pisos, 200);
        // Devolver la respuesta en formato JSON con el código de estado 200
        return response()->json($ambientes, 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regionales = Regional::where('status', 1)->get();
        return view('ambiente.create', compact('regionales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmbienteRequest $request)
    {
        try {
            DB::beginTransaction();
            $ambiente = Ambiente::create([
                'title' => $request->input('title'),
                'piso_id' => $request->input('piso_id'),
                'user_create_id' => Auth::user()->id,
                'user_edit_id' => Auth::user()->id,
            ]);
            DB::commit();
            return redirect()->route('ambiente.index')->with('success', '¡Registro Exitoso!');
        } catch (QueryException $e) {
            DB::rollBack();
            // Manejar excepciones de la base de datos
            // @dd($e);
            return redirect()->back()->with('error', 'Error de base de datos. Por favor, inténtelo de nuevo.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejar otras excepciones
            // @dd($e);
            return redirect()->back()->with('error', 'Se produjo un error. Por favor, inténtelo de nuevo.');
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
        try{
            DB::beginTransaction();
                $ambiente->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Ambiente eliminado con éxito!');
        }catch (QueryException $e){
            DB::rollBack();

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'El Ambiente esta siendo usado y no puede ser eliminado!');
            }
        }
    }
    public function cambiarEstado(Ambiente $ambiente){
        try {
            DB::beginTransaction();
            if ($ambiente->status == 1) {
                $ambiente->update([
                    'status' => 0,
                ]);
            } else {
                $ambiente->update([
                    'status' => 1,
                ]);
            }
            DB::commit();
            return redirect()->back();
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'No se pudo cambiar el estado del Ambiente.');
        }
    }
}
