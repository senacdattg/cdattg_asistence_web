<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;

Route::get('/cargardepartamentos', [DepartamentoController::class, 'cargardepartamentos'])->name('departamento.cargardepartamentos');
