<?php

namespace App\Services\Aitg\Seleccion;

use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\Aitg\Convocatoria\Convocatoria;
use App\Models\User;
use App\Services\Aitg\Convocatoria\AitgConvocatoriaEstadoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** Selección del instructor ganador y suplentes. */
class AitgSeleccionService
{
    public function __construct(
        private readonly AitgConvocatoriaEstadoService $convocatoriaEstadoService
    ) {}

    public function convocatoriasParaSeleccion(int $perPage = 15): LengthAwarePaginator
    {
        $estadosCandidatos = ['evaluacion_aprobada', 'seleccionado', 'suplente', 'rechazado'];

        return Convocatoria::query()
            ->with(['competencia', 'regional', 'plan'])
            ->whereIn('estado', ['publicada', 'cerrada', 'finalizada'])
            ->whereHas('postulaciones', fn ($q) => $q->whereIn('estado', $estadosCandidatos)->whereHas('evaluacion'))
            ->withCount(['postulaciones as candidatos_count' => fn ($q) => $q->whereIn('estado', $estadosCandidatos)->whereHas('evaluacion')])
            ->orderByDesc('updated_at')
            ->paginate($perPage);
    }

    public function candidatos(Convocatoria $convocatoria, string $orden = 'desc'): Collection
    {
        $candidatos = PostulacionPlan::query()
            ->with([
                'user.persona',
                'perfilPlan',
                'evaluacion',
            ])
            ->where('convocatoria_id', $convocatoria->id)
            ->whereIn('estado', ['evaluacion_aprobada', 'seleccionado', 'suplente', 'rechazado'])
            ->whereHas('evaluacion')
            ->get()
            ->sortBy(
                fn (PostulacionPlan $p) => (float) ($p->evaluacion?->puntaje_total ?? 0),
                SORT_REGULAR,
                $orden === 'desc'
            )
            ->values();

        return $this->marcarEmpates($candidatos);
    }

    public function seleccionarInstructor(
        Convocatoria $convocatoria,
        PostulacionPlan $ganador,
        User $usuario,
        ?PostulacionPlan $suplente = null,
        ?string $observaciones = null
    ): Convocatoria {
        abort_unless($ganador->convocatoria_id === $convocatoria->id, 422);
        abort_unless($ganador->estado === 'evaluacion_aprobada', 422, 'El aspirante debe tener evaluación aprobada.');

        if ($suplente) {
            abort_unless($suplente->convocatoria_id === $convocatoria->id, 422);
            abort_unless($suplente->estado === 'evaluacion_aprobada', 422);
            abort_unless($suplente->id !== $ganador->id, 422, 'El suplente debe ser distinto al ganador.');
        }

        return DB::transaction(function () use ($convocatoria, $ganador, $suplente, $usuario, $observaciones) {
            $ganador->update([
                'estado' => 'seleccionado',
                'fase_actual' => 'post_seleccion',
                'observaciones_validador' => $observaciones ?? $ganador->observaciones_validador,
                'user_update_id' => $usuario->id,
                'fecha_resolucion' => now(),
            ]);

            if ($suplente) {
                $suplente->update([
                    'estado' => 'suplente',
                    'user_update_id' => $usuario->id,
                ]);
            }

            PostulacionPlan::query()
                ->where('convocatoria_id', $convocatoria->id)
                ->where('estado', 'evaluacion_aprobada')
                ->whereNotIn('id', array_filter([$ganador->id, $suplente?->id]))
                ->update(['estado' => 'rechazado']);

            return $this->convocatoriaEstadoService->finalizarConSeleccion($convocatoria, $ganador);
        });
    }

    /** @param Collection<int, PostulacionPlan> $candidatos */
    private function marcarEmpates(Collection $candidatos): Collection
    {
        $conteo = $candidatos->groupBy(fn ($p) => (string) ($p->evaluacion?->puntaje_total ?? '0'));

        return $candidatos->map(function (PostulacionPlan $p) use ($conteo) {
            $puntaje = (string) ($p->evaluacion?->puntaje_total ?? '0');
            $p->setAttribute('en_empate', ($conteo[$puntaje]?->count() ?? 0) > 1);

            return $p;
        });
    }
}
