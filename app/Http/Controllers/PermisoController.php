<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $users = User::where('status', 1)->paginate(10);
        $search = $request->input('search');

        $users = User::whereHas('persona', function ($query) use ($search) {
            if ($search) {
                $query->where('primer_nombre', 'like', "%{$search}%")
                ->orWhere('segundo_nombre', 'like', "%{$search}%")
                ->orWhere('primer_apellido', 'like', "%{$search}%")
                ->orWhere('segundo_apellido', 'like', "%{$search}%")
                ->orWhere('numero_documento', 'like', "%{$search}%");
            }
        })->where('status', 1)->paginate(10);
        return view('permisos.index', ['users' => $users, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function showUserPermiso($user_id)
    {
        // @dd("vamos bien");
        // @dd($user_id);
        $user = User::find($user_id);
        $permisos = Permission::all();

        return view('permisos.show', compact('user', 'permisos'));
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $permisosUser = $request->input('permisos', []);
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $roles = $user->roles->pluck('name'); // Obtener los nombres de los roles del usuario
        $instructorNecesario = false;
        $instructorRole = false;

        foreach ($permisosUser as $permiso) {
            if ($permiso == 'TOMAR ASISTENCIA') {
                $instructorNecesario = true;
                break;
            }
        }

        if ($instructorNecesario) {
            if ($roles->contains('INSTRUCTOR')) {
                $instructorRole = true;
            }

            if ($instructorRole) {
                $user->syncPermissions($permisosUser);
                return redirect()->route('permiso.index')->with('success', 'Permisos asignados con éxito');
            } else {
                return redirect()->back()->with('error', 'Para asignar el permiso TOMAR ASISTENCIA el usuario debe tener el Rol de instructor');
            }
        } else {
            $user->syncPermissions($permisosUser);
            return redirect()->route('permiso.index')->with('success', 'Permisos asignados con éxito');
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
