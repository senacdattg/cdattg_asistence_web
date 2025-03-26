<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CentroFormacionController;

// Rutas para ver centros de formación (permiso: VER CENTROS DE FORMACION)
    Route::middleware('can:VER CENTROS DE FORMACION')->group(function () {
        Route::get('/centros', [CentroFormacionController::class, 'index'])->name('centros.index');
        Route::get('/centros/{centro}', [CentroFormacionController::class, 'show'])->name('centros.show');
    });

    // Rutas para crear centros de formación (permiso: CREAR CENTRO DE FORMACION)
    Route::middleware('can:CREAR CENTRO DE FORMACION')->group(function () {
        Route::get('/centros/create', [CentroFormacionController::class, 'create'])->name('centros.create');
        Route::post('/centros', [CentroFormacionController::class, 'store'])->name('centros.store');
    });

    // Rutas para editar centros de formación (permiso: EDITAR CENTRO DE FORMACION)
    Route::middleware('can:EDITAR CENTRO DE FORMACION')->group(function () {
        Route::get('/centros/{centro}/edit', [CentroFormacionController::class, 'edit'])->name('centros.edit');
        Route::put('/centros/{centro}', [CentroFormacionController::class, 'update'])->name('centros.update');
    });

    // Rutas para eliminar centros de formación (permiso: ELIMINAR CENTRO DE FORMACION)
    Route::middleware('can:ELIMINAR CENTRO DE FORMACION')->group(function () {
        Route::delete('/centros/{centro}', [CentroFormacionController::class, 'destroy'])->name('centros.destroy');
    });