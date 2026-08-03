<?php

namespace App\Services\Aitg\Banco;

use App\Models\Aitg\Banco\ArchivoTalento;
use App\Models\Aitg\Banco\PostulacionArchivo;
use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\Aitg\Banco\TipoArchivo;
use App\Models\Aitg\PlanContratacion;
use App\Models\Competencia;
use App\Models\Aitg\Postulacion\PostulacionChecklistItem;
use App\Models\Aitg\Postulacion\PostulacionPuntoItem;
use App\Models\User;
use App\Services\Aitg\Convocatoria\AitgConvocatoriaReglasService;
use App\Services\Aitg\Postulacion\AitgPostulacionItemsService;
use App\Support\Aitg\AitgMenuAccess;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Gestión del banco de talento: postulaciones, bóveda de archivos y reutilización. */
class AitgBancoTalentoService
{
    private const STORAGE_FOLDER = 'aitg_banco_talento';

    public function __construct(
        private readonly AitgConvocatoriaReglasService $convocatoriaReglasService,
        private readonly AitgPostulacionItemsService $postulacionItemsService,
        private readonly AitgBancoEnvioService $envioService,
        private readonly AitgBancoConsultaService $consultaService
    ) {}

    public function obtenerPostulacion(User $user, Competencia $competencia): PostulacionPlan
    {
        $existente = PostulacionPlan::where('user_id', $user->id)
            ->where('competencia_id', $competencia->id)
            ->whereNull('convocatoria_id')
            ->first();

        if ($existente) {
            return $existente->fresh(['archivos.tipoArchivo', 'competencia']);
        }

        return PostulacionPlan::create([
            'user_id' => $user->id,
            'competencia_id' => $competencia->id,
            'plan_contratacion_id' => null,
            'convocatoria_id' => null,
            'persona_id' => $user->persona?->id,
            'estado' => 'borrador',
            'fase_actual' => 'inicial',
            'user_create_id' => $user->id,
            'user_update_id' => $user->id,
        ])->fresh(['competencia']);
    }

    public function eliminarPostulacion(PostulacionPlan $postulacion, User $user): void
    {
        abort_unless($postulacion->user_id === $user->id, 403);
        abort_unless($postulacion->puedeEliminar(), 422, $postulacion->mensajeNoEliminable() ?? 'No puede eliminar esta postulación en el estado actual.');

        DB::transaction(function () use ($postulacion) {
            $postulacion->load(['archivos.archivoTalento', 'checklistItems', 'puntoItems']);

            foreach ($postulacion->archivos as $vinculo) {
                $this->eliminarVinculoArchivoCompleto($vinculo);
            }

            $postulacion->checklistItems()->delete();
            $postulacion->puntoItems()->delete();
            $postulacion->delete();
        });
    }

    private function eliminarVinculoArchivoCompleto(PostulacionArchivo $vinculo): void
    {
        $this->postulacionItemsService->desvincularArchivo($vinculo);

        $archivo = $vinculo->archivoTalento;
        $vinculo->validaciones()->delete();
        $vinculo->delete();

        if (! $archivo) {
            return;
        }

        if (! PostulacionArchivo::where('archivo_talento_id', $archivo->id)->exists()) {
            if (Storage::disk($archivo->storage_disk)->exists($archivo->storage_path)) {
                Storage::disk($archivo->storage_disk)->delete($archivo->storage_path);
            }
            $archivo->delete();
        }
    }

    public function obtenerPostulacionConvocatoria(User $user, \App\Models\Aitg\Convocatoria\Convocatoria $convocatoria): PostulacionPlan
    {
        $postulacionExistente = PostulacionPlan::where('user_id', $user->id)
            ->where('convocatoria_id', $convocatoria->id)
            ->first();

        if ($postulacionExistente) {
            $this->postulacionItemsService->instanciarDesdePlan($postulacionExistente->loadMissing('plan'));

            return $postulacionExistente->fresh([
                'checklistItems.postulacionArchivo',
                'puntoItems.postulacionArchivo',
            ]);
        }

        abort_unless($convocatoria->puedePostular(), 403, 'Esta convocatoria no acepta postulaciones.');

        $this->convocatoriaReglasService->validarPuedePostularConvocatoria($user, $convocatoria);

        $banco = $this->consultaService->bancoHabilitadoParaCompetencia($user, $convocatoria->competencia_id);

        $postulacion = PostulacionPlan::create([
            'user_id' => $user->id,
            'convocatoria_id' => $convocatoria->id,
            'persona_id' => $user->persona?->id,
            'competencia_id' => $convocatoria->competencia_id,
            'plan_contratacion_id' => $convocatoria->plan_contratacion_id,
            'perfil_plan_id' => null,
            'estado' => 'borrador',
            'fase_actual' => 'inicial',
            'user_create_id' => $user->id,
            'user_update_id' => $user->id,
        ]);

        $postulacion = $this->postulacionItemsService->instanciarDesdePlan($postulacion);

        if ($banco) {
            $this->postulacionItemsService->precargarDesdeBanco($postulacion, $banco);
            $this->precargarDocumentosDesdeBanco($postulacion, $banco);
        }

        return $postulacion->fresh([
            'archivos.archivoTalento',
            'perfilPlan',
            'checklistItems.postulacionArchivo',
            'puntoItems.postulacionArchivo',
        ]);
    }

    /** Vincula los documentos aprobados del banco a una nueva postulación de convocatoria. */
    public function precargarDocumentosDesdeBanco(PostulacionPlan $postulacionConvocatoria, PostulacionPlan $banco): void
    {
        abort_unless($postulacionConvocatoria->esConvocatoria() && $banco->esBancoTalento(), 422);

        $banco->load('archivos');

        foreach ($banco->archivos as $vinculoBanco) {
            if ($vinculoBanco->estado !== 'aprobado') {
                continue;
            }

            $yaExiste = PostulacionArchivo::where('postulacion_id', $postulacionConvocatoria->id)
                ->where('tipo_archivo_id', $vinculoBanco->tipo_archivo_id)
                ->where('punto_adicional_id', $vinculoBanco->punto_adicional_id)
                ->exists();

            if ($yaExiste) {
                continue;
            }

            PostulacionArchivo::create([
                'postulacion_id' => $postulacionConvocatoria->id,
                'archivo_talento_id' => $vinculoBanco->archivo_talento_id,
                'tipo_archivo_id' => $vinculoBanco->tipo_archivo_id,
                'punto_adicional_id' => $vinculoBanco->punto_adicional_id,
                'estado' => 'pendiente',
            ]);
        }
    }

    public function seleccionarPerfil(PostulacionPlan $postulacion, int $perfilPlanId, User $user): PostulacionPlan
    {
        $perfil = $postulacion->plan->perfiles()->where('id', $perfilPlanId)->firstOrFail();

        $postulacion->update([
            'perfil_plan_id' => $perfil->id,
            'user_update_id' => $user->id,
        ]);

        $postulacion = $postulacion->fresh(['plan.perfiles', 'plan.checklist', 'plan.puntosAdicionales', 'perfilPlan']);
        $this->postulacionItemsService->instanciarDesdePlan($postulacion);

        return $postulacion->fresh([
            'plan.perfiles',
            'plan.checklist',
            'plan.puntosAdicionales',
            'perfilPlan',
            'checklistItems.postulacionArchivo',
            'puntoItems.postulacionArchivo',
        ]);
    }

    /**
     * @param  array{
     *     tipo_archivo_id?: int|null,
     *     punto_adicional_id?: int|null,
     *     checklist_item_id?: int|null,
     *     punto_item_id?: int|null,
     *     perfil_plan_id?: int|null
     * }  $contexto
     */
    public function subirArchivo(
        PostulacionPlan $postulacion,
        UploadedFile $archivo,
        User $user,
        array $contexto = []
    ): PostulacionArchivo {
        $tipoArchivoId = $contexto['tipo_archivo_id'] ?? null;
        $puntoAdicionalId = $contexto['punto_adicional_id'] ?? null;
        $checklistItemId = $contexto['checklist_item_id'] ?? null;
        $puntoItemId = $contexto['punto_item_id'] ?? null;
        $perfilPlanId = $contexto['perfil_plan_id'] ?? null;

        $plan = $postulacion->plan;
        $competenciaId = $postulacion->competencia_id ?? $plan?->competencia_id;
        abort_unless($competenciaId, 422, 'La postulación no tiene competencia asociada.');
        $tipo = $tipoArchivoId ? TipoArchivo::findOrFail($tipoArchivoId) : null;

        if ($puntoItemId && ! $puntoAdicionalId) {
            $puntoAdicionalId = PostulacionPuntoItem::where('postulacion_id', $postulacion->id)
                ->findOrFail($puntoItemId)
                ->punto_adicional_id;
        }

        $codigo = $this->codigoNombreArchivo($tipo?->codigo, $checklistItemId, $perfilPlanId);
        $nombreAlmacenado = $this->generarNombre($user, $codigo, $archivo);
        $disk = config('filesystems.aitg_banco_disk', 'public');
        $path = Storage::disk($disk)->putFileAs(self::STORAGE_FOLDER, $archivo, $nombreAlmacenado);

        if ($tipo && ! $tipo->permite_multiples) {
            $this->desvincularTipoAnterior($postulacion, $tipo->id);
        }

        $archivoTalento = ArchivoTalento::create([
            'user_id' => $user->id,
            'tipo_archivo_id' => $tipo?->id,
            'competencia_id' => $competenciaId,
            'plan_contratacion_id' => $postulacion->plan_contratacion_id,
            'perfil_plan_id' => $postulacion->perfil_plan_id,
            'punto_adicional_id' => $puntoAdicionalId,
            'storage_disk' => $disk,
            'storage_path' => $path,
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_almacenado' => $nombreAlmacenado,
            'mime_type' => $archivo->getMimeType(),
            'tamano_bytes' => $archivo->getSize(),
            'estado' => 'pendiente',
            'user_create_id' => $user->id,
            'user_update_id' => $user->id,
        ]);

        $vinculo = $this->vincularArchivo($postulacion, $archivoTalento, $tipo?->id, $puntoAdicionalId, $perfilPlanId);

        if ($checklistItemId) {
            $item = PostulacionChecklistItem::where('postulacion_id', $postulacion->id)->findOrFail($checklistItemId);
            $this->postulacionItemsService->vincularArchivoChecklist($item, $vinculo);
        }

        if ($puntoItemId) {
            $item = PostulacionPuntoItem::where('postulacion_id', $postulacion->id)->findOrFail($puntoItemId);
            $this->postulacionItemsService->vincularArchivoPunto($item, $vinculo);
        }

        app(AitgMenuAccess::class)->persistUnlockAfterDocumentUpload($user);

        return $vinculo;
    }

    /** Carga múltiples documentos en un solo envío. */
    public function subirArchivosLote(PostulacionPlan $postulacion, array $archivos, User $user): int
    {
        $subidos = 0;

        foreach ($archivos as $key => $archivo) {
            if (! $archivo instanceof UploadedFile || ! $archivo->isValid()) {
                continue;
            }

            $this->subirArchivo(
                $postulacion,
                $archivo,
                $user,
                $this->contextoDesdeClaveArchivo((string) $key)
            );
            $subidos++;
        }

        return $subidos;
    }

    /** @return array{tipo_archivo_id?: int, punto_adicional_id?: int, checklist_item_id?: int, punto_item_id?: int} */
    private function contextoDesdeClaveArchivo(string $key): array
    {
        $map = [
            'tipo_' => 'tipo_archivo_id',
            'punto_' => 'punto_adicional_id',
            'checklist_' => 'checklist_item_id',
            'puntoitem_' => 'punto_item_id',
        ];

        foreach ($map as $prefix => $campo) {
            if (str_starts_with($key, $prefix)) {
                return [$campo => (int) str_replace($prefix, '', $key)];
            }
        }

        return [];
    }

    private function codigoNombreArchivo(?string $codigoTipo, ?int $checklistItemId, ?int $perfilPlanId): string
    {
        return match (true) {
            filled($codigoTipo) => (string) $codigoTipo,
            $checklistItemId !== null => 'CHK',
            $perfilPlanId !== null => 'PERFIL',
            default => 'PUNTO',
        };
    }

    public function reutilizarArchivo(PostulacionPlan $postulacion, ArchivoTalento $archivo, User $user): PostulacionArchivo
    {
        abort_unless($archivo->user_id === $user->id, 403);

        if ($archivo->tipo_archivo_id) {
            $this->desvincularTipoAnterior($postulacion, $archivo->tipo_archivo_id);
        }

        $vinculo = $this->vincularArchivo(
            $postulacion,
            $archivo,
            $archivo->tipo_archivo_id,
            $archivo->punto_adicional_id
        );

        app(AitgMenuAccess::class)->persistUnlockAfterDocumentUpload($user);

        return $vinculo;
    }

    public function eliminarDocumentoPostulacion(PostulacionPlan $postulacion, PostulacionArchivo $vinculo, User $user): void
    {
        abort_unless($postulacion->user_id === $user->id, 403);
        abort_unless($postulacion->puedeEditar(), 403);
        abort_unless($vinculo->postulacion_id === $postulacion->id, 404);

        $this->postulacionItemsService->desvincularArchivo($vinculo);

        $archivo = $vinculo->archivoTalento;
        $vinculo->delete();

        if ($archivo && ! PostulacionArchivo::where('archivo_talento_id', $archivo->id)->exists()) {
            if (Storage::disk($archivo->storage_disk)->exists($archivo->storage_path)) {
                Storage::disk($archivo->storage_disk)->delete($archivo->storage_path);
            }
            $archivo->delete();
        }
    }

    public function enviarRevision(PostulacionPlan $postulacion, User $user): PostulacionPlan
    {
        if ($postulacion->requierePerfil()) {
            throw new \InvalidArgumentException('Debe seleccionar el perfil al que aplica antes de enviar.');
        }

        $postulacion->update([
            'estado' => 'pendiente_revision',
            'fecha_envio' => now(),
            'observaciones_validador' => null,
            'user_update_id' => $user->id,
        ]);

        if ($postulacion->esConvocatoria()) {
            $this->postulacionItemsService->marcarEnviada($postulacion);
            $this->marcarArchivosEnRevision($postulacion);

            return $postulacion->fresh(['checklistItems', 'puntoItems']);
        }

        $this->marcarArchivosEnRevision($postulacion);

        return $postulacion->fresh(['archivos.tipoArchivo']);
    }

    public function enviarFormalizacion(PostulacionPlan $postulacion, User $user): PostulacionPlan
    {
        abort_unless($postulacion->esConvocatoria(), 422);
        abort_unless(in_array($postulacion->estado, ['seleccionado', 'requiere_correccion'], true), 422, 'Solo el instructor seleccionado puede enviar la formalización.');
        abort_unless($postulacion->fase_actual === 'post_seleccion', 422);

        if (! $this->envioService->puedeEnviarDocumentosBase($postulacion, $user)) {
            throw new \InvalidArgumentException('Complete todos los documentos obligatorios de formalización.');
        }

        $postulacion->update([
            'estado' => 'pendiente_revision',
            'fecha_envio' => now(),
            'observaciones_validador' => null,
            'user_update_id' => $user->id,
        ]);

        $this->marcarArchivosEnRevision($postulacion);

        return $postulacion->fresh(['archivos.tipoArchivo']);
    }

    private function marcarArchivosEnRevision(PostulacionPlan $postulacion): void
    {
        $fase = $postulacion->faseDocumental();
        $marcados = collect();

        if ($postulacion->checklistItems()->exists() && $fase === 'inicial') {
            $this->postulacionItemsService->archivosPostulacion($postulacion)->each(function ($a) use ($marcados) {
                $a->update(['estado' => 'en_revision']);
                $marcados->push($a->id);
            });
        }

        $postulacion->loadMissing('archivos.tipoArchivo');

        foreach ($postulacion->archivos as $vinculo) {
            if ($marcados->contains($vinculo->id)) {
                continue;
            }

            if ($vinculo->perfil_plan_id && $fase === 'inicial') {
                $vinculo->update(['estado' => 'en_revision']);

                continue;
            }

            $tipo = $vinculo->tipoArchivo;
            if ($tipo && ($tipo->fase_carga ?? 'inicial') === $fase) {
                $vinculo->update(['estado' => 'en_revision']);
            }
        }
    }

    private function vincularArchivo(
        PostulacionPlan $postulacion,
        ArchivoTalento $archivoTalento,
        ?int $tipoArchivoId,
        ?int $puntoAdicionalId,
        ?int $perfilPlanId = null
    ): PostulacionArchivo {
        if ($puntoAdicionalId) {
            PostulacionArchivo::where('postulacion_id', $postulacion->id)
                ->where('punto_adicional_id', $puntoAdicionalId)
                ->delete();
        }

        if ($perfilPlanId) {
            PostulacionArchivo::where('postulacion_id', $postulacion->id)
                ->where('perfil_plan_id', $perfilPlanId)
                ->delete();
        }

        return PostulacionArchivo::create([
            'postulacion_id' => $postulacion->id,
            'archivo_talento_id' => $archivoTalento->id,
            'tipo_archivo_id' => $tipoArchivoId,
            'punto_adicional_id' => $puntoAdicionalId,
            'perfil_plan_id' => $perfilPlanId,
            'estado' => 'pendiente',
        ]);
    }

    private function desvincularTipoAnterior(PostulacionPlan $postulacion, int $tipoArchivoId): void
    {
        PostulacionArchivo::where('postulacion_id', $postulacion->id)
            ->where('tipo_archivo_id', $tipoArchivoId)
            ->delete();
    }

    private function generarNombre(User $user, string $codigo, UploadedFile $archivo): string
    {
        return Str::upper($codigo) . "_{$user->id}_" . now()->format('YmdHis') . '.' . $archivo->getClientOriginalExtension();
    }
}
