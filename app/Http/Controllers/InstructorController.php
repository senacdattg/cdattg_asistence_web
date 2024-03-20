<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Models\Persona;
use App\Models\Tema;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
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


        return view('Instructores.create', compact('documentos','generos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequest $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'tipo_documento' => 'required',
                'numero_documento' => 'required',
                'primer_nombre' => 'required',
                'segundo_nombre' => 'nullable',
                'primer_apellido' => 'required',
                'segundo_apellido' => 'nullable',
                'fecha_de_nacimiento' => 'required|date',
                'genero' => 'required',
                'email' => 'required|email|unique:personas,email|unique:users,email',
            ]);

            if ($validator->fails()) {
                @dd($validator);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

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
            ]);
            // Crear Usuario asociado a la Persona
            $user = User::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('numero_documento')),
                'persona_id' => $persona->id,
            ]);
            $user->assignRole('INSTRUCTOR');

            return redirect()->route('instructor.index')->with('success', '¡Registro Exitoso!');
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

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        $instructor->persona->edad = Carbon::parse($instructor->persona->fecha_de_nacimiento)->age;
        $instructor->persona->fecha_de_nacimiento = Carbon::parse($instructor->persona->fecha_de_nacimiento)->format('d/m/Y');
        return view('Instructores.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        //
    }
}
