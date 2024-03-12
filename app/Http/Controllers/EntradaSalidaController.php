<?php

namespace App\Http\Controllers;

use App\Models\EntradaSalida;
use App\Http\Requests\StoreEntradaSalidaRequest;
use App\Http\Requests\UpdateEntradaSalidaRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntradaSalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Obtén todos los registros de entrada/salida del usuario actual
        $registros = EntradaSalida::where('user_id', $user->id)->get();

        // Pasa los registros a la vista
        return view('entradaSalidas.index', compact('registros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntradaSalidaRequest $request)
    {
        // @dd('holis');
        try {

            $validator = validator::make($request->all(), [
                // 'user_id' => Auth::user()->id,
                'aprendiz' => 'required|string',
            ]);

            if ($validator->fails()) {
                @dd('holis');
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Crear Persona
            $entradaSalida = EntradaSalida::create([
                'user_id' => Auth::user()->id,
                'aprendiz' => $request->input('aprendiz'),
                'entrada' => Carbon::now(),
            ]);


            return redirect()->route('entradaSalida.index')->with('success', '¡Registro Exitoso!');
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
    public function updateSalida(Request $request){
        try{
            $validator = validator::make($request->all(), [
                'aprendiz' => 'required|string',
            ]);
            if ($validator->fails()) {
                @dd('holis');
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $entradaSalida = EntradaSalida::whereExists(function ($query) use ($request) {
                $query->where('aprendiz', $request->input('aprendiz'))
                    ->where('salida', null);
            })->first();
            if($entradaSalida){

                $entradaSalida->update([
                    'salida' => Carbon::now(),
                ]);
                return redirect()->route('entradaSalida.index')->with('success', 'Salida Exitosa');
            }else{
                return redirect()->back()->withErrors(['error' => 'No ha tomado asistencia a este aprendiz.']);
            }

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
    public function show(EntradaSalida $entradaSalida)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EntradaSalida $entradaSalida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntradaSalidaRequest $request, EntradaSalida $entradaSalida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntradaSalida $entradaSalida)
    {
        //
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
