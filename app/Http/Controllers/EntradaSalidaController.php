<?php

namespace App\Http\Controllers;

use App\Models\EntradaSalida;
use App\Http\Requests\StoreEntradaSalidaRequest;
use App\Http\Requests\UpdateEntradaSalidaRequest;
use App\Models\FichaCaracterizacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
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
    public function crearCarpetaUser(){
        $user_id = Auth::id(); // Obtener el ID del usuario autenticado

        $carpeta_csv = public_path('csv');
        $carpeta_usuario = public_path('csv/' . $user_id);

        if (!file_exists($carpeta_csv)) {
            mkdir($carpeta_csv, 0777, true);
        }

        if (!file_exists($carpeta_usuario)) {
            mkdir($carpeta_usuario, 0777, true);
            // echo "Carpeta del usuario creada correctamente.";
        } else {
            // echo "La carpeta del usuario ya existe.";
        }
    }
    public function generarCSV(){
        date_default_timezone_set('America/Bogota');

        $lista = EntradaSalida::where('user_id', Auth::user()->id)->get();

        $fecha_actual = now()->format('Y-m-d_H-i-s');

        $nombre_archivo = Auth::user()->fichaCaracterizacion->ficha_caracterizacion . $fecha_actual . '.csv';

        // Inicializar el contenido del archivo CSV
        $csv_content = '';

        // Agregar las líneas al contenido del archivo
        foreach ($lista as $linea) {
            $csv_content .= implode('-', $linea->toArray()) . PHP_EOL;
        }

        // Preparar la respuesta para la descarga
        $response = response()->stream(
            function () use ($csv_content) {
                echo $csv_content;
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $nombre_archivo,
            ]
        );

        // Eliminar las entradas y fichaCaracterizacion relacionadas
        $this->destroyEntradaSalida();
        $this->destroyFichaCaractrizacion();

        // Devolver una respuesta JSON después de la descarga
        return $response;

    }
    /**
     * Display the specified resource.
     */
    public  function destroyEntradaSalida(){
        EntradaSalida::where('user_id', Auth::user()->id)->delete();
    }
    public function destroyFichaCaractrizacion(){
        FichaCaracterizacion::where('user_id', Auth::user()->id)->delete();
    }
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
