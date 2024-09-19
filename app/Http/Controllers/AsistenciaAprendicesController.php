<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaAprendiz;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsistenciaAprendicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try {
            // Obtener todos los datos de la solicitud
            $data = $request->all();

            // Registrar los datos en el log
            Log::info('Datos de la solicitud HTTP:', $data);

            // Guardar los datos en la base de datos
            foreach ($data['attendance'] as $attendance) {
                // Convertir el valor de hora_ingreso al formato correcto
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
            // Registrar el error en el log
            Log::error('Error saving attendance:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Error saving attendance', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request)
    {
        try {
            // Obtener los datos de la solicitud
            $data = $request->all();
            
            // Registrar los datos en el log
            Log::info('Datos de la solicitud HTTP para actualizar:', $data);

            // Verificar que los datos necesarios estén presentes
            if (!isset($data['caracterizacion_id']) || !isset($data['hora_salida']) || !isset($data['fecha'])) {
                return response()->json(['message' => 'Datos incompletos'], 400);
            }

            // Convertir el valor de hora_salida al formato correcto
            $horaSalida = Carbon::parse($data['hora_salida'])->format('Y-m-d H:i:s');

            // Buscar todas las asistencias por caracterizacion_id y fecha de creación
            $asistencias = AsistenciaAprendiz::where('caracterizacion_id', $data['caracterizacion_id'])
                ->whereDate('created_at', $data['fecha'])
                ->select('id', 'hora_salida')
                ->get();
            
            // Registrar las asistencias encontradas en el log
            Log::info('Asistencias encontradas:', ['asistencias' => $asistencias]);

            if ($asistencias->isEmpty()) {
                return response()->json(['message' => 'Asistencias no encontradas'], 404);
            }

            // Actualizar cada asistencia
            foreach ($asistencias as $asistencia) {
                $asistencia->hora_salida = $horaSalida;
                $asistencia->save();
            }

            return response()->json(['message' => 'Asistencias actualizadas con éxito'], 200);
        } catch (Exception $e) {
            // Registrar el error en el log
            Log::error('Error actualizando asistencias:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Error actualizando asistencias', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
