<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarnetController;

Route::resource('carnet', CarnetController::class);

Route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
    Route::get('/', [CarnetController::class, 'index'])->name('carnet.index');
    Route::post('/process', [CarnetController::class, 'processCsv'])->name('carnet.process');
    Route::post('/carnet/send-all', [CarnetController::class, 'sendAll'])->name('carnet.sendAll');
});
