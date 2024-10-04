<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaAprendiz;
use App\Models\FichaCaracterizacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use PhpParser\Node\Expr\Cast\String_;
use PHPUnit\Framework\Constraint\Count;

class AsistenciaAprendicesController extends Controller
{

    public function index (){
        $fichas = FichaCaracterizacion::select('id', 'ficha')->get();
        return view('asistencias.index', compact('fichas')); 
    }
   
    public function getAttendanceByFicha (Request $request){
       
        try {
            $fichaId = $request->input('ficha');
            if (!$fichaId) {
                return response()->json(['message' => 'ID de ficha no proporcionado'], 400);
            }
            
            $asistencias = AsistenciaAprendiz::whereHas('caracterizacion', function ($query) use ($fichaId) {
                $query->where('ficha_id', $fichaId);
            })->get();

            if ($asistencias->isEmpty()) {
                return response()->json(['message' => 'No se encontraron asistencias para la ficha proporcionada'], 404);
            }
            return view('asistencias.asistencia_by_ficha', ['asistencias' => $asistencias]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error obteniendo asistencias', 'error' => $e->getMessage()], 500);
        }
    
    }

    public function getAttendanceByDateAndFicha(Request $request){
       $ficha = $request->input('ficha');
       $fecha_inicio = $request->input('fecha_inicio');
       $fecha_fin = $request->input('fecha_fin');

         if (!$ficha || !$fecha_inicio || !$fecha_fin) {
              return response()->json(['message' => 'Datos incompletos'], 400);
         }
            $asistencias = AsistenciaAprendiz::whereHas('caracterizacion', function ($query) use ($ficha) {
                $query->where('ficha_id', $ficha);
            })->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->get();
        if ($asistencias->isEmpty()) {
            return response()->json(['message' => 'No se encontraron asistencias para la ficha y fechas proporcionadas'], 404);
        }

        return view('asistencias.asistencia_by_date', ['asistencias' => $asistencias]);
    }

    public function getDocumentsByFicha(Request $request)
    {
        $ficha_id = $request->input('ficha'); 
        
        try {
            if (!$ficha_id) {
                return response()->json(['message' => 'ID de ficha no proporcionado'], 400);
            }

            $documentos = AsistenciaAprendiz::select('numero_identificacion')
            ->whereHas('caracterizacion', function ($query) use ($ficha_id) {
                $query->where('ficha_id', $ficha_id);
            })
            ->get();
            
            if ($documentos->isEmpty()) {
                return response()->json(['message' => 'No se encontraron documentos para la ficha proporcionada'], 404);
            }

            return view('asistencias.consulta_by_document', ['documentos' => $documentos]);


            return response()->json(['documentos' => $documentos], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error obteniendo documentos', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAttendanceByDocument(Request $request){
        $document = $request->input('documento'); 
       
            if ( !$document) {
                return response()->json(['message' => 'Datos incompletos'], 400);
            }

            $asistencias = AsistenciaAprendiz::where('numero_identificacion', $document)->get();
           
            if ($asistencias->isEmpty()) {
                return response()->json(['message' => 'No se encontraron asistencias para la ficha y documento proporcionados'], 404);
            }

            return view('asistencias.asistencia_by_document', ['asistencias' => $asistencias]);

        
    }

  
    public function store(Request $request)
    {
        try {
         
            $data = $request->all();
            foreach ($data['attendance'] as $attendance) {
                $horaIngreso = Carbon::parse($attendance['hora_ingreso'])->format('Y-m-d H:i:s');

                AsistenciaAprendiz::create([
                    'caracterizacion_id' => $data['caracterizacion_id'],
                    'nombres' => $attendance['nombres'],
                    'apellidos' => $attendance['apellidos'],
                    'numero_identificacion' => $attendance['numero_identificacion'],
                    'hora_ingreso' => $horaIngreso,
                ]);
            }

            return response()->json(['message' => 'Lista de asistencia guardada con éxito'], 200);
        } catch (Exception $e) {
            Log::error('Error saving attendance:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Error saving attendance', 'error' => $e->getMessage()], 500);
        }
    }

    
    public function update(Request $request)
    {
            $data = $request->all();
            if (!isset($data['caracterizacion_id']) || !isset($data['hora_salida']) || !isset($data['fecha'])) {
                return response()->json(['message' => 'Datos incompletos'], 400);
            }
            $horaSalida = Carbon::parse($data['hora_salida'])->format('Y-m-d H:i:s');
            $asistencias = AsistenciaAprendiz::where('caracterizacion_id', $data['caracterizacion_id'])
                ->whereDate('created_at', $data['fecha'])
                ->select('id', 'hora_salida')
                ->get();
            if (!$asistencias) {
                return response()->json(['message' => 'Asistencias no encontradas'], 404);
            }

            foreach ($asistencias as $asistencia) {

                if($asistencia->hora_salida == null){
                    $asistencia->hora_salida = $horaSalida;
                }
                $asistencia->save();
            }

            return response()->json(['message' => 'Asistencias actualizadas con éxito'], 200);
        
    }

    public function assistenceNovedad(Request $request)
    {
        if (!$request->has('caracterizacion_id') || !$request->has('numero_identificacion') || !$request->has('hora_entrada') || !$request->has('novedad')) {
            return response()->json(['message' => 'Datos incompletos'], 400);
        }
   
        $caracterizacion_id = $request->input('caracterizacion_id');
        $numero_identificacion = $request->input('numero_identificacion');
        $hora_ingreso_peticion = $request->input('hora_entrada');
        $novedad_salida = $request->input('novedad');

        
        $hora_ingreso = Carbon::parse($hora_ingreso_peticion)->format('H:i:s');

      
        $asistencia = AsistenciaAprendiz::where('caracterizacion_id', $caracterizacion_id)
            ->where('numero_identificacion', $numero_identificacion)
            ->where('hora_ingreso', $hora_ingreso)
            ->first();

        if($asistencia){
            $asistencia->hora_salida = Carbon::now();
            $asistencia->novedad_salida = $novedad_salida;
            $asistencia->save();

            return response()->json(['message' => 'Solicitud de respuesta acepta'], 200);
        }

        if (!$asistencia) {
            return response()->json(['message' => 'No se encontró asistencia'], 404);
        }
        
    }

    public function getList(String $ficha, String $jornada)
    {

        $horaEjecucion = Carbon::now()->format('H:i:s');
        $fechaActual = Carbon::now()->format('Y-m-d');

        $asistencias = AsistenciaAprendiz::whereHas('caracterizacion', function ($query) use ($ficha, $jornada) {
            $query->whereHas('ficha', function ($query) use ($ficha) {
                $query->where('ficha', $ficha);
            })->whereHas('jornada', function ($query) use ($jornada) {
                $query->where('jornada', $jornada);
            });
        })->whereDate('created_at', $fechaActual)->get();

        Log::info('Asistencias: ' . $asistencias);

        foreach ($asistencias as $asistencia){

            $hourEnter = Carbon::parse($asistencia->hora_ingreso)->format('H:i:s');
            $dateEnter =  carbon::parse($asistencia->created_at)->format('Y-m-d'); 

            if($this->morning($horaEjecucion, $jornada) == true  && $this->morning( $hourEnter, $jornada) == true && $dateEnter == $fechaActual){
                return response()->json(['asistencias' => $asistencias], 200);
            }; 

            if($this->afternoon($horaEjecucion, $jornada) == true && $this->afternoon($hourEnter, $jornada) == true && $dateEnter == $fechaActual){
                return response()->json(['asistencias' => $asistencias], 200);
            }

            if($this->night($horaEjecucion, $jornada) == true && $this->night($hourEnter, $jornada) == true && $dateEnter == $fechaActual){
                return response()->json(['asistencias' => $asistencias], 200);
            }

            return response()->json(['message' => 'No se encontraron asistencias para la ficha y jornada proporcionadas'], 404);

        }

        return response()->json(['message' => 'No se encontraron asistencias para la ficha y jornada proporcionadas'], 404);


    }


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


    /***********Metodos para actulizar novedades de estrada y salida**************/ 

    public function updateExitAsistence(Request $request){

        $actualDate = Carbon::now()->format('Y-m-d');
        $actualHour = Carbon::now()->format('H:i:s');

        $data = $request->all();

        $numeroIdentificacion = $data['numero_identificacion'];
        $horaIngreso = Carbon::parse($data['hora_ingreso'])->format('H:i:s');

        Log::info('Hora de ingreso: ' . $horaIngreso);

        $asistencia = AsistenciaAprendiz::where('numero_identificacion', $numeroIdentificacion)
            ->where('hora_ingreso', $horaIngreso)
            ->first();

        Log::info('Asistencia: ' . $asistencia);

        $dateAsistence = $asistencia->created_at->format('Y-m-d');
        $actualHourCarbon = Carbon::parse($actualHour);

        if($this->morningAsistence($horaIngreso, $actualHourCarbon) == true && $dateAsistence == $actualDate){
            $asistencia->novedad_salida = $data['novedad_salida'];
            $asistencia->hora_salida = $actualHour; 
            $asistencia->save();
            return response()->json(['message' => 'Novedad de salidad Actualizada'], 200);
        }

        if($this->affternoonAsistence($horaIngreso, $actualHourCarbon) == true && $dateAsistence == $actualDate){
            $asistencia->novedad_salida = $data['novedad_salida'];
            $asistencia->hora_salida = $actualHour; 
            $asistencia->save();
            return response()->json(['message' => 'Novedad de salidad Actualizada'], 200);
        }

        if($this->nightAsistence($horaIngreso, $actualHourCarbon) == true && $dateAsistence == $actualDate){
            $asistencia->novedad_salida = $data['novedad_salida'];
            $asistencia->hora_salida = $actualHour; 
            $asistencia->save();
            return response()->json(['message' => 'Novedad de salidad Actualizada'], 200);
        }
        
       

        if (!$asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }

        
  
    }

    public function updateEntraceAsistence (Request $request){
        $actualDate = Carbon::now()->format('Y-m-d');
        $actualHour = Carbon::now()->format('H:i:s');

        $data = $request->all();

        log::info('data: '.json_encode($data)); 

        $numeroIdentificacion = $data['numero_identificacion'];
        $horaIngreso = Carbon::parse($data['hora_ingreso'])->format('H:i:s');

        Log::info('Hora de ingreso: ' . $horaIngreso);

        $asistencia = AsistenciaAprendiz::where('numero_identificacion', $numeroIdentificacion)
            ->where('hora_ingreso', $horaIngreso)
            ->first();

        Log::info('Asistencia: ' . $asistencia);

        $dateAsistence = $asistencia->created_at->format('Y-m-d');
        $actualHourCarbon = Carbon::parse($actualHour);

        if($this->morningAsistence($horaIngreso, $actualHourCarbon) == true && $dateAsistence == $actualDate){
            $asistencia->novedad_entrada = $data['novedad_entrada'];
            $asistencia->hora_ingreso = carbon::now()->format('H:i:s');  
            $asistencia->save();
            return response()->json(['message' => 'Novedad de entrada Actualizada'], 200);
        }

        if($this->affternoonAsistence($horaIngreso, $actualHourCarbon) == true && $dateAsistence == $actualDate){
            $asistencia->novedad_entrada = $data['novedad_entrada'];
            $asistencia->save();
            return response()->json(['message' => 'Novedad de entrada Actualizada'], 200);
        }

        if($this->nightAsistence($horaIngreso, $actualHourCarbon) == true && $dateAsistence == $actualDate){
            $asistencia->novedad_entrada = $data['novedad_entrada'];
            $asistencia->save();
            return response()->json(['message' => 'Novedad de entrada Actualizada'], 200);
        }
        
        if (!$asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada'], 404);
        }
    }

    private function morningAsistence($horaIngreso, $actualHour){
        $horaInicio = Carbon::createFromTime(06, 00, 0); 
        $horaFin = Carbon::createFromTime(13, 10, 0);
    
        $horaIngreso = Carbon::parse($horaIngreso);

        if ($horaIngreso->between($horaInicio, $horaFin) && $actualHour->between($horaInicio, $horaFin)) {
            return true;
        }

        return false;
    }

    private function affternoonAsistence($horaIngreso, $actualHour){
        $horaInicio = Carbon::createFromTime(13, 00, 0); 
        $horaFin = Carbon::createFromTime(18, 10, 0);
    
        $horaIngreso = Carbon::parse($horaIngreso);

        if ($horaIngreso->between($horaInicio, $horaFin) && $actualHour->between($horaInicio, $horaFin)) {
            return true;
        }

        return false;

    }

    private function nightAsistence($horaIngreso, $actualHour){
        $horaInicio = Carbon::createFromTime(17, 50, 0); 
        $horaFin = Carbon::createFromTime(23, 10, 0);
        $horaIngreso = Carbon::parse($horaIngreso);

        if ($horaIngreso->between($horaInicio, $horaFin) && $actualHour->between($horaInicio, $horaFin)) {
            return true;
        }

        return false;
    }
    
   
}
