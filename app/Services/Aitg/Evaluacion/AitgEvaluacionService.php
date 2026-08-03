<?php

namespace App\Services\Aitg\Evaluacion;

use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\Aitg\Convocatoria\Convocatoria;
use App\Models\Aitg\Evaluacion\EvaluacionPostulacion;
use App\Models\Aitg\Postulacion\PostulacionChecklistItem;
use App\Models\Aitg\Postulacion\PostulacionPuntoItem;
use App\Models\User;
use App\Services\Aitg\Postulacion\AitgPostulacionItemsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** Evaluación documental sobre los ítems de checklist de la postulación. */
class AitgEvaluacionService
{
    public function __construct(
        private readonly AitgPostulacionItemsService $postulacionItemsService
    ) {}

    public function convocatoriasEnEvaluacion(int $perPage = 15): LengthAwarePaginator
    {
        return $this->convocatoriasConPipeline($perPage);
    }

    /** Convocatorias con conteos por etapa del flujo postulación → validación → evaluación → selección. */
    public function convocatoriasConPipeline(int $perPage = 15): LengthAwarePaginator
    {
        return Convocatoria::query()
            ->with(['competencia', 'regional', 'plan'])
            ->whereIn('estado', ['publicada', 'cerrada', 'finalizada'])
            ->whereHas('postulaciones', fn ($q) => $q->whereNotNull('convocatoria_id'))
            ->withCount([
                'postulaciones as cnt_pendiente_revision' => fn ($q) => $q->where('estado', 'pendiente_revision'),
                'postulaciones as cnt_preseleccionado' => fn ($q) => $q->where('estado', 'preseleccionado'),
                'postulaciones as cnt_evaluacion_aprobada' => fn ($q) => $q->where('estado', 'evaluacion_aprobada'),
                'postulaciones as cnt_seleccionado' => fn ($q) => $q->whereIn('estado', ['seleccionado', 'suplente']),
            ])
            ->orderByDesc('updated_at')
            ->paginate($perPage);
    }

    public function postulacionesConvocatoria(Convocatoria $convocatoria): Collection
    {
        return PostulacionPlan::query()
            ->with([
                'user.persona',
                'perfilPlan',
                'evaluacion',
            ])
            ->where('convocatoria_id', $convocatoria->id)
            ->whereIn('estado', [
                'preseleccionado',
                'evaluacion_aprobada',
                'seleccionado',
                'suplente',
                'rechazado',
            ])
            ->orderByDesc('fecha_resolucion')
            ->get();
    }

    public function inicializarEvaluacion(PostulacionPlan $postulacion): EvaluacionPostulacion
    {
        if (! $postulacion->esConvocatoria() || $postulacion->estado !== 'preseleccionado') {
            throw new \InvalidArgumentException('Solo postulaciones preseleccionadas de convocatoria pueden iniciar evaluación.');
        }

        $postulacion->loadMissing(['checklistItems', 'puntoItems']);

        if ($postulacion->checklistItems->isEmpty()) {
            throw new \InvalidArgumentException('Esta postulación no tiene checklist documental instanciado.');
        }

        return DB::transaction(function () use ($postulacion) {
            $evaluacion = EvaluacionPostulacion::firstOrCreate(
                ['postulacion_id' => $postulacion->id],
                ['estado' => 'pendiente']
            );

            if ($evaluacion->estado === 'requiere_subsanacion') {
                $evaluacion->update([
                    'estado' => 'pendiente',
                    'fecha_finalizacion' => null,
                ]);
                $postulacion->checklistItems()->update(['solicita_actualizacion' => false]);
            }

            $this->recalcularPuntajes($evaluacion);

            return $evaluacion->fresh(['postulacion.checklistItems.postulacionArchivo', 'postulacion.puntoItems.postulacionArchivo']);
        });
    }

    public function cargarDatosEvaluacion(EvaluacionPostulacion $evaluacion): EvaluacionPostulacion
    {
        $evaluacion->load([
            'postulacion.user.persona',
            'postulacion.perfilPlan',
            'postulacion.convocatoria',
            'postulacion.checklistItems.postulacionArchivo.archivoTalento',
            'postulacion.puntoItems.postulacionArchivo.archivoTalento',
            'evaluador',
        ]);

        if ($evaluacion->estado === 'pendiente') {
            $evaluacion->update(['estado' => 'en_evaluacion']);
        }

        return $evaluacion->fresh([
            'postulacion.user.persona',
            'postulacion.perfilPlan',
            'postulacion.convocatoria',
            'postulacion.checklistItems.postulacionArchivo.archivoTalento',
            'postulacion.puntoItems.postulacionArchivo.archivoTalento',
            'evaluador',
        ]);
    }

    /** @param array<string, mixed> $data */
    public function guardarBorrador(EvaluacionPostulacion $evaluacion, array $data, User $evaluador): EvaluacionPostulacion
    {
        abort_unless($evaluacion->puedeEvaluar(), 422, 'Esta evaluación ya fue finalizada.');

        return DB::transaction(function () use ($evaluacion, $data, $evaluador) {
            $this->aplicarRespuestas($evaluacion, $data);
            $this->recalcularPuntajes($evaluacion);

            $evaluacion->update([
                'observaciones' => $data['observaciones'] ?? $evaluacion->observaciones,
                'evaluador_user_id' => $evaluador->id,
                'estado' => 'en_evaluacion',
            ]);

            return $evaluacion->fresh(['postulacion.checklistItems', 'postulacion.puntoItems']);
        });
    }

    /** @param array<string, mixed> $data */
    public function finalizarEvaluacion(EvaluacionPostulacion $evaluacion, array $data, User $evaluador): EvaluacionPostulacion
    {
        abort_unless($evaluacion->puedeEvaluar(), 422, 'Esta evaluación ya fue finalizada.');

        return DB::transaction(function () use ($evaluacion, $data, $evaluador) {
            $this->aplicarRespuestas($evaluacion, $data);
            $this->recalcularPuntajes($evaluacion);

            $evaluacion->load(['postulacion.checklistItems', 'postulacion.puntoItems']);

            $checklistItems = $evaluacion->postulacion->checklistItems;
            $puntoItems = $evaluacion->postulacion->puntoItems->filter(fn ($p) => $p->tieneDocumento());

            $solicitaSubsanacion = $checklistItems->contains(fn ($r) => $r->solicita_actualizacion);

            if ($checklistItems->contains(fn ($r) => $r->cumple === null)) {
                throw new \InvalidArgumentException('Debe registrar cumple/no cumple en todos los criterios del checklist antes de finalizar.');
            }

            if ($puntoItems->contains(fn ($r) => $r->cumple === null)) {
                throw new \InvalidArgumentException('Debe registrar cumple/no cumple en todos los puntos adicionales con evidencia.');
            }

            if ($solicitaSubsanacion) {
                $evaluacion->update([
                    'estado' => 'requiere_subsanacion',
                    'observaciones' => $data['observaciones'] ?? null,
                    'evaluador_user_id' => $evaluador->id,
                ]);

                $checklistItems->where('solicita_actualizacion', true)->each(function (PostulacionChecklistItem $item) {
                    $item->update(['estado' => 'requiere_subsanacion']);
                });

                $evaluacion->postulacion->update([
                    'estado' => 'requiere_correccion',
                    'observaciones_validador' => 'El comité evaluador solicitó actualizar evidencias documentales.',
                ]);

                return $evaluacion->fresh();
            }

            $obligatorioIncumplido = $checklistItems
                ->filter(fn ($r) => $r->es_obligatorio)
                ->contains(fn ($r) => $r->cumple === false);

            $estadoEvaluacion = ($obligatorioIncumplido || ($data['resultado'] ?? '') === 'rechazado')
                ? 'rechazado'
                : 'aprobado';

            $estadoPostulacion = $estadoEvaluacion === 'aprobado' ? 'evaluacion_aprobada' : 'rechazado';

            $checklistItems->each(fn (PostulacionChecklistItem $item) => $item->update(['estado' => 'evaluado']));
            $puntoItems->each(fn (PostulacionPuntoItem $item) => $item->update(['estado' => 'evaluado']));

            $evaluacion->update([
                'estado' => $estadoEvaluacion,
                'observaciones' => $data['observaciones'] ?? null,
                'evaluador_user_id' => $evaluador->id,
                'fecha_finalizacion' => now(),
            ]);

            $evaluacion->postulacion->update([
                'estado' => $estadoPostulacion,
                'fecha_resolucion' => now(),
                'observaciones_validador' => $data['observaciones'] ?? $evaluacion->postulacion->observaciones_validador,
            ]);

            return $evaluacion->fresh(['postulacion.checklistItems', 'postulacion.puntoItems']);
        });
    }

    public function recalcularPuntajes(EvaluacionPostulacion $evaluacion): void
    {
        $evaluacion->load(['postulacion.checklistItems', 'postulacion.puntoItems']);

        $puntajeChecklist = $this->postulacionItemsService->calcularPuntajeChecklistPorcentaje($evaluacion->postulacion);
        $puntajeAdicionales = $this->postulacionItemsService->calcularPuntajeAdicionales($evaluacion->postulacion);

        $evaluacion->update([
            'puntaje_checklist' => $puntajeChecklist,
            'puntaje_adicionales' => $puntajeAdicionales,
            'puntaje_total' => round($puntajeChecklist + $puntajeAdicionales, 2),
        ]);
    }

    /** @param array<string, mixed> $data */
    private function aplicarRespuestas(EvaluacionPostulacion $evaluacion, array $data): void
    {
        $postulacionId = $evaluacion->postulacion_id;

        foreach ($data['checklist'] ?? [] as $itemId => $row) {
            $item = PostulacionChecklistItem::where('postulacion_id', $postulacionId)->find($itemId);
            if (! $item) {
                continue;
            }

            $cumple = isset($row['cumple']) ? filter_var($row['cumple'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;

            $item->update([
                'cumple' => $cumple,
                'observaciones' => $row['observaciones'] ?? null,
                // Subsanación deshabilitada en el flujo de evaluación/selección.
                'solicita_actualizacion' => false,
            ]);
        }

        foreach ($data['puntos'] ?? [] as $itemId => $row) {
            $item = PostulacionPuntoItem::where('postulacion_id', $postulacionId)->find($itemId);
            if (! $item) {
                continue;
            }

            $cumple = isset($row['cumple']) ? filter_var($row['cumple'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;

            $item->update([
                'cumple' => $cumple,
                'observaciones' => $row['observaciones'] ?? null,
            ]);
        }
    }
}
