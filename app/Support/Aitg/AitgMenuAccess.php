<?php

namespace App\Support\Aitg;

use App\Models\Aitg\Banco\ArchivoTalento;
use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Session;

/**
 * Controla acceso y menú lateral AITG para aspirantes.
 *
 * - Clic en «Conviértete en Instructor SENA»: habilita acceso (sesión), sin mostrar menú.
 * - Menú lateral: solo tras subir al menos un documento (flag permanente) o si es personal AITG.
 * - Sin documentos, en la siguiente visita hay que volver a hacer clic para entrar.
 */
class AitgMenuAccess
{
    public const SESSION_KEY = 'aitg_menu_unlocked';

    /** @var list<string> */
    public const STAFF_PERMISSIONS = [
        'VER SOLICITUD BANCO AITG',
        'VALIDAR DOCUMENTO BANCO AITG',
        'VER EVALUACION AITG',
        'EVALUAR POSTULACION AITG',
        'VER SELECCION AITG',
        'SELECCIONAR INSTRUCTOR AITG',
        'VER CONVOCATORIA AITG',
        'CREAR CONVOCATORIA AITG',
        'EDITAR CONVOCATORIA AITG',
        'VER PLAN CONTRATACION',
        'CREAR PLAN CONTRATACION',
        'EDITAR PLAN CONTRATACION',
        'VER TIPO ARCHIVO AITG',
        'VER MOTIVO RECHAZO AITG',
    ];

    public function isStaff(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        foreach (self::STAFF_PERMISSIONS as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasUploadedDocuments(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if (ArchivoTalento::query()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return PostulacionPlan::query()
            ->where('user_id', $user->id)
            ->whereHas('archivos')
            ->exists();
    }

    public function isSessionUnlocked(): bool
    {
        return (bool) Session::get(self::SESSION_KEY, false);
    }

    public function isPermanentlyUnlocked(?User $user): bool
    {
        return (bool) ($user?->aitg_menu_unlocked);
    }

    public function unlockSession(?User $user = null): void
    {
        Session::put(self::SESSION_KEY, true);

        if ($user instanceof User && $this->hasUploadedDocuments($user)) {
            $this->markPermanentlyUnlocked($user);
        }
    }

    /** Tras el primer documento, el menú lateral queda visible de forma permanente. */
    public function persistUnlockAfterDocumentUpload(User $user): void
    {
        $this->markPermanentlyUnlocked($user);
    }

    public function markPermanentlyUnlocked(User $user): void
    {
        if ($user->aitg_menu_unlocked) {
            return;
        }

        $user->forceFill(['aitg_menu_unlocked' => true])->save();
    }

    /** Visibilidad del menú lateral AdminLTE (permanente tras documentos, o staff). */
    public function shouldShowSidebarMenu(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->isStaff($user) || $this->isPermanentlyUnlocked($user);
    }

    /**
     * Acceso a Banco / convocatorias y barra superior SPA.
     * Se habilita con el clic en «Conviértete en Instructor SENA» (sesión) o de forma permanente.
     */
    public function canAccessAspirantArea(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->isStaff($user)
            || $this->isSessionUnlocked()
            || $this->isPermanentlyUnlocked($user);
    }
}
