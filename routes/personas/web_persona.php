<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;

Route::resource('persona', PersonaController::class);
Route::middleware('can:EDITAR INSTRUCTOR')->group(function () {
    Route::put('/persona/{persona}/cambiarEstado', [PersonaController::class, 'cambiarEstadoPersona'])->name('persona.cambiarEstadoUser');
});