<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\Tema;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PersonaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:VER PERSONA')->only(['index', 'show']);
        $this->middleware('can:CREAR PERSONA')->only(['create', 'store']);
        $this->middleware('can:EDITAR PERSONA')->only(['edit', 'update']);
        $this->middleware('can:ELIMINAR PERSONA')->only('destroy');
        $this->middleware('can:CAMBIAR ESTADO USUARIO')->only('cambiarEstadoUser');
    }

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
        $documentos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(2);

        $generos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(3);

        return view('personas.create', compact('documentos', 'generos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // Obtener los datos validados
                $data = $request->validated();

                $data['user_create_id'] = Auth::id();
                $data['user_edit_id'] = Auth::id();

                // Crear la persona de forma masiva
                $persona = Persona::create($data);

                // Crear el usuario asociado a la persona
                $this->crearUsuarioPersona($persona);
            });

            return redirect()->route('personas.index')->with('success', '¡Registro Exitoso!');
        } catch (\Exception $e) {
            Log::error('Error al registrar persona: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error al registrar persona.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {

        return view('personas.show', ['persona' => $persona]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        // llamar los tipos de documentos
        $documentos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(2);

        // llamar los generos
        $generos = Tema::with(['parametros' => function ($query) {
            $query->wherePivot('status', 1);
        }])->findOrFail(3);

        return view('personas.edit', ['persona' => $persona, 'documentos' => $documentos, 'generos' => $generos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        try {
            DB::transaction(function () use ($request, $persona) {
                $data = $request->validated();
                $persona->update($data);

                if ($persona->user) {
                    $persona->user->update([
                        'email' => $request->input('email'),
                    ]);
                }
            });

            return redirect()->route('personas.show', $persona->id)
                ->with('success', 'Información actualizada exitosamente');
        } catch (\Exception $e) {
            Log::error("Error al actualizar la persona (ID: {$persona->id}): " . $e->getMessage());
            return redirect()->back()->withErrors([
                'error' => 'Error al actualizar la información. Por favor, inténtelo de nuevo o comuníquese con el administrador del sistema.'
            ]);
        }
    }

    /**
     * Cambia el estado de una persona.
     *
     * Este método alterna el estado de una persona entre activo (1) e inactivo (0).
     * Si el estado actual es 1, se cambiará a 0 y viceversa.
     *
     * @param int $id El ID de la persona cuyo estado se va a cambiar.
     * @return \Illuminate\Http\RedirectResponse Redirección de vuelta con un mensaje de éxito o error.
     */
    public function cambiarEstadoPersona($id)
    {
        $persona = Persona::findOrFail($id);
        $user = User::where('persona_id', $persona->id)->first();

        try {
            $persona->update(['status' => !$persona->status]);
            $user->update(['status' => !$user->status]);

            return redirect()->back()->with('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al cambiar estado de la persona (ID: {$id}): " . $e->getMessage());

            return redirect()->back()->with('error', 'No se pudo actualizar el estado.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        try {
            DB::transaction(function () use ($persona) {
                $persona->delete();
            });

            return redirect()->route('personas.index')->with('success', 'Persona eliminada exitosamente');
        } catch (QueryException $e) {
            Log::error("Error al eliminar la persona (ID: {$persona->id}): " . $e->getMessage());

            return redirect()->back()->with('error', 'No se pudo eliminar la persona. Es posible que tenga un usuario asociado.');
        } catch (\Exception $e) {
            Log::error("Error inesperado al eliminar la persona (ID: {$persona->id}): " . $e->getMessage());

            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Crea un nuevo usuario basado en la información de una persona.
     *
     * @param Persona $persona La instancia de la persona de la cual se creará el usuario.
     * @return void
     */
    function crearUsuarioPersona(Persona $persona)
    {
        $user = User::create([
            'email' => $persona->email,
            'password' => Hash::make($persona->numero_documento),
            'persona_id' => $persona->id,
        ]);

        $user->assignRole('VISITANTE');
    }
}
