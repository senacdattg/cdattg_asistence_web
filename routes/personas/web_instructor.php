<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\PersonaController;

Route::resource('instructor', InstructorController::class);
Route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
    Route::get('instructor/createImportarCSV', [InstructorController::class, 'createImportarCSV'])->name('instructor.createImportarCSV');
    Route::post('storeImportarCSV', [InstructorController::class, 'storeImportarCSV'])->name('instructor.storeImportarCSV');
    Route::post('instructor/store', [InstructorController::class, 'store'])->name('instructor.store');
    Route::get('instructor/delete/{id}', [InstructorController::class, 'deleteWithoudUser'])->name('instructor.deleteWithoudUser');
});

Route::middleware('can:EDITAR INSTRUCTOR')->group(function () {
    Route::put('/persona/{persona}/cambiarEstado', [PersonaController::class, 'cambiarEstadoPersona'])->name('persona.cambiarEstadoPersona');
});