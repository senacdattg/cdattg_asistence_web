<?php

namespace App\Services\Aitg\Banco;

use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\User;
use App\Services\Aitg\Postulacion\AitgPostulacionItemsService;

/** Evaluación de secciones documentales y si una postulación puede enviarse. */
class AitgBancoEnvioService
{
    public function __construct(
        private readonly AitgBancoRequisitosService $requisitosService,
        private readonly AitgPostulacionItemsService $postulacionItemsService
    ) {}

    public function seccionesDocumentales(PostulacionPlan $postulacion, User $user): array
    {
        if ($postulacion->requierePerfil() && $postulacion->faseDocumental() === 'inicial') {
            return [];
        }

        $soloTipos = $postulacion->esBancoTalento()
            || ($postulacion->esConvocatoria() && $postulacion->faseDocumental() === 'post_seleccion');

        if ($soloTipos) {
            return $this->seccionesTiposArchivo($postulacion, $user);
        }

        $this->postulacionItemsService->instanciarDesdePlan($postulacion);

        $secciones = $this->requiereDocumentosBaseEnConvocatoria($postulacion, $user)
            ? $this->seccionesTiposArchivo($postulacion, $user)
            : [];

        return array_merge($secciones, $this->postulacionItemsService->construirSecciones($postulacion, $user));
    }

    public function seccionesTiposArchivo(PostulacionPlan $postulacion, User $user): array
    {
        $secciones = $this->requisitosService->construirSecciones($postulacion, $user);

        return array_values(array_filter(
            $secciones,
            fn (array $seccion) => $seccion['key'] !== 'puntos_adicionales'
        ));
    }

    public function requiereDocumentosBaseEnConvocatoria(PostulacionPlan $postulacion, User $user): bool
    {
        if (! $postulacion->esConvocatoria()) {
            return false;
        }

        $competenciaId = $postulacion->competencia_id ?? $postulacion->plan?->competencia_id;

        if (! $competenciaId) {
            return false;
        }

        $bancoAprobado = PostulacionPlan::query()
            ->where('user_id', $user->id)
            ->where('competencia_id', $competenciaId)
            ->whereNull('convocatoria_id')
            ->where('estado', 'aprobado')
            ->exists();

        return ! $bancoAprobado;
    }

    public function puedeEnviar(PostulacionPlan $postulacion, User $user): bool
    {
        $sinPerfil = $postulacion->requierePerfil() && $postulacion->faseDocumental() === 'inicial';
        $formalizacion = $postulacion->esConvocatoria() && $postulacion->faseDocumental() === 'post_seleccion';

        if ($sinPerfil) {
            return false;
        }

        if ($formalizacion) {
            return $this->puedeEnviarDocumentosBase($postulacion, $user);
        }

        $itemsOk = $this->postulacionItemsService->puedeEnviar($postulacion);
        $faltaBase = $postulacion->esConvocatoria()
            && $this->requiereDocumentosBaseEnConvocatoria($postulacion, $user)
            && ! $this->puedeEnviarDocumentosBase($postulacion, $user);

        return $itemsOk
            && ! $faltaBase
            && ($postulacion->esConvocatoria() || $this->puedeEnviarDocumentosBase($postulacion, $user));
    }

    public function puedeEnviarDocumentosBase(PostulacionPlan $postulacion, User $user): bool
    {
        $secciones = $this->seccionesTiposArchivo($postulacion, $user);

        if ($postulacion->estado === 'requiere_correccion') {
            return $this->correccionesCompletas($secciones);
        }

        return $this->obligatoriosCompletos($secciones);
    }

    /** @param list<array<string, mixed>> $secciones */
    private function correccionesCompletas(array $secciones): bool
    {
        foreach ($secciones as $seccion) {
            foreach ($seccion['items'] as $item) {
                if (! ($item['requiere_accion'] ?? false)) {
                    continue;
                }

                $vinculado = $item['vinculado'] ?? null;
                if (! $vinculado || $vinculado->estado === 'rechazado') {
                    return false;
                }
            }
        }

        return true;
    }

    /** @param list<array<string, mixed>> $secciones */
    private function obligatoriosCompletos(array $secciones): bool
    {
        foreach ($secciones as $seccion) {
            foreach ($seccion['items'] as $item) {
                if ($item['obligatorio'] && empty($item['vinculado'])) {
                    return false;
                }
            }
        }

        return true;
    }
}
