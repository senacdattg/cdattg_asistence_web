<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;

Route::resource('personas', PersonaController::class);

Route::middleware('can:CAMBIAR ESTADO PERSONA')->group(function () {
    Route::put('/personas/{id}/cambiarEstadoPersona', [PersonaController::class, 'cambiarEstadoPersona'])->name('persona.cambiarEstadoPersona');
});
Route::middleware('can:EDITAR PERSONA')->group(function () {
    Route::get('/persona/{persona}/edit', [PersonaController::class, 'edit'])->name('persona.edit');
    Route::put('/persona/{persona}', [PersonaController::class, 'update'])->name('persona.update');
});
Route::middleware('can:ELIMINAR PERSONA')->group(function () {
    Route::delete('/persona/{persona}', [PersonaController::class, 'destroy'])->name('persona.destroy');
});