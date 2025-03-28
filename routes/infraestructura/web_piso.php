<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PisoController;

Route::resource('piso', PisoController::class);

Route::middleware('can:EDITAR PISO')->group(function () {
    Route::put('/piso/cambiarEstado/{piso}', [PisoController::class, 'cambiarEstado'])->name('piso.cambiarEstado');
});

Route::get('/cargarPisos/{bloque_id}', [PisoController::class, 'cargarPisos'])->name('piso.cargarPisos');
