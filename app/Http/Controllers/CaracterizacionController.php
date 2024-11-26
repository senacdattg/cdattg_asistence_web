<?php

namespace App\Http\Controllers;

use App\Models\CaracterizacionPrograma;
use App\Models\FichaCaracterizacion;
use App\Models\Instructor;
use App\Models\JornadaFormacion;
use App\Models\Persona;
use App\Models\ProgramaFormacion;
use App\Models\Sede;
use Faker\Provider\ar_EG\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaracterizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $caracteres = CaracterizacionPrograma::with('ficha')->get();

        return view('caracterizacion.index', compact('caracteres'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('caracterizacion.create', [
           'fichas' => FichaCaracterizacion::all(),
       ]);
    }

    public function getCaracterByFicha(Request $request) {
        $request->validate([
            'ficha_id' => 'required|integer|exists:fichas_caracterizacion,id',
        ]);

        $fichaId = $request->input('ficha_id');

    
        $ficha = FichaCaracterizacion::with(['programaFormacion'])->find($fichaId);
        $sedePrograma = $ficha->programaFormacion->sede_id; 

        
        $sede = Sede::find($sedePrograma); 
        $instructors = Instructor::all(); 
        $jornadas = JornadaFormacion::all();

       

        return view('caracterizacion.caracterizacion', compact('ficha', 'sede', 'instructors', 'jornadas')); 

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ficha_id' => 'required|exists:fichas_caracterizacion,id',
            'programa_id' => 'required|exists:programas_formacion,id',
            'sede_id' => 'required|exists:sedes,id',
            'jornada_id' => 'required|exists:jornadas_formacion,id',
            'persona_id' => 'required|exists:instructors,persona_id',
        ]);
        
        $caracterizacion = new CaracterizacionPrograma();
        $caracterizacion->ficha_id = $request->input('ficha_id');
        $caracterizacion->programa_formacion_id = $request->input('programa_id');
        $caracterizacion->instructor_persona_id = $request->input('persona_id');
        $caracterizacion->jornada_id = $request->input('jornada_id');
        $caracterizacion->sede_id = $request->input('sede_id');
        
        $caracterizacion->save();

        return redirect()->route('caracterizacion.index')->with('success', 'Caracterización creada exitosamente.');
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
        $caracterizacion = CaracterizacionPrograma::findOrFail($id);
        return view('caracterizacion.edit', [
            'caracterizacion' => $caracterizacion,
            'fichas' => FichaCaracterizacion::all(),
            'programas' => ProgramaFormacion::all(),
            'instructores' => Instructor::all(),
            'jornadas' => JornadaFormacion::all(),
            'sedes' => Sede::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     
        $request->validate([
            'ficha_id' => 'required|exists:fichas_caracterizacion,id',
            'programa_formacion_id' => 'required|exists:programas_formacion,id',
            'instructor_persona_id' => 'required|exists:instructors,persona_id',
            'jornada_id' => 'required|exists:jornadas_formacion,id',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $caracterizacion = CaracterizacionPrograma::findOrFail($id);
        $caracterizacion->ficha_id = $request->input('ficha_id');
        $caracterizacion->programa_formacion_id = $request->input('programa_formacion_id');
        $caracterizacion->instructor_persona_id = $request->input('instructor_persona_id');
        $caracterizacion->jornada_id = $request->input('jornada_id');
        $caracterizacion->sede_id = $request->input('sede_id');

        $caracterizacion->save();

        return redirect()->route('caracterizacion.index')->with('success', 'Caracterización actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $caracterizacion = CaracterizacionPrograma::where('id', $id);
        $caracterizacion->delete();

        return redirect()->route('caracterizacion.index')->with('success', 'Caracterización eliminada exitosamente.');
    }


    public function CaracterizacionByInstructor(String $id){ 
        $caracterizaciones = CaracterizacionPrograma::with('ficha', 'programaFormacion', 'persona', 'jornada', 'sede')
            ->where('instructor_persona_id', $id)
            ->get()
            ->map(function ($caracterizacion) {
                return [
                    'id' => $caracterizacion->id,
                    'ficha' => $caracterizacion->ficha->ficha ?? 'N/A',
                    'programa_formacion' => $caracterizacion->programaFormacion->nombre ?? 'N/A',
                    'persona' => $caracterizacion->persona->primer_nombre ?? '' .' '. $caracterizacion->persona->segundo_nombre  ?? '' . ' ' . $caracterizacion->persona->primer_apellido ?? ''.' '. $caracterizacion->persona->segundo_apellido ?? '',
                    'jornada' => $caracterizacion->jornada->jornada ?? 'N/A',
                    'sede' => $caracterizacion->sede->sede ?? 'N/A',
                ];
            });

        if ($caracterizaciones->isNotEmpty()) {
            return response()->json($caracterizaciones, 200);
        } else {
            return response()->json(['message' => 'No se encontraron caracterizaciones.'], 404);
        }

    }
}
