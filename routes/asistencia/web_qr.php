<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenceQrController;


//TOMA DE ASISTENCIA CON QR WEB
Route::resource('asistenciaAprendiz', AsistenceQrController::class);
route::middleware('can:TOMAR ASISTENCIA')->group(function () {
    Route::get('asistence/web', [AsistenceQrController::class, 'index'])->name('asistence.web');
    Route::post('/asistence/store', [AsistenceQrController::class, 'store'])->name('asistence.store');
    Route::get('asistece/caracterSelected/{id}', [AsistenceQrController::class, 'caracterSelected'])->name('asistence.caracterSelected');
    Route::get('/asistence/web/list/{ficha}/{jornada}', [AsistenceQrController::class, 'getAsistenceWebList'])->name('asistence.weblist');
    Route::get('/asistence/exit/{identificacion}/{ingreso}/{fecha}', [AsistenceQrController::class, 'redirectAprenticeExit'])->name('asistence.webexit');
    Route::get('/asistence/entrance/{identificacion}/{ingreso}/{fecha}', [AsistenceQrController::class, 'redirectAprenticeEntrance'])->name('asistence.webentrance');
    Route::get('/asistence/exitFormation/{caracterizacion_id}', [AsistenceQrController::class, 'exitFormationAsistenceWeb'])->name('asistence.exitFormation');
    Route::post('/asistence/setNewExit', [AsistenceQrController::class, 'setNewExitAsistenceWeb'])->name('asistence.setNewExit');
    Route::post('/asistence/setNewEntrance', [AsistenceQrController::class, 'setNewEntranceAsistenceWeb'])->name('asistence.setNewEntrance');
});
