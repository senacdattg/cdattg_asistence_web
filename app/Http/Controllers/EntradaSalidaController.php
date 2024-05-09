<?php

namespace App\Http\Controllers;

use App\Models\EntradaSalida;
use App\Http\Requests\StoreEntradaSalidaRequest;
use App\Http\Requests\UpdateEntradaSalidaRequest;
use App\Models\Ambiente;
use App\Models\FichaCaracterizacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class EntradaSalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $fichaCaracterizacion)
    {
        $ficha = FichaCaracterizacion::where('id', $fichaCaracterizacion);

        $registros = EntradaSalida::where('instructor_user_id', Auth::user()->id)
            ->where('fecha', Carbon::now()->toDateString())
            ->where('listado', null)->get();

        // Pasa los registros a la vista
        return view('entradaSalidas.index', compact('registros', 'ficha'));
    }
    public function apiIndex(Request $request)
    {
        $id_ficha_caracterizacion = $request->id_ficha_caracterizacion;
        $id_instructor = $request->id_instructor;
        // @dd($fichaCaracterizacion);
        $fecha = Carbon::now()->toDateString();
        $fichaCaracterizacion = FichaCaracterizacion::where('id', $id_ficha_caracterizacion)->first();
        $ficha = [
            "id" => $fichaCaracterizacion->id,
            "ficha" => $fichaCaracterizacion->ficha,
            "nombre_curso" => $fichaCaracterizacion->nombre_curso,
            // "instructor_asignado" => [
            //     "id" => $fichaCaracterizacion->instructor_asignado,
            //     "primer_nombre" => $fichaCaracterizacion->instructor->persona->primer_nombre,
            //     "segundo_nombre" => $fichaCaracterizacion->instructor->persona->segundo_nombre,
            //     "primer_apellido" => $fichaCaracterizacion->instructor->persona->primer_apellido,
            //     "segundo_apellido" => $fichaCaracterizacion->instructor->persona->segundo_apellido,
            // ],
            // "created_at" => $fichaCaracterizacion->created_at,
            // "updated_at" => $fichaCaracterizacion->updated_at,
            "ambiente" => $fichaCaracterizacion->ambiente->title,
        ];
        // @dd($ficha);
        // Obtén todos los registros de entrada/salida del usuario actual
        $registros = EntradaSalida::where('instructor_user_id', $id_instructor)
            ->where('fecha', Carbon::now()->toDateString())
            ->where('ficha_caracterizacion_id', $id_ficha_caracterizacion)
            ->where('listado', null)
            ->get();
        // @dd($registros);
        $datos = [
            "ficha_caracterizacion" => $ficha,
            "fecha" => $fecha,
            "registros" => $registros
        ];
        return response()->json($datos);
    }
    public function registros(Request $request)
    {
        $fichaCaracterizacion = $request->ficha_id;
        $ambiente_id = $request->ambiente_id;

        $ambiente = Ambiente::where('id', $ambiente_id)->first();
        $descripcion = $request->descripcion;
        $fecha = Carbon::now()->toDateString();
        // @dd($ficha);
        $ficha = FichaCaracterizacion::where('id', $fichaCaracterizacion)->first();
        // Obtén todos los registros de entrada/salida del usuario actual
        $registros = EntradaSalida::where('instructor_user_id', Auth::user()->id)
            ->where('fecha', Carbon::now()->toDateString())
            ->where('ficha_caracterizacion_id', $fichaCaracterizacion)
            ->where('listado', null)->get();
        // @dd($registros);
        // Pasa los registros a la vista
        return view('entradaSalidas.index', compact('registros', 'ficha', 'fecha', 'ambiente', 'descripcion'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entradaSalidas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function apiStoreEntradaSalida(Request $request)
    {
        $ficha_caracterizacion_id = $request->ficha_caracterizacion_id;
        $aprendiz = $request->aprendiz;
        $instructor_user_id = $request->instructor_user_id;
        $entradaSalida = EntradaSalida::create([
            'fecha' => Carbon::now()->toDateString(),
            'instructor_user_id' => $instructor_user_id,
            'aprendiz' => $aprendiz,
            'entrada' => Carbon::now(),
            'ficha_caracterizacion_id' => $ficha_caracterizacion_id,
        ]);
        if ($entradaSalida) {

            return response()->json(["message" => "Entrada Salida creada con éxito"], 200);
        } else {
            return response()->json(["error" => "Error al crear la entrada salida"], 500);
        }
    }
    public function apiUpdateEntradaSalida(Request $request)
    {
        $aprendiz = $request->aprendiz;
        $entradaSalida = EntradaSalida::where('aprendiz', $aprendiz)
            ->where('salida', null)
            ->where('listado', null)
            ->first();

        if ($entradaSalida) {

            $entradaSalida->update([
                'salida' => Carbon::now(),
            ]);
            return response()->json(["message" => "Entrada salida Actualizada con éxito"], 200);
        } else {
            return response()->json(["error" => "Error al crear la entrada salida"], 500);
        }
    }
    public function storeEntradaSalida($ficha_id, $aprendiz, $ambiente_id, $descripcion)
    {

        // @dd('holis');
        try {
            // crear aprendiz
            $entradaSalida = EntradaSalida::create([
                'fecha' => Carbon::now()->toDateString(),
                'instructor_user_id' => Auth::user()->id,
                'aprendiz' => $aprendiz,
                'entrada' => Carbon::now(),
                'ficha_caracterizacion_id' => $ficha_id,
            ]);


            return redirect()->route('entradaSalida.registros', compact('ficha_id','ambiente_id', 'descripcion'))->with('success', '¡Registro Exitoso!');
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
    public function store(StoreEntradaSalidaRequest $request)
    {
        @dd('holis');
        try {

            $validator = validator::make($request->all(), [
                // 'user_id' => Auth::user()->id,
                'aprendiz' => 'required|string',
                'ficha_caracterizacion_id' => 'required',
            ]);
            // @dd($request->ficha_caracterizacion_id);

            if ($validator->fails()) {
                // @dd('holis');
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // @dd('parela ahí');

            // Crear Persona
            $entradaSalida = EntradaSalida::create([
                'fecha' => Carbon::now()->toDateString(),
                'instructor_user_id' => Auth::user()->id,
                'aprendiz' => $request->input('aprendiz'),
                'entrada' => Carbon::now(),
                'ficha_caracterizacion_id' => $request->input('ficha_caracterizacion_id'),
            ]);


            return redirect()->route('entradaSalida.registros', compact(''))->with('success', '¡Registro Exitoso!');
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
    public function updateSalida(Request $request)
    {
        try {
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
            if ($entradaSalida) {

                $entradaSalida->update([
                    'salida' => Carbon::now(),
                ]);
                return redirect()->route('entradaSalida.registros', ['fichaCaracterizacion' => $entradaSalida->ficha_caracterizacion_id])->with('success', 'Salida Exitosa');
            } else {
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
    public function updateEntradaSalida($aprendiz)
    {
        try {
            $entradaSalida = EntradaSalida::where('aprendiz', $aprendiz)
                ->where('salida', null)->first();

            if ($entradaSalida) {

                $entradaSalida->update([
                    'salida' => Carbon::now(),
                ]);
                return redirect()->route('entradaSalida.registros', ['fichaCaracterizacion' => $entradaSalida->ficha_caracterizacion_id])->with('success', 'Salida Exitosa');
            } else {
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
    public function crearCarpetaUser()
    {
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
    public function generarCSV($ficha)
    {
        // @dd($ficha);
        $lista = EntradaSalida::where('instructor_user_id', Auth::user()->id)
            ->where('fecha', Carbon::now()->toDateString())
            ->where('ficha_caracterizacion_id', $ficha)->get();
        // @dd($lista);
        $fichaCaracterizacion = FichaCaracterizacion::find($ficha);
        // @dd($fichaCaracterizacion);
        $fecha_actual = now()->format('Y-m-d_H-i-s');

        $nombre_archivo = $fichaCaracterizacion->ficha . '-' . $fecha_actual . '.csv';

        // Inicializar el contenido del archivo CSV
        $csv_content = "Aprendiz,Ficha,Fecha,HoraEntrada,HoraSalida" . PHP_EOL;

        // Agregar las líneas al contenido del archivo
        foreach ($lista as $linea) {
            // @dd($linea);
            $csv_content .= $linea->aprendiz . ',' . $fichaCaracterizacion->ficha . ',' . $linea->fecha . ',' . $linea->entrada . ',' . $linea->salida . PHP_EOL;
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

        $this->marcarListado($ficha);

        // Devolver una respuesta JSON después de la descarga
        return $response;
    }
    /**
     * Display the specified resource.
     */
    public  function marcarListado($ficha)
    {
        DB::table('entrada_salidas')
            ->where('instructor_user_id', Auth::user()->id)
            ->where('fecha', Carbon::now()->toDateString())
            ->where('ficha_caracterizacion_id', $ficha)
            ->update(['listado' => 1]);
    }
    public function destroyFichaCaractrizacion()
    {
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
        return view('entradaSalidas.edit');
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
        $entradaSalida->delete();
        return redirect()->back()->with('success', '¡Registro eliminado exitosamente!');
    }

    public function cargarDatos(Request $request)
    {
        $data = $request->validate([
            'evento' => 'required',
            'ficha_id',
        ]);
        // @dd($request->ficha_caracterizacion_id);
        $ficha_id = $request->ficha_id;
        $evento = $request->evento;
        $ambiente_id = $request->ambiente_id;
        $descripcion = $request->descripcion;
        if ($request->evento == 1) {
            // @dd('se supone que aqui vamos bien' . $request->evento);
                return view('entradaSalidas.create', compact('ficha_id', 'evento', 'ambiente_id', 'descripcion'));
        } else {
            return view('entradaSalidas.edit', compact('ficha_id','evento', 'ambiente_id', 'descripcion'));
        }
    }
}
