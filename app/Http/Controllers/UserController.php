<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function toggleStatus(User $user)
    {
        // Opcional: Evitar que un usuario modifique su propio estado
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'No puedes modificar tu propio estado.');
        }

        $nuevoStatus = $user->status === 1 ? 0 : 1;

        try {
            $user->update([
                'status'       => $nuevoStatus,
                'user_edit_id' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Estado actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado: ' . $e->getMessage());

            return redirect()->back()->with('error', 'No se pudo actualizar el estado');
        }
    }

    public function assignRoles(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'roles'   => 'nullable|array',
            'roles.*' => 'required|string|exists:roles,name',
        ]);

        try {
            $user = User::findOrFail($data['user_id']);
            $roles = $data['roles'] ?? [];
            $user->syncRoles($roles);

            return redirect()->back()->with('success', 'Roles asignados correctamente');
        } catch (\Exception $e) {
            Log::error('Error al asignar roles: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudieron asignar los roles');
        }
    }
}
