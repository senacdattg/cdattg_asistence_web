<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:module
                            {module? : El nombre del módulo a migrar (batch_01_sistema_base, batch_02_permisos, etc.)}
                            {--all : Ejecutar todos los módulos en orden}
                            {--fresh : Ejecutar fresh antes de migrar}
                            {--list : Listar todos los módulos disponibles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta migraciones organizadas por módulos funcionales';

    /**
     * Módulos disponibles en orden de ejecución
     *
     * @var array
     */
    protected $batches = [
        'batch_01_sistema_base' => 'Sistema Base (users, tokens, jobs)',
        'batch_02_permisos' => 'Permisos y Roles (Spatie)',
        'batch_03_parametros' => 'Parámetros y Configuración',
        'batch_04_ubicaciones' => 'Ubicaciones Geográficas (países, departamentos, municipios, sedes)',
        'batch_05_personas' => 'Personas y Usuarios',
        'batch_06_infraestructura' => 'Infraestructura Física (bloques, pisos, ambientes)',
        'batch_07_programas' => 'Programas de Formación',
        'batch_08_fichas' => 'Fichas de Caracterización',
        'batch_09_instructores_aprendices' => 'Instructores, Aprendices y Vigilantes',
        'batch_10_relaciones' => 'Relaciones (aprendiz-ficha, instructor-ficha, ambiente-ficha)',
        'batch_11_jornadas_horarios' => 'Jornadas, Horarios y Días de Formación',
        'batch_12_asistencias' => 'Asistencias y Registros de Entrada/Salida',
        'batch_13_competencias' => 'Competencias, Resultados de Aprendizaje y Guías',
        'batch_14_evidencias' => 'Evidencias de Aprendizaje',
        'batch_15_logs_auditoria' => 'Logs y Auditoría',
        'batch_16_inventario' => 'Módulo de inventario',
        'batch_17_complementarios' => 'Módulo de Complementarios (cursos complementarios, aspirantes, caracterización)',
        'batch_18_entrada_salida' => 'Módulo de Entradas y Salidas',
        'batch_19_biogjgas' => 'Módulo BIOGJGAS Guaviare (Investigación)',
        'batch_20_biogjgas_contenido' => 'Módulo BIOGJGAS submódulos de contenido',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('list')) {
            return $this->listModules();
        }

        if ($this->option('all')) {
            if ($this->option('fresh')) {
                $this->freshDatabase();
            }
            return $this->migrateAll();
        }

        if ($this->option('fresh')) {
            $this->freshDatabase();
        }

        $module = $this->argument('module');

        if (!$module) {
            $this->error('❌ Debes especificar un módulo o usar --all');
            $this->info('💡 Usa: php artisan migrate:module --list para ver todos los módulos');
            return 1;
        }

        return $this->migrateSingleBatch($module);
    }

    /**
     * Lista todos los módulos disponibles
     */
    protected function listModules(): int
    {
        $this->info('📋 Módulos de migración disponibles:');
        $this->newLine();

            foreach ($this->batches as $key => $description) {
            $path = database_path("migrations/{$key}");
            $exists = is_dir($path);
            $status = $exists ? '✓' : '✗';

            $this->line("  {$status} <fg=cyan>{$key}</> - {$description}");
        }

        $this->newLine();
        $this->info('💡 Uso:');
        $this->line('  php artisan migrate:module batch_01_sistema_base');
        $this->line('  php artisan migrate:module --all');
        $this->line('  php artisan migrate:module --all --fresh');

        return 0;
    }

    /**
     * Migra todos los módulos en orden
     */
    protected function migrateAll(): int
    {
        $this->info('🚀 Ejecutando todas las migraciones por módulos...');
        $this->newLine();

        $totalBatches = count($this->batches);
        $currentBatch = 0;

        foreach ($this->batches as $batch => $description) {
            $currentBatch++;
            $this->info("[{$currentBatch}/{$totalBatches}] Migrando: {$batch}");

            $result = $this->migrateSingleBatch($batch, false);

            if ($result !== 0) {
                $this->error("❌ Error al migrar el batch: {$batch}");
                return 1;
            }

            $this->newLine();
        }

        $this->info('✅ Todas las migraciones completadas exitosamente');
        return 0;
    }

    /**
     * Migra un módulo específico
     */
    protected function migrateSingleBatch(string $batch, bool $showHeader = true): int
    {
        if (!array_key_exists($batch, $this->batches)) {
            $this->error("❌ El batch '{$batch}' no existe");
            $this->info('💡 Usa: php artisan migrate:batch --list para ver todos los batches');
            return 1;
        }

        $path = "database/migrations/{$batch}";
        $fullPath = base_path($path);

        if (!is_dir($fullPath)) {
            $this->error("❌ El directorio del módulo no existe: {$path}");
            return 1;
        }

        if ($showHeader) {
            $this->info("🔄 Migrando batch: {$batch}");
            $this->line("   {$this->batches[$batch]}");
            $this->newLine();
        }

        try {
            $exitCode = Artisan::call('migrate', [
                '--path' => $path,
                '--force' => true,
            ]);

            $output = Artisan::output();
            if (!empty(trim($output))) {
                $this->line($output);
            }

            if ($exitCode === 0) {
                $this->info("✓ Batch {$batch} migrado exitosamente");
                return 0;
            } else {
                $this->error("❌ Error al migrar el batch: {$batch}");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Limpia la base de datos eliminando todas las tablas sin ejecutar migrate:fresh
     * que ejecutaría todas las migraciones en orden alfabético sin respetar batches
     */
    protected function freshDatabase(): void
    {
        $this->warn('⚠️  Limpiando base de datos...');

        try {
            // Deshabilitar las restricciones de claves foráneas
            Schema::disableForeignKeyConstraints();

            // Obtener todas las tablas
            $driver = DB::getDriverName();
            $tables = [];

            if ($driver === 'mysql') {
                $databaseName = DB::getDatabaseName();
                $tables = DB::select("SHOW TABLES");
                $tableNames = [];
                foreach ($tables as $table) {
                    $tableArray = (array) $table;
                    $tableNames[] = reset($tableArray);
                }
            } elseif ($driver === 'pgsql') {
                $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                $tableNames = array_map(function($table) {
                    return $table->tablename;
                }, $tables);
            } elseif ($driver === 'sqlite') {
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence'");
                $tableNames = array_map(function($table) {
                    return $table->name;
                }, $tables);
            } else {
                $this->warn("⚠️  Driver de base de datos no soportado: {$driver}");
                return;
            }

            if (empty($tableNames)) {
                $this->info('  ℹ No hay tablas para eliminar');
                Schema::enableForeignKeyConstraints();
                return;
            }

            // Eliminar todas las tablas
            foreach ($tableNames as $table) {
                try {
                    Schema::dropIfExists($table);
                    $this->line("  ✓ Eliminada tabla: {$table}");
                } catch (\Exception $e) {
                    $this->warn("  ⚠ No se pudo eliminar la tabla {$table}: {$e->getMessage()}");
                }
            }

            // Habilitar nuevamente las restricciones de claves foráneas
            Schema::enableForeignKeyConstraints();

            $this->info('✓ Base de datos limpiada exitosamente');
        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            $this->error("❌ Error al limpiar la base de datos: {$e->getMessage()}");
            throw $e;
        }
    }
}

