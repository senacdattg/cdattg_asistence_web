<?php

namespace App\Console\Commands;

use App\Models\Inventario\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillSenaBarcodes extends Command
{
    protected $signature = 'productos:backfill-sena-barcodes {--dry-run : Muestra qué haría sin guardar cambios} {--chunk=500 : Tamaño de lote para procesar}';

    protected $description = 'Genera y asigna códigos SENA de 11 dígitos incrementales para productos que no lo tengan';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $chunk = (int) $this->option('chunk');

        $this->info('Iniciando asignación de códigos SENA de 11 dígitos'.($dryRun ? ' (dry-run)' : ''));

        $normalizados = $this->normalizarCodigosExistentes($chunk, $dryRun);

        if ($normalizados > 0) {
            $this->info("Normalizados {$normalizados} códigos existentes a 11 dígitos");
        }

        $resultado = $this->asignarCodigosFaltantes($chunk, $dryRun);

        $this->info("Procesados: {$resultado['procesados']}, Asignados: {$resultado['asignados']}");

        return self::SUCCESS;
    }

    private function normalizarCodigosExistentes(int $chunk, bool $dryRun): int
    {
        $normalizados = 0;

        Producto::whereNotNull('codigo_barras_sena')
            ->select('id', 'codigo_barras_sena')
            ->chunkById($chunk, function ($productos) use (&$normalizados, $dryRun) {
                foreach ($productos as $producto) {
                    if ($this->normalizarCodigoProducto($producto, $dryRun)) {
                        $normalizados++;
                    }
                }
            });

        return $normalizados;
    }

    private function normalizarCodigoProducto(Producto $producto, bool $dryRun): bool
    {
        $soloDigitos = preg_replace('/\D/', '', (string) $producto->codigo_barras_sena);

        if ($soloDigitos === '' || strlen($soloDigitos) === 11) {
            return false;
        }

        $nuevo = $this->formatearCodigoOnceDigitos($soloDigitos);

        if (! $dryRun) {
            $producto->codigo_barras_sena = $nuevo;
            $producto->saveQuietly();
        }

        return true;
    }

    /**
     * @return array{procesados: int, asignados: int}
     */
    private function asignarCodigosFaltantes(int $chunk, bool $dryRun): array
    {
        $procesados = 0;
        $asignados = 0;

        Producto::whereNull('codigo_barras_sena')
            ->select('id')
            ->chunkById($chunk, function ($productos) use (&$procesados, &$asignados, $dryRun) {
                foreach ($productos as $producto) {
                    $procesados++;

                    if ($this->procesarProductoSinCodigo($producto, $dryRun)) {
                        $asignados++;
                    }
                }
            });

        return ['procesados' => $procesados, 'asignados' => $asignados];
    }

    private function procesarProductoSinCodigo(Producto $producto, bool $dryRun): bool
    {
        $siguiente = $this->generarSiguienteCodigo();

        if ($dryRun) {
            $this->line("[dry-run] Producto {$producto->id} -> {$siguiente}");

            return false;
        }

        return $this->asignarCodigoConReintentos($producto, $siguiente);
    }

    private function asignarCodigoConReintentos(Producto $producto, string $siguiente): bool
    {
        $maxIntentos = 3;

        for ($intento = 0; $intento < $maxIntentos; $intento++) {
            try {
                if ($this->asignarCodigoEnTransaccion($producto, $siguiente)) {
                    return true;
                }
            } catch (\Throwable $e) {
                if ($intento === $maxIntentos - 1) {
                    throw $e;
                }
            }
        }

        return false;
    }

    private function asignarCodigoEnTransaccion(Producto $producto, string $siguiente): bool
    {
        return DB::transaction(function () use ($producto, $siguiente) {
            $modelo = Producto::lockForUpdate()->find($producto->id);

            if (! $modelo) {
                return false;
            }

            if ($modelo->codigo_barras_sena) {
                return true;
            }

            $modelo->codigo_barras_sena = $siguiente;
            $modelo->save();

            return true;
        });
    }

    private function formatearCodigoOnceDigitos(string $soloDigitos): string
    {
        return str_pad(substr($soloDigitos, -11), 11, '0', STR_PAD_LEFT);
    }

    private function generarSiguienteCodigo(): string
    {
        return DB::transaction(function () {
            $max = Producto::whereNotNull('codigo_barras_sena')
                ->select('codigo_barras_sena')
                ->max('codigo_barras_sena');

            $soloDigitos = preg_replace('/\D/', '', (string) $max);
            $num = $soloDigitos === '' ? 0 : (int) $soloDigitos;

            return str_pad((string) ($num + 1), 11, '0', STR_PAD_LEFT);
        }, 3);
    }
}
