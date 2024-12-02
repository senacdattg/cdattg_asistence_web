<?php

namespace App\Http\Controllers;

use App\Models\CaracterizacionPrograma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AsistenciaAprendiz;
use App\Models\JornadaFormacion;
use Herramientas;
use Illuminate\Support\Facades\Log;


class AsistenceQrController extends Controller
{
    /**
     * Muestra una lista de todas las fichas de caracterización.
     *
     * Este método recupera todas las fichas de caracterización junto con su
     * relación 'programaFormacion' y las pasa a la vista 'fichas.index'.
     *
     * @return \Illuminate\View\View La vista que muestra la lista de fichas de caracterización.
     */
    public function index()
    {

        $user = Auth::user(); 
        $id_person = $user->persona_id; 
      
        $caracterizaciones = CaracterizacionPrograma::where('instructor_persona_id', $id_person)->get(); 
        
        if (!$caracterizaciones) {
            return response()->json(['message' => 'El instructor no tiene fichas de caracterización asignadas'], 404);
        }

        return view('qr_asistence.caracter_selecter', compact('caracterizaciones')); 
       
    }

    /**
     * Muestra la vista para seleccionar la caracterización.
     *
     * @param int $id El ID de la caracterización.
     * @return \Illuminate\View\View La vista de selección de caracterización.
     */
    public function caracterSelected( $id )
    {
        $caracterizacion_id = $id; 
        $caracterizacion = CaracterizacionPrograma::find($caracterizacion_id);
        
       
        return view('qr_asistence.index', compact('caracterizacion'));
        
    }



    /**
     * Almacena la asistencia de los aprendices en la base de datos.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de la asistencia.
     * @return \Illuminate\Http\RedirectResponse Redirige a la ruta 'qr_asistence.index' con un mensaje de éxito o error.
     */
    public function store(Request $request)
    {
       $data = $request->all(); 
      
       if(!$data){
        return back()->with('Error', 'No hay datos registrados.');
       }
      
       foreach( $data['asistencia'] as $asistence ){

        $asistenceData = json_decode($asistence, true);
        Log::info($asistenceData);
       
        $asistencia = AsistenciaAprendiz::create([
            'caracterizacion_id' => $data['caracterizacion_id'], 
            'nombres' => $asistenceData['nombres'], 
            'apellidos' => $asistenceData['apellidos'], 
            'numero_identificacion' => $asistenceData['identificacion'], 
            'hora_ingreso' => $asistenceData['hora_ingreso'],
        ]); 
        
       }

       
        if (!empty($asistencia) || $asistencia !== null) {
            return back()->with('success', 'Asistencia registrada exitosamente.');
        } else {
            return back()->with('error', 'Error al registrar la asistencia.');
        }

    }


  
    /**
     * Obtiene la lista de asistencias web para una ficha y jornada específicas.
     *
     * @param String $ficha El identificador de la ficha.
     * @param String $jornada El identificador de la jornada.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View Redirige de vuelta con un mensaje de error o muestra la vista con la lista de asistencias.
     */
    public function getAsistenceWebList (String $ficha, String $jornada) {

        // Obtiene la hora y fecha actual
        $horaEjecucion = Carbon::now()->format('H:i:s');
        $fechaActual = Carbon::now()->format('Y-m-d');

        // Obtiene la jornada de formación basada en el identificador de jornada
        $obJornada = JornadaFormacion::where('jornada', $jornada)->first();

        // Formatea las horas de inicio y fin de la jornada
        $hI = Carbon::parse($obJornada->hora_inicio)->format('H'); 
        $mI = Carbon::parse($obJornada->hora_inicio)->format('i');
        $h2I = Carbon::parse($obJornada->hora_fin)->format('H');
        $m2F = Carbon::parse($obJornada->hora_fin)->format('i');
       
        // Obtiene las asistencias de los aprendices para la ficha y jornada especificadas en la fecha actual
        $asistencias = AsistenciaAprendiz::whereHas('caracterizacion', function ($query) use ($ficha, $jornada) {
            $query->whereHas('ficha', function ($query) use ($ficha) {
                $query->where('ficha', $ficha);
            })->whereHas('jornada', function ($query) use ($jornada) {
                $query->where('jornada', $jornada);
            });
        })->whereDate('created_at', $fechaActual)->get();

        // Itera sobre las asistencias obtenidas
        foreach ($asistencias as $asistencia){
             
            // Formatea la hora y fecha de ingreso de la asistencia
            $hourEnter = Carbon::parse($asistencia->hora_ingreso)->format('H:i:s');
            $dateEnter =  carbon::parse($asistencia->created_at)->format('Y-m-d'); 

            // Valida si la hora de ejecución está dentro del rango de la jornada y si la fecha de ingreso es la actual
            if($this->validateHour($horaEjecucion, $jornada , $hI , $mI , $h2I , $m2F) == true  && $dateEnter == $fechaActual){
                if ($asistencias->isEmpty() || $asistencias === null) {
                    return back()->with('error', 'No se encontraron asistencias para la ficha y jornada proporcionadas');
                }
                return view('qr_asistence.showList', compact('asistencias', 'ficha'));
            }

            // Si no se encontraron asistencias, redirige de vuelta con un mensaje de error
            if ($asistencias->isEmpty() || $asistencias === null) {
                return back()->with('error', 'No se encontraron asistencias para la ficha y jornada proporcionadas');
            }
        }
    }


    ///***** METODOS QUE PERMITEN OBTENER LA LISTA DE ASISTENCIA POR HORARIO Y JORNADA    **** */

    public function validateHour($ingreso, $jornada, $hora1, $min1, $hora2, $min2)
    {
        $horaInicio = Carbon::createFromTime($hora1, $min1 , 0); 
        $horaFin = Carbon::createFromTime($hora2, $min2, 0); 
      
        $horaIngreso = Carbon::parse($ingreso);

        if ($horaIngreso->between($horaInicio, $horaFin)) {
            return true;
        }

         // if ($horaIngreso->between($horaInicio, $horaFin) && $jornada === $morning ) {
        //     return true;
        // }


        return false;
    }
    

    /**
     * Verifica si la hora de ingreso está dentro del rango de la mañana y si la jornada es "Mañana".
     *
     * @param string $ingreso La hora de ingreso en formato de cadena.
     * @param string $jornada La jornada a verificar.
     * @return bool Retorna true si la hora de ingreso está entre las 06:00 y las 13:10 y la jornada es "Mañana", de lo contrario retorna false.
     */
    public function morning($ingreso, $jornada)
    {
        $horaInicio = Carbon::createFromTime(06, 00, 0); 
        $horaFin = Carbon::createFromTime(13, 10, 0); 
        $morning = 'Mañana'; 

        $horaIngreso = Carbon::parse($ingreso);

        if ($horaIngreso->between($horaInicio, $horaFin) && $jornada === $morning ) {
            return true;
        }

        return false;
    }

    /**
     * Verifica si la hora de ingreso está dentro del rango de la tarde.
     *
     * @param string $ingreso La hora de ingreso en formato de cadena.
     * @param string $jornada La jornada a verificar, debe ser 'Tarde'.
     * @return bool Retorna true si la hora de ingreso está entre las 13:00 y las 18:10 y la jornada es 'Tarde', de lo contrario retorna false.
     */
    public function afternoon ($ingreso, $jornada){
        $horaInicio = Carbon::createFromTime(13, 00, 0); 
        $horaFin = Carbon::createFromTime(18, 10, 0); 
        $morning = 'Tarde'; 

        $horaIngreso = Carbon::parse($ingreso);

        if ($horaIngreso->between($horaInicio, $horaFin) && $morning === $jornada) {
            return true;
        }

        return false;
    }

    /**
     * Verifica si una hora de ingreso corresponde a la jornada nocturna.
     *
     * @param string $ingreso La hora de ingreso en formato de cadena.
     * @param string $jornada El tipo de jornada (debe ser 'Noche' para que coincida).
     * @return bool Retorna true si la hora de ingreso está entre las 17:50 y las 23:10 y la jornada es 'Noche', de lo contrario retorna false.
     */
    public function night($ingreso, $jornada)
    {
        $horaInicio = Carbon::createFromTime(17, 50, 0); 
        $horaFin = Carbon::createFromTime(23, 10, 0);
        $night = 'Noche'; 

        $horaIngreso = Carbon::parse($ingreso);

        if ($horaIngreso->between($horaInicio, $horaFin) && $jornada === $night) {
            return true;
        }

        return false;
    }

    /*** METODOS PARA REDIRIGIR A FORMULARIO DE ENTRADA Y SALIDA DE LA ASISTENCIA WEB */

    /**
     * Redirige al aprendiz a la vista de salida de asistencia.
     *
     * Este método busca un registro de asistencia de aprendiz basado en la identificación,
     * la hora de ingreso y la fecha proporcionadas. Si no se encuentra un registro que coincida
     * con los datos proporcionados, redirige de vuelta con un mensaje de error. Si se encuentra
     * un registro, redirige a la vista de nueva salida de asistencia con los datos de asistencia.
     *
     * @param string $identificacion El número de identificación del aprendiz.
     * @param string $ingreso La hora de ingreso del aprendiz.
     * @param string $fecha La fecha de la asistencia en formato 'Y-m-d'.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View Redirección con mensaje de error o vista de nueva salida de asistencia.
     */
    public function redirectAprenticeExit (String $identificacion , String $ingreso , String $fecha) {

        $fecha = Carbon::parse($fecha)->format('Y-m-d');
        $asistencia = AsistenciaAprendiz::where('numero_identificacion', $identificacion)
            ->where('hora_ingreso', $ingreso)
            ->whereDate('created_at', $fecha)
            ->first();

    
        if (!$asistencia) {
            return back()->with('error', 'No se encontró asistencia con los datos proporcionados.');
        }

        return view('qr_asistence.newExitAsistence', compact('asistencia')); 
    }

    /**
     * Redirige a la vista de entrada de aprendiz con la asistencia correspondiente.
     *
     * @param string $identificacion Número de identificación del aprendiz.
     * @param string $ingreso Hora de ingreso del aprendiz.
     * @param string $fecha Fecha de la asistencia en formato 'Y-m-d'.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *         Redirige de vuelta con un mensaje de error si no se encuentra la asistencia,
     *         o muestra la vista 'qr_asistence.newEntranceAsistence' con los datos de la asistencia.
     */
    public function redirectAprenticeEntrance (String $identificacion , String $ingreso , String $fecha) {
       
        $fecha = Carbon::parse($fecha)->format('Y-m-d');
        $asistencia = AsistenciaAprendiz::where('numero_identificacion', $identificacion)
            ->where('hora_ingreso', $ingreso)
            ->whereDate('created_at', $fecha)
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No se encontró asistencia con los datos proporcionados.');
        }
       
        return view('qr_asistence.newEntranceAsistence', compact('asistencia'));

    }


    /**** METODOS PARA SALIDDA DE FORMACIÓN Y ACTUALIZACION DE NOVEDADES DE ENTRADA Y SALIDA */

    /**
     * Actualiza la hora de salida de las asistencias de un aprendiz para una fecha específica.
     *
     * @param String $caracterizacion_id El ID de la caracterización del aprendiz.
     * @return \Illuminate\Http\RedirectResponse Redirige de vuelta con un mensaje de éxito o error.
     *
     * Este método busca las asistencias del aprendiz para la fecha actual y actualiza la hora de salida
     * con la hora actual. Si no se encuentran asistencias, redirige de vuelta con un mensaje de error.
     * Si se actualizan las asistencias correctamente, redirige de vuelta con un mensaje de éxito.
     */
    public function exitFormationAsistenceWeb(String $caracterizacion_id) {
        $fechaActual = Carbon::now()->format('Y-m-d');

        $asistencias = AsistenciaAprendiz::where('caracterizacion_id', $caracterizacion_id)
            ->whereDate('created_at', $fechaActual)
            ->get();
        
        if ($asistencias->isEmpty() || $asistencias === null) { 
            return back()->with('error', 'No se encontraron asistencias para la ficha y jornada proporcionadas');
        }

        foreach ($asistencias as $asistencia) {
            $asistencia->update([
                'hora_salida' => Carbon::now()->format('H:i:s')
            ]);
        }

        return back()->with('success', 'Hora de salida actualizada exitosamente.');
    }


    /**
     * Actualiza la hora de salida y la novedad de salida de un registro de asistencia existente.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos necesarios.
     * 
     * @return \Illuminate\Http\RedirectResponse Redirige de vuelta con un mensaje de éxito.
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos falla.
     * 
     * Validación de los datos de entrada:
     * - 'identificacion': Requerido, cadena de texto, máximo 255 caracteres.
     * - 'nombres': Requerido, cadena de texto, máximo 255 caracteres.
     * - 'apellidos': Requerido, cadena de texto, máximo 255 caracteres.
     * - 'novedad': Requerido, cadena de texto, máximo 255 caracteres.
     * 
     * Este método busca un registro de asistencia del aprendiz basado en su número de identificación
     * y la fecha actual. Si se encuentra un registro, actualiza la hora de salida y la novedad de salida.
     */
    public function setNewExitAsistenceWeb(Request $request) {
        $data = $request->all(); 


        $request->validate([
            'identificacion' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'novedad' => 'required|string|max:255',
        ]);

        $fechaEjecucion = Carbon::now()->format('Y-m-d');

        $asistencia = AsistenciaAprendiz::where('numero_identificacion', $data['identificacion'])
            ->whereDate('created_at', $fechaEjecucion)
            ->first();

        $asistencia->update([
            'hora_salida' =>  Carbon::now()->format('H:i:s'),
            'novedad_salida' => $data['novedad']
        ]);

        return back()->with('success', 'Novedad de salida actualizada exitosamente.');

        
    }

    
    /**
     * Establece una nueva novedad de entrada para la asistencia web.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de la novedad de entrada.
     * @return \Illuminate\Http\RedirectResponse Redirige de vuelta con un mensaje de éxito.
     *
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos de la solicitud falla.
     *
     * Validación de la solicitud:
     * - 'identificacion': requerido, cadena de texto, máximo 255 caracteres.
     * - 'nombres': requerido, cadena de texto, máximo 255 caracteres.
     * - 'apellidos': requerido, cadena de texto, máximo 255 caracteres.
     * - 'novedad': requerido, cadena de texto, máximo 255 caracteres.
     *
     * Este método busca una entrada de asistencia para el aprendiz con el número de identificación proporcionado
     * y la fecha actual. Si se encuentra una entrada, actualiza el campo 'novedad_entrada' con la novedad proporcionada.
     */
    public function setNewEntranceAsistenceWeb(Request $request) {
        $data = $request->all(); 
        $request->validate([
            'identificacion' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'novedad' => 'required|string|max:255',
        ]);

        $fechaEjecucion = Carbon::now()->format('Y-m-d');

        $asistencia = AsistenciaAprendiz::where('numero_identificacion', $data['identificacion'])
            ->whereDate('created_at', $fechaEjecucion)
            ->first();

        $asistencia->update([
            'novedad_entrada' => $data['novedad']
        ]);

        return back()->with('success', 'Novedad de entrada actualizada exitosamente.');
    }

}
