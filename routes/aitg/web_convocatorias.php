<?php

use App\Http\Controllers\Aitg\Convocatoria\ConvocatoriaController;
use App\Http\Controllers\Aitg\Convocatoria\ConvocatoriaPublicaController;
use Illuminate\Support\Facades\Route;

Route::prefix('aitg')->name('aitg.')->group(function () {
    Route::prefix('convocatorias')->name('convocatorias.')->group(function () {
        Route::get('/planes-por-competencia', [ConvocatoriaController::class, 'planesPorCompetencia'])
            ->name('planes-por-competencia');

        Route::prefix('publicas')->name('publicas.')->middleware('aitg.menu')->group(function () {
            Route::get('/', [ConvocatoriaPublicaController::class, 'index'])->name('index');

            Route::prefix('{convocatoria}')->group(function () {
                Route::get('/', [ConvocatoriaPublicaController::class, 'show'])->name('show');
                Route::delete('/postulacion', [ConvocatoriaPublicaController::class, 'destroyPostulacion'])->name('postulacion.destroy');
                Route::get('/postular', [ConvocatoriaPublicaController::class, 'postular'])->name('postular');
                Route::post('/perfil', [ConvocatoriaPublicaController::class, 'seleccionarPerfil'])->name('perfil');
                Route::post('/documentos', [ConvocatoriaPublicaController::class, 'storeDocumentos'])->name('documentos.store');
                Route::post('/documentos-lote', [ConvocatoriaPublicaController::class, 'storeDocumentosLote'])->name('documentos.lote');
                Route::delete('/documentos/{postulacionArchivo}', [ConvocatoriaPublicaController::class, 'destroyDocumento'])->name('documentos.destroy');
                Route::post('/reutilizar', [ConvocatoriaPublicaController::class, 'reutilizar'])->name('reutilizar');
                Route::post('/enviar', [ConvocatoriaPublicaController::class, 'enviarPostulacion'])->name('enviar');
                Route::get('/formalizacion', [ConvocatoriaPublicaController::class, 'formalizacion'])->name('formalizacion');
                Route::post('/formalizacion/enviar', [ConvocatoriaPublicaController::class, 'enviarFormalizacion'])->name('formalizacion.enviar');
            });
        });

        Route::get('/', [ConvocatoriaController::class, 'index'])->name('index');
        Route::get('/create', [ConvocatoriaController::class, 'create'])->name('create');
        Route::post('/', [ConvocatoriaController::class, 'store'])->name('store');

        Route::prefix('{convocatoria}')->group(function () {
            Route::get('/postulaciones', [ConvocatoriaController::class, 'postulaciones'])->name('postulaciones');
            Route::get('/edit', [ConvocatoriaController::class, 'edit'])->name('edit');
            Route::put('/', [ConvocatoriaController::class, 'update'])->name('update');
            Route::delete('/', [ConvocatoriaController::class, 'destroy'])->name('destroy');
            Route::get('/', [ConvocatoriaController::class, 'show'])->name('show');
        });
    });
});
