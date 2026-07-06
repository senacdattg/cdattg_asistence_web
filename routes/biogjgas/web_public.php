use App\Http\Controllers\Biogjgas\Public\PodcastController;
use App\Http\Controllers\Biogjgas\Public\BoletinController;
use App\Http\Controllers\Biogjgas\Public\RevistaController;
<?php

use App\Http\Controllers\Biogjgas\Public\HomeController;
use App\Http\Controllers\Biogjgas\Public\PresentacionController;
use App\Http\Controllers\Biogjgas\Public\SemilleroController;
use Illuminate\Support\Facades\Route;

Route::prefix('investigacion')
    ->name('biogjgas.')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/presentacion', [PresentacionController::class, 'show'])->name('presentacion.show');
        Route::get('/revista', [RevistaController::class, 'index'])->name('revista.index');
        Route::get('/revista/{edicion}', [RevistaController::class, 'show'])->name('revista.show');

        Route::prefix('boletines')->name('boletines.')->group(function () {
            Route::get('/', [BoletinController::class, 'index'])->name('index');
            Route::get('/{boletin}', [BoletinController::class, 'show'])->name('show');
        });

        Route::prefix('semilleros')->name('semilleros.')->group(function () {
            Route::get('/', [SemilleroController::class, 'index'])->name('index');
            Route::get('/{semillero}', [SemilleroController::class, 'show'])->name('show');
                Route::prefix('podcast')->name('podcast.')->group(function () {
            Route::get('/', [PodcastController::class, 'index'])->name('index');
            Route::get('/{podcast}', [PodcastController::class, 'show'])->name('show');
        });

    });
    });