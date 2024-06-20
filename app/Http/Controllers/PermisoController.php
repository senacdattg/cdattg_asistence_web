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
        //
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
