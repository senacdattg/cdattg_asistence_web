<?php

use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\AsistenceQr;
use App\Http\Controllers\AsistenceQrController;
use App\Http\Controllers\AsistenciaAprendicesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BloqueController;
use App\Http\Controllers\CaracterizacionController;
use App\Http\Controllers\CarnetController;
use App\Http\Controllers\CentroFormacionController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EntradaSalidaController;
use App\Http\Controllers\FichaCaracterizacionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\JornadaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\SedeController;
use App\Models\Ambiente;
use App\Models\Bloque;
use App\Models\EntradaSalida;
use App\Models\FichaCaracterizacion;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ProgramaCaracterizacionController;
use App\Http\Controllers\ProgramaFormacionController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CorsMiddleware;
use App\Models\CaracterizacionPrograma;
use App\Models\CentroFormacion;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/apis', function () {
    return view('apis');
});
// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware('auth')->group(function () {

    Route::resource('home', HomeController::class);
    // Rutas para persona
    Route::resource('persona', PersonaController::class);
    route::middleware('can:CAMBIAR ESTADO PERSONA')->group(function () {
        Route::put('/persona/{persona}/cambiarEstadoPersona', [PersonaController::class, 'cambiarEstadoPersona'])->name('persona.cambiarEstadoPersona');
    });

    // Rutas para AsistenciaAprendizController
    Route::resource('asistenciaAprendiz', AsistenciaAprendicesController::class);
    route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
        Route::get('/asistencia/index', [AsistenciaAprendicesController::class, 'index'])->name('asistencia.index');
        Route::post('/asistencia/ficha', [AsistenciaAprendicesController::class, 'getAttendanceByFicha'])->name('asistencia.getAttendanceByFicha');
        Route::post('/asistencia/ficha/fecha', [AsistenciaAprendicesController::class, 'getAttendanceByDateAndFicha'])->name('asistencia.getAttendanceByDateAndFicha');
        Route::post('/asistencia/ficha/documentos', [AsistenciaAprendicesController::class, 'getDocumentsByFicha'])->name('asistencia.getDocumentsByFicha');
        Route::post('/asistencia/documento', [AsistenciaAprendicesController::class, 'getAttendanceByDocument'])->name('asistencia.getAttendanceByDocument');
    });

    //Rutas para ver asistencia con permiso 'TOMAR ASISTENCIA'
    Route::resource('asistenciaAprendiz', AsistenciaAprendicesController::class);
    route::middleware('can:TOMAR ASISTENCIA')->group(function () {
        Route::get('/asistencia/index', [AsistenciaAprendicesController::class, 'index'])->name('asistencia.index');
        Route::post('/asistencia/ficha', [AsistenciaAprendicesController::class, 'getAttendanceByFicha'])->name('asistencia.getAttendanceByFicha');
        Route::post('/asistencia/ficha/fecha', [AsistenciaAprendicesController::class, 'getAttendanceByDateAndFicha'])->name('asistencia.getAttendanceByDateAndFicha');
        Route::post('/asistencia/ficha/documentos', [AsistenciaAprendicesController::class, 'getDocumentsByFicha'])->name('asistencia.getDocumentsByFicha');
        Route::post('/asistencia/documento', [AsistenciaAprendicesController::class, 'getAttendanceByDocument'])->name('asistencia.getAttendanceByDocument');
    });

    //TOMA DE ASISTENCIA CON QR WEB
    Route::resource('asistenciaAprendiz', AsistenceQrController::class);
    route::middleware('can:TOMAR ASISTENCIA')->group(function () {
        Route::get('asistence/web', [AsistenceQrController::class, 'index'])->name('asistence.web');
        Route::post('/asistence/store', [AsistenceQrController::class, 'store'])->name('asistence.store');
        Route::get('asistece/caracterSelected/{id}', [AsistenceQrController::class, 'caracterSelected'])->name('asistence.caracterSelected');
        Route::get('/asistence/web/list/{ficha}/{jornada}', [AsistenceQrController::class, 'getAsistenceWebList'])->name('asistence.weblist');
        Route::get('/asistence/exit/{identificacion}/{ingreso}/{fecha}', [AsistenceQrController::class, 'redirectAprenticeExit'])->name('asistence.webexit');
        Route::get('/asistence/entrance/{identificacion}/{ingreso}/{fecha}', [AsistenceQrController::class, 'redirectAprenticeEntrance'])->name('asistence.webentrance');
        Route::get('/asistence/exitFormation/{caracterizacion_id}', [AsistenceQrController::class, 'exitFormationAsistenceWeb'])->name('asistence.exitFormation');
        Route::post('/asistence/setNewExit', [AsistenceQrController::class, 'setNewExitAsistenceWeb'])->name('asistence.setNewExit');
        Route::post('/asistence/setNewEntrance', [AsistenceQrController::class, 'setNewEntranceAsistenceWeb'])->name('asistence.setNewEntrance');
    });

    //rutas para ProgramaCaractizacionControllerasistence.webexit
    // Rutas para ProgramaCaracterizacionController
    Route::resource('programaFormacion', ProgramaFormacionController::class);
    route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
        Route::get('/programa/index', [ProgramaFormacionController::class, 'index'])->name('programa.index');
        Route::get('/programa/create', [ProgramaFormacionController::class, 'create']);
        Route::post('/programa/store', [ProgramaFormacionController::class, 'store'])->name('programa.save');
        Route::get('/programa/search', [ProgramaFormacionController::class, 'search'])->name('programa.search');
        Route::get('/programa/{id}/edit', [ProgramaFormacionController::class, 'edit'])->name('programa.edit');
        Route::post('/programa/{id}', [ProgramaFormacionController::class, 'update'])->name('programa.update');
        Route::delete('/programa/{id}', [ProgramaFormacionController::class, 'destroy'])->name('programa.destroy');
    });

    /** RUTAS PARA JornadaController */

    Route::resource('jornada', JornadaController::class);
    route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
        Route::get('/jornada/{id}/destroy', [JornadaController::class, 'destroy'])->name('jornada.destroy');
        Route::post('jornada/{id}/update', [JornadaController::class, 'update'])->name('jornada.update');
    });

    // Rutas para FichasCaracterizacionController
    Route::resource('fichaCaracterizacion', FichaCaracterizacionController::class);
    route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
        Route::get('/ficha/index', [FichaCaracterizacionController::class, 'index'])->name('ficha.index');
        Route::get('/ficha/search', [FichaCaracterizacionController::class, 'search'])->name('ficha.search');
        Route::get('/fichaCaracterizacion/create', [FichaCaracterizacionController::class, 'create'])->name('fichaCaracterizacion.create');
        Route::post('/fichaCaracterizacion/store', [FichaCaracterizacionController::class, 'store'])->name('fichaCaracterizacion.store');
        Route::get('/fichaCaracterizacion/{id}/edit', [FichaCaracterizacionController::class, 'edit'])->name('ficha.edit');
        Route::post('/fichaCaracterizacion/{id}', [FichaCaracterizacionController::class, 'update'])->name('ficha.update');
        Route::delete('/fichaCaracterizacion/{id}', [FichaCaracterizacionController::class, 'destroy'])->name('ficha.destroy');
    });

    // Rutas para CaracterizacionController
    Route::resource('caracterizacion', CaracterizacionController::class);
    route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
        Route::get('/caracter/index', [CaracterizacionController::class, 'index'])->name('caracter.index');
        Route::get('/caracterizacion/search', [CaracterizacionController::class, 'show'])->name('caracterizacion.search');
        Route::get('/caracterizacion/create', [CaracterizacionController::class, 'create'])->name('caracterizacion.create');
        Route::post('/caracterizacion/ficha', [CaracterizacionController::class, 'getCaracterByFicha'])->name('caracterizacion.ficha');
        Route::post('/caracterizacion/store', [CaracterizacionController::class, 'store'])->name('caracterizacion.store');
        Route::get('/caracterizacion/{id}/edit', [CaracterizacionController::class, 'edit'])->name('caracterizacion.edit');
        Route::get('/caracterizacion/destroy/{id}', [CaracterizacionController::class, 'destroy'])->name('caracterizacion.destroy');
        Route::post('/caracterizacion/{id}', [CaracterizacionController::class, 'update'])->name('caracterizacion.update');
        //Route::get('/caracterizacion/{id}/show', [CaracterizacionController::class, 'show'])->name('caracterizacion.show');
    });

    Route::resource('carnet', CarnetController::class);
    route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
        Route::get('/', [CarnetController::class, 'index'])->name('carnet.index');
        Route::post('/process', [CarnetController::class, 'processCsv'])->name('carnet.process');
        Route::post('/carnet/send-all', [CarnetController::class, 'sendAll'])->name('carnet.sendAll');
    });


    //Rutas para instructores
    Route::resource('instructor', InstructorController::class);
    route::middleware('can:VER PROGRAMA DE CARACTERIZACION')->group(function () {
        route::get('createImportarCSV', [InstructorController::class, 'createImportarCSV'])->name('instructor.createImportarCSV');
        route::post('storeImportarCSV', [InstructorController::class, 'storeImportarCSV'])->name('instructor.storeImportarCSV');
        Route::post('instructor/store', [InstructorController::class, 'store'])->name('instructor.store');
        Route::get('instructor/delete/{id}', [InstructorController::class, 'deleteWithoudUser'])->name('instructor.deleteWithoudUser');
    });

    // Rutas para entrada y salida
    Route::resource('entradaSalida', EntradaSalidaController::class);
    Route::get('cargarDatos', [EntradaSalidaController::class, 'cargarDatos'])->name('entradaSalida.cargarDatos')->middleware('cros');
    Route::get('crearEntradaSalida/{ficha_id}/{aprendiz}/{ambiente_id}/{descripcion}', [EntradaSalidaController::class, 'storeEntradaSalida'])->name('entradaSalida.crearEntradaSalida');
    Route::get('editarEntradaSalida/{aprendiz}/{ambiente_id}/{descripcion}', [EntradaSalidaController::class, 'updateEntradaSalida'])->name('entradaSalida.editarEntradaSalida');
    Route::post('/registros', [EntradaSalidaController::class, 'registros'])->name('entradaSalida.registros');
    Route::post('updateSalida', [EntradaSalidaController::class, 'updateSalida'])->name('entradaSalida.updateSalida');
    Route::get('generarCSV/{ficha}', [EntradaSalidaController::class, 'generarCSV'])->name('entradaSalida.generarCSV');

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
        Route::put('/centros/{centro}/cambiarEstado', [CentroFormacion::class, 'cambiarEstado'])->name('centro.cambiarEstado');
    });

    // Rutas para eliminar centros de formación (permiso: ELIMINAR CENTRO DE FORMACION)
    Route::middleware('can:ELIMINAR CENTRO DE FORMACION')->group(function () {
        Route::delete('/centros/{centro}', [CentroFormacionController::class, 'destroy'])->name('centros.destroy');
    });

    // Ruta para sedes
    Route::resource('sede', SedeController::class);
    Route::get('/cargarSedesByMunicipio/{municipio_id}', [SedeController::class, 'cargarSedesByMunicipio'])->name('sede.cargarSedesByMunicipio');
    Route::get('/cargarSedesByRegional/{regional_id}', [SedeController::class, 'cargarSedesByRegional'])->name('sede.cargarSedesByRegional');
    route::middleware('can:EDITAR SEDE')->group(function () {
        Route::put('sedeUpdateStatus/{sede}', [SedeController::class, 'cambiarEstadoSede'])->name('sede.cambiarEstado');
    });

    // Ruta para bloques
    Route::resource('bloque', BloqueController::class);
    route::middleware('can:EDITAR BLOQUE')->group(function () {
        route::put('/bloque/cambiarEstado/{bloque}', [BloqueController::class, 'cambiarEstado'])->name('bloque.cambiarEstado');
    });

    Route::get('/cargarBloques/{sede_id}', [BloqueController::class, 'cargarBloques'])->name('bloque.cargarBloques');

    // Ruta para los pisos
    Route::resource('piso', PisoController::class);
    route::middleware('can:EDITAR PISO')->group(function () {
        route::put('/piso/cambiarEstado/{piso}', [PisoController::class, 'cambiarEstado'])->name('piso.cambiarEstado');
    });
    Route::get('/cargarPisos/{bloque_id}', [PisoController::class, 'cargarPisos'])->name('piso.cargarPisos');

    // Ruta para ambientes
    Route::resource('ambiente', AmbienteController::class);
    route::middleware('can:EDITAR AMBIENTE')->group(function () {
        Route::put('/ambiente/cambiarEstado/{ambiente}', [AmbienteController::class, 'cambiarEstado'])->name('ambiente.cambiarEstado');
    });
    Route::get('/cargarAmbientes/{piso_id}', [AmbienteController::class, 'cargarAmbientes'])->name('ambiente.cargarAmbientes');



    // rutas para parametros
    Route::resource('parametro', ParametroController::class);
    route::middleware('can:EDITAR PARAMETRO')->group(function () {

        Route::put('/parametro/{parametro}/cambiar-estado', [ParametroController::class, 'cambiarEstado'])->name('parametro.cambiarEstado');
    });


    // rutas para temas
    Route::resource('tema', TemaController::class);
    route::middleware('can:EDITAR TEMA')->group(function () {

        Route::put('/tema/{tema}/cambiar-estado', [TemaController::class, 'cambiarEstado'])->name('tema.cambiarEstado');
        Route::put('/tema/{tema}/cambiar-estado-parametro/{parametro}', [TemaController::class, 'cambiarEstadoParametro'])->name('tema.cambiarEstadoParametro');
        Route::post('/temas/updatePatametrosTemas', [TemaController::class, 'updateParametrosTemas'])->name('tema.updateParametrosTemas');
        Route::delete('/tema/{tema}/eliminar-parametro/{parametro}', [TemaController::class, 'eliminarParametro'])->name('tema.eliminarParametro');
    });
    // rutas para las regionales
    Route::resource('regional', RegionalController::class);
    route::middleware('can:EDITAR REGIONAL')->group(function () {
        Route::put('regionalUpdateStatus/{regional}', [RegionalController::class, 'cambiarEstadoRegional'])->name('regional.cambiarEstado');
    });
    // rutas para los permisos
    route::middleware('can:ASIGNAR PERMISOS')->group(function () {

        route::resource('permiso', PermisoController::class);
        route::get('/permiso/{user}', [PermisoController::class, 'showPermiso'])->name('permiso.show');
    });

    Route::get('/logout', [LogoutController::class, 'cerrarSesion'])->name('logout');

    // rutas para departamentos

    Route::get('/cargardepartamentos', [DepartamentoController::class, 'cargardepartamentos'])->name('departamento.cargardepartamentos');
    Route::get('/cargarMunicipios/{departamento_id}', [MunicipioController::class, 'cargarMunicipios'])->name('municipio.cargarMunicipios');

    // Ruta para activar/inactivar usuario
    Route::patch('/user/{user}', [UserController::class, 'toggleStatus'])->name('user.toggleStatus');

    // Ruta para asignar roles (solo SUPER ADMINISTRADOR)
    Route::patch('/user/{user}/assign-roles', [UserController::class, 'assignRoles'])->name('user.assignRoles');
});

// rutas del controlador register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/registro', 'mostrarFormulario')->name('registro');
    Route::post('/registrarme', 'create')->name('registrarme');
});
// rutas del controlador login
Route::resource('login', LoginController::class);
Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'verificarLogin')->name('verificarLogin');
    // Route::get('/login','mostrarLogin')->name('login');
    Route::post('/iniciarSesion', 'iniciarSesion')->name('iniciarSesion');
});
