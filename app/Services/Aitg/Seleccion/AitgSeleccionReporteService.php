<?php

namespace App\Services\Aitg\Seleccion;

use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\Aitg\Convocatoria\Convocatoria;
use Illuminate\Support\Collection;

/** Construye reportes de justificación de selección / no selección por convocatoria. */
class AitgSeleccionReporteService
{
    /**
     * Postulaciones relevantes para el reporte de una convocatoria (ranking + decisión).
     *
     * @return Collection<int, PostulacionPlan>
     */
    public function postulacionesReporte(Convocatoria $convocatoria): Collection
    {
        return PostulacionPlan::query()
            ->with([
                'user.persona',
                'perfilPlan',
                'evaluacion.evaluador',
                'checklistItems' => fn ($q) => $q->orderBy('orden'),
                'puntoItems' => fn ($q) => $q->orderBy('orden'),
            ])
            ->where('convocatoria_id', $convocatoria->id)
            ->whereIn('estado', [
                'evaluacion_aprobada',
                'seleccionado',
                'suplente',
                'rechazado',
                'preseleccionado',
            ])
            ->get()
            ->sortByDesc(fn (PostulacionPlan $p) => (float) ($p->evaluacion?->puntaje_total ?? 0))
            ->values();
    }

    /** @return array<string, mixed> */
    public function reporteGeneral(Convocatoria $convocatoria): array
    {
        $convocatoria->loadMissing(['competencia', 'regional', 'plan', 'postulacionSeleccionada.user.persona']);
        $postulaciones = $this->postulacionesReporte($convocatoria);

        $candidatos = $postulaciones->values()->map(function (PostulacionPlan $postulacion, int $index) use ($postulaciones, $convocatoria) {
            return $this->armarCandidato($postulacion, $index + 1, $postulaciones, $convocatoria);
        });

        return [
            'convocatoria' => [
                'id' => $convocatoria->id,
                'codigo' => $convocatoria->codigo,
                'titulo' => $convocatoria->titulo,
                'estado' => $convocatoria->estado,
                'estado_label' => Convocatoria::ESTADOS[$convocatoria->estado] ?? $convocatoria->estado,
                'competencia' => $convocatoria->competencia->nombre ?? '—',
                'regional' => $convocatoria->regional->nombre ?? '—',
                'plan' => $convocatoria->plan
                    ? trim(($convocatoria->plan->periodo ?? '').' — '.($convocatoria->competencia->nombre ?? 'Plan #'.$convocatoria->plan_contratacion_id))
                    : ('Plan #'.$convocatoria->plan_contratacion_id),
            ],
            'generado_en' => now(),
            'resumen' => [
                'total_candidatos' => $candidatos->count(),
                'seleccionados' => $candidatos->where('resultado_clave', 'seleccionado')->count(),
                'suplentes' => $candidatos->where('resultado_clave', 'suplente')->count(),
                'no_seleccionados' => $candidatos->whereIn('resultado_clave', ['rechazado', 'no_seleccionado', 'pendiente_seleccion'])->count(),
            ],
            'candidatos' => $candidatos,
        ];
    }

    /** @return array<string, mixed> */
    public function reporteIndividual(Convocatoria $convocatoria, PostulacionPlan $postulacion): array
    {
        abort_unless($postulacion->convocatoria_id === $convocatoria->id, 404);

        $general = $this->reporteGeneral($convocatoria);
        $candidato = collect($general['candidatos'])->firstWhere('postulacion_id', $postulacion->id);

        if (! $candidato) {
            abort(404, 'La postulación no forma parte del reporte de esta convocatoria.');
        }

        return [
            'convocatoria' => $general['convocatoria'],
            'generado_en' => $general['generado_en'],
            'candidato' => $candidato,
            'contexto_ranking' => $general['candidatos']->map(fn ($c) => [
                'posicion' => $c['posicion'],
                'nombre' => $c['nombre'],
                'documento' => $c['documento'],
                'puntaje_total' => $c['puntaje_total'],
                'resultado' => $c['resultado'],
            ])->values(),
        ];
    }

    /**
     * Filas planas para exportación Excel del reporte general.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function filasExcelGeneral(Convocatoria $convocatoria): Collection
    {
        $reporte = $this->reporteGeneral($convocatoria);

        return collect($reporte['candidatos'])->map(fn (array $c) => [
            'posicion' => $c['posicion'],
            'documento' => $c['documento'],
            'nombre' => $c['nombre'],
            'perfil' => $c['perfil'],
            'puntaje_checklist' => $c['puntaje_checklist'],
            'puntaje_adicionales' => $c['puntaje_adicionales'],
            'puntaje_total' => $c['puntaje_total'],
            'resultado' => $c['resultado'],
            'justificacion' => $c['justificacion'],
            'criterios_detalle' => collect($c['criterios'])->map(
                fn (array $crit) => $this->formatoCriterioExcel($crit)
            )->implode(' | '),
        ]);
    }

    /** @param array{nombre: string, cumple: mixed, observaciones?: string|null} $crit */
    private function formatoCriterioExcel(array $crit): string
    {
        if ($crit['cumple'] === true) {
            $estado = 'Cumple';
        } elseif ($crit['cumple'] === false) {
            $estado = 'No cumple';
        } else {
            $estado = 'Sin evaluar';
        }

        $linea = $crit['nombre'].': '.$estado;

        return ! empty($crit['observaciones'])
            ? $linea.' ('.$crit['observaciones'].')'
            : $linea;
    }

    /** @return array<string, mixed> */
    private function armarCandidato(
        PostulacionPlan $postulacion,
        int $posicion,
        Collection $todos,
        Convocatoria $convocatoria
    ): array {
        $persona = $postulacion->user?->persona;
        $evaluacion = $postulacion->evaluacion;
        $nombre = trim(($persona->primer_nombre ?? '').' '.($persona->segundo_nombre ?? '').' '.($persona->primer_apellido ?? '').' '.($persona->segundo_apellido ?? ''));
        $nombre = preg_replace('/\s+/', ' ', $nombre) ?: ($postulacion->user?->email ?? 'Candidato #'.$postulacion->id);

        $ganador = $todos->firstWhere('estado', 'seleccionado')
            ?? ($convocatoria->postulacion_seleccionada_id
                ? $todos->firstWhere('id', $convocatoria->postulacion_seleccionada_id)
                : null);

        $resultadoClave = $this->resultadoClave($postulacion);
        $criterios = $postulacion->checklistItems->map(fn ($item) => [
            'nombre' => $item->nombre,
            'obligatorio' => (bool) $item->es_obligatorio,
            'cumple' => $item->cumple,
            'observaciones' => $item->observaciones,
        ])->values()->all();

        $puntos = $postulacion->puntoItems->map(fn ($item) => [
            'descripcion' => $item->descripcion,
            'puntaje' => (float) $item->puntaje_adicional,
            'cumple' => $item->cumple,
            'tiene_evidencia' => $item->tieneDocumento(),
            'observaciones' => $item->observaciones,
        ])->values()->all();

        return [
            'postulacion_id' => $postulacion->id,
            'posicion' => $posicion,
            'documento' => $persona->numero_documento ?? '—',
            'nombre' => $nombre,
            'email' => $postulacion->user?->email ?? '—',
            'perfil' => $postulacion->perfilPlan->descripcion_criterio ?? '—',
            'estado_postulacion' => $postulacion->estado,
            'estado_label' => $postulacion->estado_label,
            'resultado_clave' => $resultadoClave,
            'resultado' => $this->resultadoLabel($resultadoClave),
            'puntaje_checklist' => number_format((float) ($evaluacion->puntaje_checklist ?? 0), 2, '.', ''),
            'puntaje_adicionales' => number_format((float) ($evaluacion->puntaje_adicionales ?? 0), 2, '.', ''),
            'puntaje_total' => number_format((float) ($evaluacion->puntaje_total ?? 0), 2, '.', ''),
            'observaciones_evaluacion' => $evaluacion->observaciones ?? null,
            'observaciones_seleccion' => $postulacion->observaciones_validador,
            'fecha_resolucion' => $postulacion->fecha_resolucion?->format('d/m/Y H:i'),
            'criterios' => $criterios,
            'puntos_adicionales' => $puntos,
            'justificacion' => $this->construirJustificacion($postulacion, $posicion, $todos, $ganador, $resultadoClave),
        ];
    }

    private function resultadoClave(PostulacionPlan $postulacion): string
    {
        return match ($postulacion->estado) {
            'seleccionado' => 'seleccionado',
            'suplente' => 'suplente',
            'rechazado' => 'rechazado',
            'evaluacion_aprobada' => 'pendiente_seleccion',
            default => 'no_seleccionado',
        };
    }

    private function resultadoLabel(string $clave): string
    {
        return match ($clave) {
            'seleccionado' => 'Seleccionado',
            'suplente' => 'Suplente',
            'rechazado' => 'No seleccionado',
            'pendiente_seleccion' => 'Pendiente de selección',
            default => 'No seleccionado',
        };
    }

    private function construirJustificacion(
        PostulacionPlan $postulacion,
        int $posicion,
        Collection $todos,
        ?PostulacionPlan $ganador,
        string $resultadoClave
    ): string {
        $evaluacion = $postulacion->evaluacion;
        $puntaje = number_format((float) ($evaluacion->puntaje_total ?? 0), 2);
        $checklist = number_format((float) ($evaluacion->puntaje_checklist ?? 0), 2);
        $bonus = number_format((float) ($evaluacion->puntaje_adicionales ?? 0), 2);

        $partes = [
            "Ocupó la posición {$posicion} de {$todos->count()} en el ranking documental con puntaje total {$puntaje} ({$checklist}% checklist + {$bonus} puntos adicionales).",
            $this->textoCumplimientoChecklist($postulacion),
        ];

        if ($evaluacion?->observaciones) {
            $partes[] = 'Observaciones del evaluador: '.$evaluacion->observaciones;
        }

        $partes = array_merge(
            $partes,
            $this->textoDecisionSeleccion($postulacion, $posicion, $ganador, $resultadoClave, $puntaje)
        );

        if ($postulacion->observaciones_validador && in_array($resultadoClave, ['seleccionado', 'suplente', 'rechazado'], true)) {
            $partes[] = 'Observaciones del comité de selección: '.$postulacion->observaciones_validador;
        }

        return implode(' ', $partes);
    }

    private function textoCumplimientoChecklist(PostulacionPlan $postulacion): string
    {
        $obligatoriosFallidos = $postulacion->checklistItems
            ->filter(fn ($i) => $i->es_obligatorio && $i->cumple === false)
            ->pluck('nombre')
            ->values();

        if ($obligatoriosFallidos->isNotEmpty()) {
            return 'Criterios obligatorios no cumplidos: '.$obligatoriosFallidos->implode(', ').'.';
        }

        $cumplidos = $postulacion->checklistItems->where('cumple', true)->count();
        $totalCrit = $postulacion->checklistItems->count();

        return "Cumplió {$cumplidos} de {$totalCrit} criterios del checklist documental.";
    }

    /** @return list<string> */
    private function textoDecisionSeleccion(
        PostulacionPlan $postulacion,
        int $posicion,
        ?PostulacionPlan $ganador,
        string $resultadoClave,
        string $puntaje
    ): array {
        return match ($resultadoClave) {
            'seleccionado' => $this->textoSeleccionado($posicion),
            'suplente' => $this->textoSuplente($ganador),
            'rechazado' => $this->textoRechazado($postulacion, $ganador, $puntaje),
            'pendiente_seleccion' => [
                'Tiene evaluación aprobada y permanece a la espera de la confirmación de selección por el comité.',
            ],
            default => ['No fue seleccionado en este proceso.'],
        };
    }

    /** @return list<string> */
    private function textoSeleccionado(int $posicion): array
    {
        $partes = ['Fue seleccionado como instructor ganador de la convocatoria.'];
        $partes[] = $posicion === 1
            ? 'La decisión se sustenta en haber obtenido el mayor puntaje de ranking entre los candidatos con evaluación aprobada.'
            : 'Aunque no ocupaba el primer lugar del ranking, el comité de selección lo designó como ganador.';

        return $partes;
    }

    /** @return list<string> */
    private function textoSuplente(?PostulacionPlan $ganador): array
    {
        $partes = ['Fue designado como suplente del proceso de contratación.'];
        if ($ganador) {
            $partes[] = 'Quedó detrás del seleccionado ('.$this->nombreCorto($ganador).', '.$this->puntajeCorto($ganador).' pts) y se mantiene como alternativa ante eventual desistimiento.';
        }

        return $partes;
    }

    /** @return list<string> */
    private function textoRechazado(PostulacionPlan $postulacion, ?PostulacionPlan $ganador, string $puntaje): array
    {
        if ($postulacion->evaluacion?->estado === 'rechazado') {
            return ['No fue seleccionado porque su evaluación documental fue rechazada.'];
        }

        if ($ganador) {
            return [
                'No fue seleccionado: el comité confirmó como ganador a '.$this->nombreCorto($ganador)
                .' ('.$this->puntajeCorto($ganador).' pts), con puntaje superior o decisión de desempate frente a este candidato ('.$puntaje.' pts).',
            ];
        }

        return ['No fue seleccionado en el proceso de contratación.'];
    }

    private function nombreCorto(PostulacionPlan $postulacion): string
    {
        $nombre = trim(($postulacion->user?->persona?->primer_nombre ?? '').' '.($postulacion->user?->persona?->primer_apellido ?? ''));

        return $nombre !== '' ? $nombre : ('postulación #'.$postulacion->id);
    }

    private function puntajeCorto(PostulacionPlan $postulacion): string
    {
        return number_format((float) ($postulacion->evaluacion?->puntaje_total ?? 0), 2);
    }
}
