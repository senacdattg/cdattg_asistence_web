<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;

// Logout
Route::post('logout', [LogoutController::class, 'cerrarSesion'])->name('logout');