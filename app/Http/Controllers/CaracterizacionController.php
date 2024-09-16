<?php

namespace App\Http\Controllers;

use App\Models\CaracterizacionPrograma;
use App\Models\FichaCaracterizacion;
use App\Models\Instructor;
use App\Models\JornadaFormacion;
use App\Models\ProgramaFormacion;
use App\Models\Sede;

use Illuminate\Http\Request;

class CaracterizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $caracteres = CaracterizacionPrograma::with('ficha', 'instructor', 'programaFormacion', 'jornada', 'sede')->get();

        return view('caracterizacion.index', compact('caracteres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('caracterizacion.create', [
           'fichas' => FichaCaracterizacion::all(),
           'programas' => ProgramaFormacion::all(),
           'instructores' => Instructor::all(), 
           'jornadas' => JornadaFormacion::all(), 
           'sedes' => Sede::all(), 
       ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        

    $request->validate([
        'ficha_id' => 'required|exists:fichas_caracterizacion,id',
        'programa_formacion_id' => 'required|exists:programas_formacion,id',
        'instructor_id' => 'required|exists:instructors,id',
        'jornada_id' => 'required|exists:jornadas_formacion,id',
        'sede_id' => 'required|exists:sedes,id',
    ]);

    $caracterizacion = new CaracterizacionPrograma();
    $caracterizacion->ficha_id = $request->input('ficha_id');
    $caracterizacion->programa_formacion_id = $request->input('programa_formacion_id');
    $caracterizacion->instructor_id = $request->input('instructor_id');
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
            'instructor_id' => 'required|exists:instructors,id',
            'jornada_id' => 'required|exists:jornadas_formacion,id',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $caracterizacion = CaracterizacionPrograma::findOrFail($id);
        $caracterizacion->ficha_id = $request->input('ficha_id');
        $caracterizacion->programa_formacion_id = $request->input('programa_formacion_id');
        $caracterizacion->instructor_id = $request->input('instructor_id');
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

        $caracterizacion = CaracterizacionPrograma::findOrFail($id);
        $caracterizacion->delete();

        return redirect()->route('caracterizacion.index')->with('success', 'Caracterización eliminada exitosamente.');
    }


    public function CaracterizacionByInstructor(String $id){
        $caracterizaciones = CaracterizacionPrograma::with('ficha', 'programaFormacion', 'jornada', 'sede')
        ->where('instructor_id', $id)
        ->get();

        return response()->json($caracterizaciones);
    }
}
