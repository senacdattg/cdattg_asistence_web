<?php

use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\BloqueController;
use App\Http\Controllers\CaracterizacionController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EntradaSalidaController;
use App\Http\Controllers\FichaCaracterizacionController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\SedeController;
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
Route::middleware('auth:sanctum')->group(function(){

    Route::get('parametroApi', [ParametroController::class, 'apiIndex'])->name('api.parametro.index');
    Route::get('apiGetTipoDocumentos', [ParametroController::class, 'apiGetTipoDocumentos']);
    Route::get('apiGetGeneros', [ParametroController::class, 'apiGetGeneros']);
    Route::get('fichaCaracterizacion/apiIndex', [FichaCaracterizacionController::class, 'apiIndex']);
    Route::get('fichaCaracterizacion/apiShow', [FichaCaracterizacionController::class, 'apiShow']);
    Route::post('fichaCaracterizacion/apiStore', [FichaCaracterizacionController::class, 'apiStore']);

    //http://127.0.0.1:8000/api/caracterizacion/byInstructor
    Route::get('caracterizacion/byInstructor/{id}', [CaracterizacionController::class, 'CaracterizacionByInstructor']);

    // http://127.0.0.1:8000/api/fichaCaracterizacion/apiStore
    Route::get('entradaSalida/apiIndex', [EntradaSalidaController::class, 'apiIndex']);
    Route::post('entradaSalida/apiListarEntradaSalida', [EntradaSalidaController::class, 'apiListarEntradaSalida']);
    // http://127.0.0.1:8000/api/entradaSalida/apiIndex/1
    Route::post('entradaSalida/apiStoreEntradaSalida', [EntradaSalidaController::class, 'apiStoreEntradaSalida']);
    Route::post('entradaSalida/apiUpdateEntradaSalida', [EntradaSalidaController::class, 'apiUpdateEntradaSalida']);
    // ruta para actualizar perfil de instructor
    Route::post('instructor/apiUpdate', [InstructorController::class, 'apiUpdate']);

    // select dinamico
    Route::get('apiCargarDepartamentos', [DepartamentoController::class, 'apiCargarDepartamentos']);
    Route::get('apiCargarMunicipios', [MunicipioController::class, 'apiCargarMunicipios']);
    Route::get('apiCargarSedes', [SedeController::class, 'apiCargarSedes']);
    Route::get('apiCargarBloques', [BloqueController::class, 'apiCargarBloques']);
    Route::get('apiCargarPisos', [PisoController::class, 'apiCargarPisos']);
    Route::get('apiCargarAmbientes', [AmbienteController::class, 'apiCargarAmbientes']);
});


route::post('authenticate', [LoginController::class, 'authenticate']);
route::post('logout', [LogoutController::class, 'logout']);

