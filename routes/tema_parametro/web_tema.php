<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemaController;

Route::resource('tema', TemaController::class);

Route::middleware('can:EDITAR TEMA')->group(function () {
    Route::put('/tema/{tema}/cambiar-estado', [TemaController::class, 'cambiarEstado'])->name('tema.cambiarEstado');
    Route::put('/tema/{tema}/cambiar-estado-parametro/{parametro}', [TemaController::class, 'cambiarEstadoParametro'])->name('tema.cambiarEstadoParametro');
    Route::post('/temas/updatePatametrosTemas', [TemaController::class, 'updateParametrosTemas'])->name('tema.updateParametrosTemas');
    Route::delete('/tema/{tema}/eliminar-parametro/{parametro}', [TemaController::class, 'eliminarParametro'])->name('tema.eliminarParametro');
});
