<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionalController;

Route::resource('regional', RegionalController::class);

Route::middleware('can:EDITAR REGIONAL')->group(function () {
    Route::put('/regionalUpdateStatus/{regional}', [RegionalController::class, 'cambiarEstadoRegional'])->name('regional.cambiarEstado');
});
