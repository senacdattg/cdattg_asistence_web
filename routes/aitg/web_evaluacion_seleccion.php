<?php

use App\Http\Controllers\Aitg\Evaluacion\EvaluacionController;
use App\Http\Controllers\Aitg\Seleccion\SeleccionController;
use Illuminate\Support\Facades\Route;

$canEvaluarPostulacion = 'can:EVALUAR POSTULACION AITG';

Route::prefix('aitg')->name('aitg.')->group(function () use ($canEvaluarPostulacion) {
    Route::prefix('evaluacion')->name('evaluacion.')->middleware('can:VER EVALUACION AITG')->group(function () use ($canEvaluarPostulacion) {
        Route::get('/', [EvaluacionController::class, 'index'])->name('index');
        Route::get('/convocatorias/{convocatoria}/postulaciones', [EvaluacionController::class, 'postulaciones'])->name('postulaciones');
        Route::post('/postulaciones/{postulacion}/iniciar', [EvaluacionController::class, 'iniciar'])
            ->middleware($canEvaluarPostulacion)
            ->name('iniciar');
        Route::get('/{evaluacion}', [EvaluacionController::class, 'show'])->name('show');
        Route::post('/{evaluacion}/guardar', [EvaluacionController::class, 'guardar'])
            ->middleware($canEvaluarPostulacion)
            ->name('guardar');
        Route::post('/{evaluacion}/finalizar', [EvaluacionController::class, 'finalizar'])
            ->middleware($canEvaluarPostulacion)
            ->name('finalizar');
    });

    Route::prefix('seleccion')->name('seleccion.')->middleware('can:VER SELECCION AITG')->group(function () {
        Route::get('/', [SeleccionController::class, 'index'])->name('index');
        Route::get('/convocatorias/{convocatoria}', [SeleccionController::class, 'candidatos'])->name('candidatos');
        Route::post('/convocatorias/{convocatoria}/confirmar', [SeleccionController::class, 'confirmar'])
            ->middleware('can:SELECCIONAR INSTRUCTOR AITG')
            ->name('confirmar');

        Route::get('/convocatorias/{convocatoria}/reporte', [SeleccionController::class, 'reporteGeneral'])
            ->name('reporte.general');
        Route::get('/convocatorias/{convocatoria}/reporte/pdf', [SeleccionController::class, 'reporteGeneralPdf'])
            ->name('reporte.general.pdf');
        Route::get('/convocatorias/{convocatoria}/reporte/excel', [SeleccionController::class, 'reporteGeneralExcel'])
            ->name('reporte.general.excel');
        Route::get('/convocatorias/{convocatoria}/reporte/candidatos/{postulacion}', [SeleccionController::class, 'reporteIndividual'])
            ->name('reporte.individual');
        Route::get('/convocatorias/{convocatoria}/reporte/candidatos/{postulacion}/pdf', [SeleccionController::class, 'reporteIndividualPdf'])
            ->name('reporte.individual.pdf');
    });
});
