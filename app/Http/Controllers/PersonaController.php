<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = Persona::paginate(10);
        return view('personas.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
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
                'cargo' => 'required',
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
                'cargo' => $request->input('cargo'),
            ]);

            // Crear Usuario asociado a la Persona
            $user = User::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('numero_documento')),
                'persona_id' => $persona->id,
            ]);

            return redirect()->route('persona.index')->with('success', '¡Registro Exitoso!');
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
    public function show(Persona $persona)
    {
        // $persona = Persona::find(1);
        $persona->edad = Carbon::parse($persona->fecha_de_nacimiento)->age;
        $persona->fecha_de_nacimiento = Carbon::parse($persona->fecha_de_nacimiento)->format('d/m/Y');


        return view('personas.show', ['persona' => $persona]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        // $persona = Persona::find(1);
        return view('personas.edit', ['persona' => $persona]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        // @dd($persona->id);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'primer_nombre' => 'required',
            'segundo_nombre' => 'nullable',
            'primer_apellido' => 'required',
            'segundo_apellido' => 'nullable',
            'fecha_de_nacimiento' => 'required|date',
            'genero' => 'required',
            'email' => 'required|email' ,// Agrega la regla unique con la excepción del registro actual
            'cargo' => 'required',
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            @dd($validator);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Actualizar Persona
            // $persona = Persona::findOrFail($persona);
            $persona->update([
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre'),
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido'),
                'fecha_de_nacimiento' => $request->input('fecha_de_nacimiento'),
                'genero' => $request->input('genero'),
                'email' => $request->input('email') ,
                'cargo' => $request->input('cargo'),
            ]);
            // Actualizar Usuario asociado a la Persona
            $user = User::where('persona_id', $persona->id)->first();

            if ($user) {
                $user->update([
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('numero_documento')),
                    'status' => $request->input('status'),
                ]);
            }

            return redirect()->route('persona.show', ['persona' => $persona->id])
                ->with('success', 'Información actualizada exitosamente');
        } catch (\Exception $e) {
            dd( $e);
            return redirect()->back()->withErrors(['error' => 'Error al actualizar la información. Por favor, inténtelo de nuevo.']);
        }
    }
    public function cambiarEstadoUser(Request $request, string $id)
    {
        //  DB::enableQueryLog();
        // @dd($user->id);
        $user = User::where('persona_id', $id)->first();

        // @dd($user);
        // @dd($user->update(['status' => 0]));
        try {
            if ($user->status === 1) {
                $user->update(['status' => 0]);
            } else {
                $user->update(['status' => 1]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return redirect()->back();
        // dd(DB::getQueryLog());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        //
    }
}
