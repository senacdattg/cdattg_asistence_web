<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermisoController;

Route::middleware('can:ASIGNAR PERMISOS')->group(function () {
    Route::resource('permiso', PermisoController::class);
    Route::get('/permiso/{user}', [PermisoController::class, 'showPermiso'])->name('permiso.show');
});