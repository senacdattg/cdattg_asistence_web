<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipioController;

Route::get('/cargarMunicipios/{departamento_id}', [MunicipioController::class, 'cargarMunicipios'])->name('municipio.cargarMunicipios');
