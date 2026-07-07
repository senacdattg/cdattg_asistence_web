<?php

use App\Http\Controllers\Biogjgas\Admin\ActividadAdminController;
use App\Http\Controllers\Biogjgas\Admin\BannerAdminController;
use App\Http\Controllers\Biogjgas\Admin\BoletinAdminController;
use App\Http\Controllers\Biogjgas\Admin\ConvocatoriaAdminController;
use App\Http\Controllers\Biogjgas\Admin\DashboardController;
use App\Http\Controllers\Biogjgas\Admin\IntegranteAdminController;
use App\Http\Controllers\Biogjgas\Admin\LineaAdminController;
use App\Http\Controllers\Biogjgas\Admin\PodcastAdminController;
use App\Http\Controllers\Biogjgas\Admin\PresentacionAdminController;
use App\Http\Controllers\Biogjgas\Admin\ProyectoAdminController;
use App\Http\Controllers\Biogjgas\Admin\RevistaAdminController;
use App\Http\Controllers\Biogjgas\Admin\SemilleroAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('biogjgas/admin')
    ->name('biogjgas.admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('semilleros', SemilleroAdminController::class)->except(['show']);
        Route::resource('banners', BannerAdminController::class)->except(['show']);
        Route::get('presentacion', [PresentacionAdminController::class, 'edit'])->name('presentacion.edit');
        Route::put('presentacion', [PresentacionAdminController::class, 'update'])->name('presentacion.update');
        Route::resource('revista', RevistaAdminController::class)->except(['show']);
        Route::resource('boletin', BoletinAdminController::class)->except(['show']);
        Route::resource('podcast', PodcastAdminController::class)->except(['show']);
        Route::resource('convocatoria', ConvocatoriaAdminController::class)->except(['show']);
        Route::resource('actividad', ActividadAdminController::class)->except(['show']);
        Route::resource('linea', LineaAdminController::class)->except(['show']);
        Route::resource('integrante', IntegranteAdminController::class)->except(['show']);
        Route::resource('proyecto', ProyectoAdminController::class)->except(['show']);
    });
