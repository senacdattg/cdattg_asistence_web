<?php

use App\Http\Controllers\Aitg\AitgAccesoInstructorController;
use App\Http\Controllers\Aitg\Banco\BancoInstructorController;
use App\Http\Controllers\Aitg\Banco\MotivoRechazoController;
use App\Http\Controllers\Aitg\Banco\TipoArchivoController;
use App\Http\Controllers\Aitg\Banco\ValidacionBancoController;
use Illuminate\Support\Facades\Route;

$canValidarDocumentoBanco = 'can:VALIDAR DOCUMENTO BANCO AITG';

Route::prefix('aitg')->name('aitg.')->group(function () use ($canValidarDocumentoBanco) {
    Route::get('/acceso-instructor', AitgAccesoInstructorController::class)->name('acceso-instructor');

    Route::prefix('banco-instructores')->name('banco-instructores.')->middleware('aitg.menu')->group(function () {
        Route::get('/', [BancoInstructorController::class, 'index'])->name('index');
        Route::get('/competencia/{competencia}', [BancoInstructorController::class, 'postulacion'])->name('postulacion');
        Route::post('/competencia/{competencia}/documentos', [BancoInstructorController::class, 'store'])->name('documentos.store');
        Route::post('/competencia/{competencia}/documentos-lote', [BancoInstructorController::class, 'storeLote'])->name('documentos.lote');
        Route::delete('/competencia/{competencia}/documentos/{postulacionArchivo}', [BancoInstructorController::class, 'destroyDocumento'])->name('documentos.destroy');
        Route::post('/competencia/{competencia}/reutilizar', [BancoInstructorController::class, 'reutilizar'])->name('reutilizar');
        Route::post('/competencia/{competencia}/enviar-revision', [BancoInstructorController::class, 'enviarRevision'])->name('enviar-revision');
        Route::delete('/competencia/{competencia}/postulacion', [BancoInstructorController::class, 'destroyPostulacion'])->name('postulacion.destroy');
        Route::get('/archivos/{archivo}/ver', [BancoInstructorController::class, 'verArchivo'])->name('archivos.ver');
        Route::get('/archivos/{archivo}/stream', [BancoInstructorController::class, 'streamArchivo'])->name('archivos.stream');
        Route::get('/archivos/{archivo}/descargar', [BancoInstructorController::class, 'downloadArchivo'])->name('archivos.download');
    });

    Route::prefix('validacion-banco')->name('validacion-banco.')->middleware('can:VER SOLICITUD BANCO AITG')->group(function () use ($canValidarDocumentoBanco) {
        Route::get('/', [ValidacionBancoController::class, 'index'])->name('index');
        Route::get('/{postulacion}', [ValidacionBancoController::class, 'show'])->name('show');
        Route::post('/archivos/{archivoPostulacion}/validar', [ValidacionBancoController::class, 'validar'])
            ->middleware($canValidarDocumentoBanco)
            ->name('archivos.validar');
        Route::post('/{postulacion}/validar-lote', [ValidacionBancoController::class, 'validarLote'])
            ->middleware($canValidarDocumentoBanco)
            ->name('validar-lote');
        Route::post('/{postulacion}/devolver', [ValidacionBancoController::class, 'devolver'])
            ->middleware($canValidarDocumentoBanco)
            ->name('devolver');
    });

    Route::resource('tipos-archivo', TipoArchivoController::class)
        ->parameters(['tipos-archivo' => 'tipoArchivo'])
        ->except(['show']);

    Route::resource('motivos-rechazo', MotivoRechazoController::class)
        ->parameters(['motivos-rechazo' => 'motivoRechazo'])
        ->except(['show']);
});
