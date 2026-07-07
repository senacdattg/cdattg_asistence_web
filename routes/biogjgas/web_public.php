<?php

use App\Http\Controllers\Biogjgas\Public\ActividadController;
use App\Http\Controllers\Biogjgas\Public\BoletinController;
use App\Http\Controllers\Biogjgas\Public\ConvocatoriaController;
use App\Http\Controllers\Biogjgas\Public\HomeController;
use App\Http\Controllers\Biogjgas\Public\PodcastController;
use App\Http\Controllers\Biogjgas\Public\PresentacionController;
use App\Http\Controllers\Biogjgas\Public\RevistaController;
use App\Http\Controllers\Biogjgas\Public\SemilleroController;
use Illuminate\Support\Facades\Route;

Route::prefix('investigacion')
    ->name('biogjgas.')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/presentacion', [PresentacionController::class, 'show'])->name('presentacion.show');

        Route::prefix('semilleros')->name('semilleros.')->group(function () {
            Route::get('/', [SemilleroController::class, 'index'])->name('index');
            Route::get('/{semillero}', [SemilleroController::class, 'show'])->name('show');
        });

        Route::get('/revista', [RevistaController::class, 'index'])->name('revista.index');
        Route::get('/revista/{edicion}', [RevistaController::class, 'show'])->name('revista.show');

        Route::prefix('boletines')->name('boletines.')->group(function () {
            Route::get('/', [BoletinController::class, 'index'])->name('index');
            Route::get('/{boletin}', [BoletinController::class, 'show'])->name('show');
        });

        Route::prefix('podcast')->name('podcast.')->group(function () {
            Route::get('/', [PodcastController::class, 'index'])->name('index');
            Route::get('/{podcast}', [PodcastController::class, 'show'])->name('show');
        });

        Route::prefix('convocatorias')->name('convocatorias.')->group(function () {
            Route::get('/', [ConvocatoriaController::class, 'index'])->name('index');
            Route::get('/{convocatoria}', [ConvocatoriaController::class, 'show'])->name('show');
        });

        Route::prefix('actividades')->name('actividades.')->group(function () {
            Route::get('/', [ActividadController::class, 'index'])->name('index');
            Route::get('/{actividad}', [ActividadController::class, 'show'])->name('show');
        });
    });
