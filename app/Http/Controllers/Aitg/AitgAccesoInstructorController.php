<?php

namespace App\Http\Controllers\Aitg;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Aitg\AitgMenuAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Desbloquea acceso AITG (sesión) al clic en «Conviértete en Instructor SENA». */
class AitgAccesoInstructorController extends Controller
{
    public function __construct(
        private readonly AitgMenuAccess $menuAccess
    ) {
        $this->middleware('auth');
        $this->middleware('can:VER BANCO INSTRUCTOR AITG');
    }

    public function __invoke(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->menuAccess->unlockSession($user);

        return redirect()
            ->route('aitg.banco-instructores.index')
            ->with('success', 'Módulo de instructores habilitado. Complete su postulación en el Banco de Talento.');
    }
}
