<?php

use App\Http\Controllers\Biogjgas\Public\HomeController;
use App\Http\Controllers\Biogjgas\Public\SemilleroController;
use Illuminate\Support\Facades\Route;

Route::prefix('investigacion')
    ->name('biogjgas.')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::prefix('semilleros')->name('semilleros.')->group(function () {
            Route::get('/', [SemilleroController::class, 'index'])->name('index');
            Route::get('/{semillero}', [SemilleroController::class, 'show'])->name('show');
        });
    });
