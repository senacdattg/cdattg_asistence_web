<?php

namespace App\Services\Aitg\Banco;

use App\Models\Aitg\Banco\ArchivoTalento;
use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\Aitg\Convocatoria\Convocatoria;
use App\Models\Aitg\PlanContratacion;
use App\Models\Competencia;
use App\Models\User;
use Illuminate\Support\Collection;

/** Consultas de competencias, postulaciones y bóveda del banco AITG. */
class AitgBancoConsultaService
{
    public function buscarCompetencias(array $filtros): Collection
    {
        $query = Competencia::query()
            ->where('status', true)
            ->whereHas('aitgPlanes', fn ($q) => $q->whereIn('estado', ['activo', 'borrador']))
            ->with(['aitgPlanes' => fn ($q) => $q->whereIn('estado', ['activo', 'borrador'])->with('regional')->orderByDesc('created_at')])
            ->orderBy('nombre');

        if ($nombre = trim((string) ($filtros['competencia'] ?? ''))) {
            $query->where('nombre', 'like', "%{$nombre}%");
        }

        if ($regionalId = $filtros['regional_id'] ?? null) {
            $query->whereHas('aitgPlanes', fn ($q) => $q->where('regional_id', $regionalId));
        }

        if ($modalidad = $filtros['modalidad'] ?? null) {
            $query->whereHas('aitgPlanes', fn ($q) => $q->where('modalidad', $modalidad));
        }

        return $query->limit(20)->get();
    }

    public function listarPostulacionesBanco(User $user): Collection
    {
        return $this->queryPostulacionesUsuario($user)
            ->whereNull('convocatoria_id')
            ->get();
    }

    public function listarPostulacionesConvocatoria(User $user): Collection
    {
        return $this->queryPostulacionesUsuario($user)
            ->whereNotNull('convocatoria_id')
            ->get();
    }

    public function listarPostulacionesDeConvocatoria(Convocatoria $convocatoria): Collection
    {
        return PostulacionPlan::with(['user.persona', 'perfilPlan'])
            ->where('convocatoria_id', $convocatoria->id)
            ->orderByDesc('updated_at')
            ->get();
    }

    public function bancoHabilitadoParaCompetencia(User $user, int $competenciaId): ?PostulacionPlan
    {
        return PostulacionPlan::where('user_id', $user->id)
            ->where('competencia_id', $competenciaId)
            ->whereNull('convocatoria_id')
            ->where('estado', 'aprobado')
            ->first();
    }

    public function bancoHabilitadoParaPlan(User $user, int $planContratacionId): ?PostulacionPlan
    {
        $plan = PlanContratacion::find($planContratacionId);

        if (! $plan?->competencia_id) {
            return null;
        }

        return $this->bancoHabilitadoParaCompetencia($user, $plan->competencia_id);
    }

    public function bovedaUsuario(User $user): Collection
    {
        return ArchivoTalento::with('tipoArchivo')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }

    private function queryPostulacionesUsuario(User $user)
    {
        return PostulacionPlan::with([
            'competencia',
            'plan.competencia',
            'plan.regional',
            'perfilPlan',
            'convocatoria',
            'archivos.tipoArchivo',
            'archivos.puntoAdicional',
            'archivos.validaciones.motivoRechazo',
        ])
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at');
    }
}
