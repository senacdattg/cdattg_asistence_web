<?php

use App\Http\Controllers\EntradaSalidaController;
use App\Http\Controllers\FichaCaracterizacionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ParametroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('parametroApi', [ParametroController::class, 'apiIndex'])->name('api.parametro.index');
Route::get('fichaCaracterizacion/apiIndex', [FichaCaracterizacionController::class, 'apiIndex'])->name('api.fichaCaracterizacion.index');
Route::get('fichaCaracterizacion/apiShow', [FichaCaracterizacionController::class, 'apiShow']);
Route::post('fichaCaracterizacion/apiStore', [FichaCaracterizacionController::class, 'apiStore']);
// http://127.0.0.1:8000/api/fichaCaracterizacion/apiStore
Route::get('entradaSalida/apiIndex', [EntradaSalidaController::class, 'apiIndex']);
// http://127.0.0.1:8000/api/entradaSalida/apiIndex/1
Route::post('entradaSalida/apiStoreEntradaSalida', [EntradaSalidaController::class, 'apiStoreEntradaSalida']);
Route::post('entradaSalida/apiUpdateEntradaSalida', [EntradaSalidaController::class, 'apiUpdateEntradaSalida']);


route::post('authenticate', [LoginController::class, 'authenticate']);
route::post('logout', [LogoutController::class, 'logout']);

