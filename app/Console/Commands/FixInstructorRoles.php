<?php

namespace App\Console\Commands;

use App\Models\Instructor;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class FixInstructorRoles extends Command
{
    protected $signature = 'roles:fix-instructors {--dry-run : Solo mostrar qué se haría sin ejecutar cambios}';

    protected $description = 'Asigna el rol INSTRUCTOR a todos los instructores que no lo tienen';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->showDryRunBanner($dryRun);
        $this->info('🔧 Corrigiendo roles de instructores...');
        $this->newLine();

        Role::firstOrCreate(['name' => 'INSTRUCTOR']);

        $instructores = Instructor::with(['persona.user'])->get();
        $stats = $this->fixInstructorRoles($instructores, $dryRun);

        $this->showSummary($instructores->count(), $stats, $dryRun);

        return self::SUCCESS;
    }

    private function showDryRunBanner(bool $dryRun): void
    {
        if (! $dryRun) {
            return;
        }

        $this->warn('🔍 MODO DRY-RUN: Solo se mostrarán los cambios que se harían');
        $this->newLine();
    }

    /**
     * @param  Collection<int, Instructor>  $instructores
     * @return array{corregidos: int, ya_tienen_rol: int}
     */
    private function fixInstructorRoles($instructores, bool $dryRun): array
    {
        $corregidos = 0;
        $yaTienenRol = 0;

        foreach ($instructores as $instructor) {
            $resultado = $this->processInstructor($instructor, $dryRun);

            if ($resultado === 'corrected') {
                $corregidos++;
            } elseif ($resultado === 'already_has') {
                $yaTienenRol++;
            }
        }

        return ['corregidos' => $corregidos, 'ya_tienen_rol' => $yaTienenRol];
    }

    private function processInstructor(Instructor $instructor, bool $dryRun): ?string
    {
        if (! $instructor->persona?->user) {
            return null;
        }

        $user = $instructor->persona->user;
        $nombre = trim($instructor->persona->primer_nombre.' '.$instructor->persona->primer_apellido);

        if ($user->hasRole('INSTRUCTOR')) {
            $this->line("ℹ️  {$nombre}: Ya tiene rol INSTRUCTOR");

            return 'already_has';
        }

        if (! $dryRun) {
            $user->syncRoles(['INSTRUCTOR']);
        }

        $mensaje = $dryRun ? 'Se asignaría rol INSTRUCTOR' : 'Rol INSTRUCTOR asignado';
        $this->line("✅ {$nombre}: {$mensaje}");

        return 'corrected';
    }

    /**
     * @param  array{corregidos: int, ya_tienen_rol: int}  $stats
     */
    private function showSummary(int $totalInstructores, array $stats, bool $dryRun): void
    {
        $this->newLine();
        $this->info('📊 RESUMEN:');
        $this->line("   - Corregidos: {$stats['corregidos']}");
        $this->line("   - Ya tenían rol: {$stats['ya_tienen_rol']}");
        $this->line("   - Total procesados: {$totalInstructores}");

        if ($stats['corregidos'] > 0) {
            $this->newLine();
            $verbo = $dryRun ? 'Se corregirían' : 'Se corrigieron';
            $this->info("✅ {$verbo} {$stats['corregidos']} instructores.");

            return;
        }

        $this->info('✅ Todos los instructores ya tienen el rol correcto.');
    }
}
