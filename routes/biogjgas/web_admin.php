use App\Http\Controllers\Biogjgas\Admin\RevistaAdminController;
<?php

use App\Http\Controllers\Biogjgas\Admin\BannerAdminController;
use App\Http\Controllers\Biogjgas\Admin\DashboardController;
use App\Http\Controllers\Biogjgas\Admin\PresentacionAdminController;
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
    });