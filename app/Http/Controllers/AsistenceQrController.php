<?php

namespace App\Http\Controllers;

use App\Models\CaracterizacionPrograma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AsistenciaAprendiz;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->all(); 

        dd($data);
       foreach( $data['asistencia'] as $asistence ){

        $asistenceData = json_decode($asistence, true);
        
        $asistencia = AsistenciaAprendiz::create([
            'caracterizacion_id' => $data['caracterizacion_id'], 
            'nombres' => $asistenceData['nombres'], 
            'apellidos' => $asistenceData['apellidos'], 
            'numero_identificacion' => $asistenceData['identificacion'], 
            'hora_ingreso' => $asistenceData['hora_ingreso'],
        ]); 
       }
       
       dd($asistencia); 

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    ///***** METODOS QUE PERMITEN OBTENES LA LISTA DE ASISTENCIA POR HORARIO Y JORNADA    **** */

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
