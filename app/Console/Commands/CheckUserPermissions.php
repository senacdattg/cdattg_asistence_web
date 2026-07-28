<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CheckUserPermissions extends Command
{
    /**
     * Palabras clave por categoría (orden de evaluación: primera coincidencia gana).
     *
     * @var array<string, list<string>>
     */
    private const PERMISSION_GROUP_KEYWORDS = [
        'PARÁMETROS Y TEMAS' => ['PARAMETRO', 'TEMA'],
        'UBICACIÓN' => ['REGIONAL', 'MUNICIPIO'],
        'INFRAESTRUCTURA' => ['CENTRO', 'SEDE', 'BLOQUE', 'PISO', 'AMBIENTE'],
        'INSTRUCTORES' => ['INSTRUCTOR', 'ESPECIALIDAD'],
        'FICHAS' => ['FICHA'],
        'PERSONAS' => ['PERSONA'],
        'INVENTARIO' => [
            'PRODUCTO', 'CATALOGO', 'CARRITO', 'CATEGORIA', 'MARCA', 'PROVEEDOR',
            'CONTRATO', 'ORDEN', 'PRESTAMO', 'DEVOLUCION', 'ENTRADA', 'SALIDA', 'INVENTARIO',
        ],
        'APRENDICES' => ['APRENDIZ'],
        'PROGRAMAS' => ['PROGRAMA'],
        'COMPETENCIAS Y RAP' => ['COMPETENCIA', 'RAP', 'RESULTADO'],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-permissions {userId? : ID del usuario a verificar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica los permisos de un usuario específico o lista todos los usuarios con sus roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('userId');

        if ($userId) {
            // Mostrar permisos de un usuario específico
            $this->showUserPermissions($userId);
        } else {
            // Listar todos los usuarios con sus roles
            $this->listAllUsers();
        }

        return 0;
    }

    private function showUserPermissions($userId)
    {
        $user = User::with(['persona', 'roles', 'permissions'])->find($userId);

        if (! $user) {
            $this->error("❌ Usuario con ID {$userId} no encontrado.");

            return;
        }

        $this->info("👤 Usuario: {$user->persona->nombre_completo} (ID: {$userId})");
        $this->info("📧 Email: {$user->email}");

        // Mostrar roles
        $roles = $user->roles->pluck('name')->toArray();
        if (empty($roles)) {
            $this->warn('⚠️  Sin roles asignados');
        } else {
            $this->info('🎭 Roles asignados: '.implode(', ', $roles));
        }

        // Obtener todos los permisos (directos y de roles)
        $allPermissions = $user->getAllPermissions();

        $this->info('✅ Permisos totales: '.$allPermissions->count());

        if ($allPermissions->isEmpty()) {
            $this->warn('⚠️  No tiene permisos asignados');

            return;
        }

        // Agrupar permisos por categoría
        $grouped = $this->groupPermissions($allPermissions);

        foreach ($grouped as $category => $permissions) {
            $this->newLine();
            $this->info("📦 {$category} ({$permissions->count()}):");
            foreach ($permissions as $permission) {
                $this->line("   ✓ {$permission->name}");
            }
        }
    }

    private function listAllUsers()
    {
        $users = User::with(['persona', 'roles'])->get();

        if ($users->isEmpty()) {
            $this->warn('⚠️  No hay usuarios registrados.');

            return;
        }

        $this->info("👥 LISTADO DE USUARIOS Y ROLES\n");

        $tableData = [];
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ') ?: 'Sin roles';
            $permissionsCount = $user->getAllPermissions()->count();

            $tableData[] = [
                $user->id,
                $user->persona->nombre_completo ?? 'Sin nombre',
                $user->email,
                $roles,
                $permissionsCount,
            ];
        }

        $this->table(
            ['ID', 'Nombre', 'Email', 'Roles', 'Permisos'],
            $tableData
        );

        $this->newLine();
        $this->info('💡 Usa: php artisan user:check-permissions {userId} para ver los permisos detallados de un usuario');
    }

    private function groupPermissions($permissions)
    {
        $groups = $this->emptyPermissionGroups();

        foreach ($permissions as $permission) {
            $category = $this->resolvePermissionCategory(strtoupper($permission->name));
            $groups[$category]->push($permission);
        }

        return collect($groups)->filter(fn ($group) => $group->isNotEmpty());
    }

    /**
     * @return array<string, Collection<int, mixed>>
     */
    private function emptyPermissionGroups(): array
    {
        $categories = array_keys(self::PERMISSION_GROUP_KEYWORDS);
        $categories[] = 'OTROS';

        return array_combine(
            $categories,
            array_map(fn () => collect(), $categories)
        );
    }

    private function resolvePermissionCategory(string $name): string
    {
        foreach (self::PERMISSION_GROUP_KEYWORDS as $category => $keywords) {
            if ($this->nameMatchesAnyKeyword($name, $keywords)) {
                return $category;
            }
        }

        return 'OTROS';
    }

    /**
     * @param  list<string>  $keywords
     */
    private function nameMatchesAnyKeyword(string $name, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($name, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
