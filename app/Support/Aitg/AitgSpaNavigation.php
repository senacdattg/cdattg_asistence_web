<?php

namespace App\Support\Aitg;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Navegación SPA del módulo AITG (submódulos del flujo contractual).
 */
class AitgSpaNavigation
{
    /** @var list<string> */
    private const ASPIRANT_KEYS = ['convocatorias-publicas', 'banco'];

    /**
     * @return list<array{key: string, label: string, icon: string, route: string|null, url: string|null, can: string|array|null, match: list<string>}>
     */
    public static function items(): array
    {
        return [
            [
                'key' => 'convocatorias-publicas',
                'label' => 'Convocatorias',
                'icon' => 'fa-bullhorn',
                'route' => 'aitg.convocatorias.publicas.index',
                'url' => null,
                // Permiso de aspirante; la barra superior se habilita con sesión tras «Conviértete en Instructor».
                'can' => 'VER BANCO INSTRUCTOR AITG',
                'match' => ['aitg/convocatorias/publicas'],
            ],
            [
                'key' => 'banco',
                'label' => 'Banco',
                'icon' => 'fa-id-card',
                'route' => 'aitg.banco-instructores.index',
                'url' => null,
                'can' => 'VER BANCO INSTRUCTOR AITG',
                'match' => ['aitg/banco-instructores'],
            ],
            [
                'key' => 'validacion',
                'label' => 'Validación',
                'icon' => 'fa-check-double',
                'route' => 'aitg.validacion-banco.index',
                'url' => null,
                'can' => 'VER SOLICITUD BANCO AITG',
                'match' => ['aitg/validacion-banco'],
            ],
            [
                'key' => 'evaluacion',
                'label' => 'Evaluación',
                'icon' => 'fa-tasks',
                'route' => 'aitg.evaluacion.index',
                'url' => null,
                'can' => 'VER EVALUACION AITG',
                'match' => ['aitg/evaluacion'],
            ],
            [
                'key' => 'seleccion',
                'label' => 'Selección',
                'icon' => 'fa-user-check',
                'route' => 'aitg.seleccion.index',
                'url' => null,
                'can' => 'VER SELECCION AITG',
                'match' => ['aitg/seleccion'],
            ],
            [
                'key' => 'convocatorias-gestion',
                'label' => 'Gestión',
                'icon' => 'fa-cogs',
                'route' => 'aitg.convocatorias.index',
                'url' => null,
                'can' => 'VER CONVOCATORIA AITG',
                'match' => ['aitg/convocatorias'],
            ],
            [
                'key' => 'planes',
                'label' => 'Planes',
                'icon' => 'fa-file-contract',
                'route' => 'aitg.planes-contratacion.index',
                'url' => null,
                'can' => 'VER PLAN CONTRATACION',
                'match' => ['aitg/planes-contratacion'],
            ],
            [
                'key' => 'catalogos',
                'label' => 'Catálogos',
                'icon' => 'fa-book',
                'route' => null,
                'url' => null,
                'can' => ['VER TIPO ARCHIVO AITG', 'VER MOTIVO RECHAZO AITG'],
                'match' => ['aitg/tipos-archivo', 'aitg/motivos-rechazo'],
            ],
        ];
    }

    /**
     * @return list<array{key: string, label: string, icon: string, href: string, active: bool}>
     */
    public static function visibleItems(?string $currentPath = null): array
    {
        /** @var User|null $user */
        $user = Auth::user();
        $path = trim($currentPath ?? request()->path(), '/');
        $visible = [];

        $menuAccess = app(AitgMenuAccess::class);

        foreach (self::items() as $item) {
            if (! self::userCan($user, $item['can'] ?? null)) {
                continue;
            }

            // Barra superior: visible con clic de acceso (sesión) o menú permanente.
            // El menú lateral AdminLTE sigue usando ver-menu-aitg (solo permanente/staff).
            if (in_array($item['key'], self::ASPIRANT_KEYS, true)
                && ! $menuAccess->canAccessAspirantArea($user)) {
                continue;
            }

            $href = self::resolveHref($item, $user);
            if ($href === null) {
                continue;
            }

            $visible[] = [
                'key' => $item['key'],
                'label' => $item['label'],
                'icon' => $item['icon'],
                'href' => $href,
                'active' => self::isActive($path, $item['match'], $item['key']),
            ];
        }

        return $visible;
    }

    public static function activeKey(?string $currentPath = null): ?string
    {
        foreach (self::visibleItems($currentPath) as $item) {
            if ($item['active']) {
                return $item['key'];
            }
        }

        return null;
    }

    private static function userCan(?User $user, string|array|null $can): bool
    {
        if ($can === null) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        $permissions = is_array($can) ? $can : [$can];

        return collect($permissions)->contains(
            fn (string $permission): bool => $user->can($permission)
        );
    }

    /** @param array{key?: string, route?: string|null, url?: string|null} $item */
    private static function resolveHref(array $item, ?User $user): ?string
    {
        if (($item['key'] ?? null) === 'catalogos') {
            return self::resolveCatalogosHref($user);
        }

        if (! empty($item['route']) && Route::has($item['route'])) {
            return route($item['route']);
        }

        return ! empty($item['url']) ? url($item['url']) : null;
    }

    private static function resolveCatalogosHref(?User $user): ?string
    {
        if ($user?->can('VER TIPO ARCHIVO AITG') && Route::has('aitg.tipos-archivo.index')) {
            return route('aitg.tipos-archivo.index');
        }

        if ($user?->can('VER MOTIVO RECHAZO AITG') && Route::has('aitg.motivos-rechazo.index')) {
            return route('aitg.motivos-rechazo.index');
        }

        return null;
    }

    /** @param list<string> $matchers */
    private static function isActive(string $path, array $matchers, string $key): bool
    {
        if ($key === 'convocatorias-gestion' && str_starts_with($path, 'aitg/convocatorias/publicas')) {
            return false;
        }

        foreach ($matchers as $matcher) {
            $matcher = trim($matcher, '/');
            if ($path === $matcher || str_starts_with($path, $matcher.'/')) {
                return true;
            }
        }

        return false;
    }
}
