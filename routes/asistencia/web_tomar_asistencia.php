<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaAprendicesController;

//Rutas para ver asistencia con permiso 'TOMAR ASISTENCIA'
Route::resource('asistenciaAprendiz', AsistenciaAprendicesController::class);
route::middleware('can:TOMAR ASISTENCIA')->group(function () {
    Route::get('/asistencia/index', [AsistenciaAprendicesController::class, 'index'])->name('asistencia.index');
    Route::post('/asistencia/ficha', [AsistenciaAprendicesController::class, 'getAttendanceByFicha'])->name('asistencia.getAttendanceByFicha');
    Route::post('/asistencia/ficha/fecha', [AsistenciaAprendicesController::class, 'getAttendanceByDateAndFicha'])->name('asistencia.getAttendanceByDateAndFicha');
    Route::post('/asistencia/ficha/documentos', [AsistenciaAprendicesController::class, 'getDocumentsByFicha'])->name('asistencia.getDocumentsByFicha');
    Route::post('/asistencia/documento', [AsistenciaAprendicesController::class, 'getAttendanceByDocument'])->name('asistencia.getAttendanceByDocument');
});
