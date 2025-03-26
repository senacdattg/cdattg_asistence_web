<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmbienteController;

Route::resource('ambiente', AmbienteController::class);

Route::middleware('can:EDITAR AMBIENTE')->group(function () {
    Route::put('/ambiente/cambiarEstado/{ambiente}', [AmbienteController::class, 'cambiarEstado'])->name('ambiente.cambiarEstado');
});

Route::get('/cargarAmbientes/{piso_id}', [AmbienteController::class, 'cargarAmbientes'])->name('ambiente.cargarAmbientes');
