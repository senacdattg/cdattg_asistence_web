<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SedeController;

Route::resource('sede', SedeController::class);

Route::get('/cargarSedesByMunicipio/{municipio_id}', [SedeController::class, 'cargarSedesByMunicipio'])->name('sede.cargarSedesByMunicipio');
Route::get('/cargarSedesByRegional/{regional_id}', [SedeController::class, 'cargarSedesByRegional'])->name('sede.cargarSedesByRegional');

Route::middleware('can:EDITAR SEDE')->group(function () {
    Route::put('sedeUpdateStatus/{sede}', [SedeController::class, 'cambiarEstadoSede'])->name('sede.cambiarEstado');
});
