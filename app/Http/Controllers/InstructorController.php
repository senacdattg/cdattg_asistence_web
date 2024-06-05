<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Models\FichaCaracterizacion;
use App\Models\Persona;
use App\Models\Regional;
use App\Models\Tema;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructores = Instructor::paginate(10);
        return view('Instructores.index', compact('instructores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // llamar los tipos de documentos
        $documentos = Tema::with(['parametros' => function ($query){
            $query->wherePivot('status', 1);
        }])->findOrFail(2);
        // llamar los generos
        $generos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(3);
        $regionales = Regional::where('status', 1)->get();


        return view('Instructores.create', compact('documentos','generos', 'regionales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequest $request)
    {
        try {
            DB::beginTransaction();
            // Crear Persona
            $persona = Persona::create([
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre'),
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido'),
                'fecha_de_nacimiento' => $request->input('fecha_de_nacimiento'),
                'genero' => $request->input('genero'),
                'email' => $request->input('email'),
            ]);

            $instructor = Instructor::create([
                'persona_id' => $persona->id,
                'regional_id' => $request->regional_id,
            ]);
            // Crear Usuario asociado a la Persona
            $user = User::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('numero_documento')),
                'persona_id' => $persona->id,
            ]);
            $user->assignRole('INSTRUCTOR');
            DB::commit();
            return redirect()->route('instructor.index')->with('success', '¡Registro Exitoso!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            // @dd($e);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error de base de datos. Por favor, inténtelo de nuevo.' . $e->getMessage());
        }
        // catch (\Exception $e) {
        //     // Manejar otras excepciones
        //     @dd($e);
        //     return redirect()->back()->withErrors(['error' => 'Se produjo un error. Por favor, inténtelo de nuevo.']);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        $fichasCaracterizacion = FichaCaracterizacion::all();
        $instructor->persona->edad = Carbon::parse($instructor->persona->fecha_de_nacimiento)->age;
        $instructor->persona->fecha_de_nacimiento = Carbon::parse($instructor->persona->fecha_de_nacimiento)->format('d/m/Y');
        return view('Instructores.show', compact('instructor', 'fichasCaracterizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        // llamar los tipos de documentos
        $documentos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(2);
        // llamar los generos
        $generos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(3);
        $regionales = Regional::where('status', 1)->get();
        return view('Instructores.edit', ['instructor' => $instructor], compact('documentos', 'generos', 'regionales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor)
    {
        try {
            DB::beginTransaction();
            $persona = Persona::find($instructor->persona_id);
            $persona->update([
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre'),
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido'),
                'fecha_de_nacimiento' => $request->input('fecha_de_nacimiento'),
                'genero' => $request->input('genero'),
                'email' => $request->input('email'),
            ]);

            $instructor->update([
                'persona_id' => $persona->id,
                'regional_id' => $request->regional_id,
            ]);
            // actualizar Usuario asociado a la Persona
            $user = User::where('persona_id', $persona->id);
            $user->update([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('numero_documento')),
            ]);
            DB::commit();
            return redirect()->route('instructor.index')->with('success', '¡Actualización Exitosa!');
        } catch (QueryException $e) {
            // Manejar excepciones de la base de datos
            // @dd($e);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error de base de datos. Por favor, inténtelo de nuevo.' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        try {
            DB::beginTransaction();
            $instructor->delete();
            DB::commit();
            return redirect()->route('instructor.index')->with('success', 'Instructor eliminado exitosamente');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {

                return redirect()->back()->with('error', 'El instructor se encuentra en uso en estos momentos, no se puede eliminar');
            }
        }
    }
}
