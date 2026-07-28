<?php

namespace App\Console\Commands;

use App\Models\Parametro;
use App\Models\Persona;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AsignarTipoDocumentoPorDefecto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aprendices:asignar-tipo-documento {--dry-run : Solo mostrar qué se actualizaría sin hacer cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna tipo de documento por defecto a personas que no lo tienen';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info('🔍 Buscando personas sin tipo de documento...');

        $personasSinTipoDoc = Persona::whereNull('tipo_documento')->get();

        if ($personasSinTipoDoc->isEmpty()) {
            $this->info('✅ Todas las personas ya tienen tipo de documento asignado.');

            return self::SUCCESS;
        }

        return $this->procesarPersonasSinTipoDocumento($personasSinTipoDoc, $isDryRun);
    }

    /**
     * @param  Collection<int, Persona>  $personasSinTipoDoc
     */
    private function procesarPersonasSinTipoDocumento(Collection $personasSinTipoDoc, bool $isDryRun): int
    {
        $exitCode = self::SUCCESS;

        $this->warn("⚠️  Se encontraron {$personasSinTipoDoc->count()} personas sin tipo de documento:");

        $tipoDocDefecto = Parametro::where('name', 'CEDULA DE CIUDADANIA')->first();

        if (! $tipoDocDefecto) {
            $this->error('❌ No se encontró el parámetro "CEDULA DE CIUDADANIA"');
            $exitCode = self::FAILURE;
        } else {
            $this->info("📋 Tipo de documento por defecto: {$tipoDocDefecto->name} (ID: {$tipoDocDefecto->id})");
            $this->mostrarVistaPreviaPersonas($personasSinTipoDoc);

            if ($isDryRun) {
                $this->info('🔍 Modo dry-run: No se realizarán cambios.');
                $this->info('💡 Ejecuta sin --dry-run para asignar el tipo de documento.');
            } elseif (! $this->confirm('¿Deseas asignar el tipo de documento por defecto a todas estas personas?')) {
                $this->info('❌ Operación cancelada.');
            } else {
                $exitCode = $this->asignarTipoDocumento($tipoDocDefecto);

                if ($exitCode === self::SUCCESS) {
                    $this->verificarAsignacionCompleta();
                }
            }
        }

        return $exitCode;
    }

    /**
     * @param  Collection<int, Persona>  $personasSinTipoDoc
     */
    private function mostrarVistaPreviaPersonas(Collection $personasSinTipoDoc): void
    {
        $this->info('👥 Primeras 5 personas que se actualizarían:');
        $personasSinTipoDoc->take(5)->each(function ($persona, $index) {
            $this->line('   '.($index + 1).". {$persona->nombre_completo} - {$persona->numero_documento}");
        });

        if ($personasSinTipoDoc->count() > 5) {
            $this->line('   ... y '.($personasSinTipoDoc->count() - 5).' más');
        }
    }

    private function asignarTipoDocumento(Parametro $tipoDocDefecto): int
    {
        $this->info('🔄 Asignando tipo de documento por defecto...');

        try {
            DB::beginTransaction();

            $actualizadas = Persona::whereNull('tipo_documento')
                ->update(['tipo_documento' => $tipoDocDefecto->id]);

            DB::commit();

            $this->info("✅ Se actualizaron {$actualizadas} personas con el tipo de documento por defecto.");

            return self::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error durante la actualización: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    private function verificarAsignacionCompleta(): void
    {
        $personasRestantes = Persona::whereNull('tipo_documento')->count();

        if ($personasRestantes === 0) {
            $this->info('✅ Verificación: Todas las personas ahora tienen tipo de documento asignado.');
        } else {
            $this->warn("⚠️  Aún quedan {$personasRestantes} personas sin tipo de documento.");
        }
    }
}
