<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramaFormacionController;

// Rutas para ProgramaCaracterizacionController
Route::resource('programa', ProgramaFormacionController::class);
route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
    Route::get('/programa/index', [ProgramaFormacionController::class, 'index'])->name('programa.index');
    Route::get('/programa/create', [ProgramaFormacionController::class, 'create']);
    Route::post('/programa/store', [ProgramaFormacionController::class, 'store'])->name('programa.save');
    Route::get('/programa/search', [ProgramaFormacionController::class, 'search'])->name('programa.search');
    Route::get('/programa/{id}/edit', [ProgramaFormacionController::class, 'edit'])->name('programa.edit');
    Route::post('/programa/{id}', [ProgramaFormacionController::class, 'update'])->name('programa.update');
    Route::delete('/programa/{id}', [ProgramaFormacionController::class, 'destroy'])->name('programa.destroy');
});
