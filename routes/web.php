<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web de la aplicación.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas públicas
foreach (glob(routes_path('autenticacion/public') . '/*.php') as $routeFile) {
    include_once $routeFile;
}

// Rutas protegidas
Route::middleware('auth')->group(function () {
    $protectedFolders = [
        'autenticacion/private',
        'usuario',
        'asistencia',
        'caracterizacion',
        'entrada_salida',
        'infraestructura',
        'jornada_carnet',
        'personas',
        'tema_parametro',
        'ubicacion',
    ];

    foreach ($protectedFolders as $folder) {
        foreach (glob(routes_path($folder) . '/*.php') as $routeFile) {
            include_once $routeFile;
        }
    }
});
