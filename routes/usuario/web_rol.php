<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Activar/Inactivar usuarios
Route::patch('/user/{user}', [UserController::class, 'toggleStatus'])->name('user.toggleStatus');

// Asignar roles a usuarios
Route::patch('/user/{user}/assign-roles', [UserController::class, 'assignRoles'])->name('user.assignRoles');
