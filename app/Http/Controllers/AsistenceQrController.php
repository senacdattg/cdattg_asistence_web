<?php

namespace App\Http\Controllers;

use App\Models\CaracterizacionPrograma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AsistenciaAprendiz;
use Illuminate\Support\Facades\Log;


class AsistenceQrController extends Controller
{
    /**
     * Display a listing of the resource.
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

    public function caracterSelected( $id )
    {
        $caracterizacion_id = $id; 
        $caracterizacion = CaracterizacionPrograma::find($caracterizacion_id);
        
       
        return view('qr_asistence.index', compact('caracterizacion'));
        
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->all(); 
      
      
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


    public function getAsistenceWebList (String $ficha, String $jornada) {

        $horaEjecucion = Carbon::now()->format('H:i:s');
        $fechaActual = Carbon::now()->format('Y-m-d');

        $asistencias = AsistenciaAprendiz::whereHas('caracterizacion', function ($query) use ($ficha, $jornada) {
            $query->whereHas('ficha', function ($query) use ($ficha) {
                $query->where('ficha', $ficha);
            })->whereHas('jornada', function ($query) use ($jornada) {
                $query->where('jornada', $jornada);
            });
        })->whereDate('created_at', $fechaActual)->get();

        foreach ($asistencias as $asistencia){

            $hourEnter = Carbon::parse($asistencia->hora_ingreso)->format('H:i:s');
            $dateEnter =  carbon::parse($asistencia->created_at)->format('Y-m-d'); 

            if($this->morning($horaEjecucion, $jornada) == true  && $this->morning( $hourEnter, $jornada) == true && $dateEnter == $fechaActual){
                if ($asistencias->isEmpty() || $asistencias === null) {
                    return back()->with('error', 'No se encontraron asistencias para la ficha y jornada proporcionadas');
                }
                return view('qr_asistence.showList', compact('asistencias', 'ficha'));
            }; 

            if($this->afternoon($horaEjecucion, $jornada) == true && $this->afternoon($hourEnter, $jornada) == true && $dateEnter == $fechaActual){
                if ($asistencias->isEmpty() || $asistencias === null) {
                    return back()->with('error', 'No se encontraron asistencias para la ficha y jornada proporcionadas');
                }
                return view('qr_asistence.showList', compact('asistencias', 'ficha'));
            }

            if($this->night($horaEjecucion, $jornada) == true && $this->night($hourEnter, $jornada) == true && $dateEnter == $fechaActual){
                if ($asistencias->isEmpty() || $asistencias === null) {
                    return back()->with('error', 'No se encontraron asistencias para la ficha y jornada proporcionadas');
                }
                return view('qr_asistence.showList', compact('asistencias', 'ficha'));
            }

            if ($asistencias->isEmpty() || $asistencias === null) {
                return back()->with('error', 'No se encontraron asistencias para la ficha y jornada proporcionadas');
            }

        }
    }


    ///***** METODOS QUE PERMITEN OBTENER LA LISTA DE ASISTENCIA POR HORARIO Y JORNADA    **** */

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


    /**** METODOS PARA SALIDDA DE FROMACIÓN Y ACTUALIZACION DE NOVEDADES DE ENTRADA Y SALIDA */

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
