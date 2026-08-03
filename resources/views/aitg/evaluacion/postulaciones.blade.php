@extends('aitg.layouts.spa')

@section('title', 'Postulaciones en evaluación - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Postulaciones — ' . ($convocatoria->titulo ?? 'Convocatoria'),
        'subtitle' => ($convocatoria->competencia->nombre ?? '') . ' · ' . ($convocatoria->regional->nombre ?? ''),
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Evaluación', 'url' => route('aitg.evaluacion.index')],
            ['label' => 'Postulaciones', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('aitg.evaluacion.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver a convocatorias
            </a>
        </div>

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Documento</th>
                                <th>Nombre</th>
                                <th>Perfil</th>
                                <th>Puntaje</th>
                                <th>Estado evaluación</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($postulaciones as $postulacion)
                                @php
                                    $persona = $postulacion->user->persona;
                                    $evaluacion = $postulacion->evaluacion;
                                @endphp
                                <tr>
                                    <td>{{ $persona->numero_documento ?? '—' }}</td>
                                    <td>{{ trim(($persona->primer_nombre ?? '') . ' ' . ($persona->primer_apellido ?? '')) ?: $postulacion->user->email }}</td>
                                    <td>{{ $postulacion->perfilPlan->descripcion_criterio ?? '—' }}</td>
                                    <td>
                                        @if($evaluacion)
                                            <strong>{{ number_format($evaluacion->puntaje_total, 2) }}</strong>
                                            <br><small class="text-muted">Checklist: {{ number_format($evaluacion->puntaje_checklist, 2) }} · Adicionales: {{ number_format($evaluacion->puntaje_adicionales, 2) }}</small>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if($evaluacion)
                                            <span class="badge badge-info">{{ $evaluacion->estado_label }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $postulacion->estado_label }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($evaluacion && $evaluacion->finalizada())
                                            <a href="{{ route('aitg.evaluacion.show', $evaluacion) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Ver evaluación
                                            </a>
                                        @elseif($evaluacion)
                                            <a href="{{ route('aitg.evaluacion.show', $evaluacion) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-clipboard-check"></i> Evaluar
                                            </a>
                                        @elseif($postulacion->estado === 'preseleccionado')
                                            <form action="{{ route('aitg.evaluacion.iniciar', $postulacion) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-clipboard-check"></i> Evaluar
                                                </button>
                                            </form>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No hay postulaciones para evaluar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
