<?php

namespace App\Policies;

use App\Models\EntradaSalida;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EntradaSalidaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EntradaSalida $entradaSalida): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EntradaSalida $entradaSalida): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EntradaSalida $entradaSalida): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EntradaSalida $entradaSalida): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EntradaSalida $entradaSalida): bool
    {
        //
    }
}
