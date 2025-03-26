<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BloqueController;

Route::resource('bloque', BloqueController::class);

Route::middleware('can:EDITAR BLOQUE')->group(function () {
    Route::put('/bloque/cambiarEstado/{bloque}', [BloqueController::class, 'cambiarEstado'])->name('bloque.cambiarEstado');
});

Route::get('/cargarBloques/{sede_id}', [BloqueController::class, 'cargarBloques'])->name('bloque.cargarBloques');
