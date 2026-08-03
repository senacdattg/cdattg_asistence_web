<?php

namespace App\Http\Middleware;

use App\Support\Aitg\AitgMenuAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Bloquea rutas aspirante AITG sin clic de acceso ni desbloqueo permanente (documentos). */
class EnsureAitgMenuAccess
{
    public function __construct(
        private readonly AitgMenuAccess $menuAccess
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($this->menuAccess->canAccessAspirantArea($user)) {
            return $next($request);
        }

        return redirect()
            ->route('verificarLogin')
            ->with('info', 'Para acceder al módulo de instructores, haga clic en «Conviértete en Instructor SENA».');
    }
}
