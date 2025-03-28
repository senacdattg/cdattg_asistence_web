<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParametroController;

Route::resource('parametro', ParametroController::class);

Route::middleware('can:EDITAR PARAMETRO')->group(function () {
    Route::put('/parametro/{parametro}/cambiar-estado', [ParametroController::class, 'cambiarEstado'])->name('parametro.cambiarEstado');
});
