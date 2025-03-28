<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntradaSalidaController;

Route::resource('entradaSalida', EntradaSalidaController::class);

// Cargar datos vía middleware (por ejemplo, para frontend o API)
Route::get('cargarDatos', [EntradaSalidaController::class, 'cargarDatos'])
    ->name('entradaSalida.cargarDatos')
    ->middleware('cros');

// Crear entrada/salida directa
Route::get('crearEntradaSalida/{ficha_id}/{aprendiz}/{ambiente_id}/{descripcion}', 
    [EntradaSalidaController::class, 'storeEntradaSalida']
)->name('entradaSalida.crearEntradaSalida');

Route::get('editarEntradaSalida/{aprendiz}/{ambiente_id}/{descripcion}', 
    [EntradaSalidaController::class, 'updateEntradaSalida']
)->name('entradaSalida.editarEntradaSalida');

// Registrar ingreso/salida
Route::post('/registros', [EntradaSalidaController::class, 'registros'])->name('entradaSalida.registros');
Route::post('updateSalida', [EntradaSalidaController::class, 'updateSalida'])->name('entradaSalida.updateSalida');

// Generar CSV de fichas
Route::get('generarCSV/{ficha}', [EntradaSalidaController::class, 'generarCSV'])->name('entradaSalida.generarCSV');
