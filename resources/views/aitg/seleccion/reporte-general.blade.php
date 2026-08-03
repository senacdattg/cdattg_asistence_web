@extends('aitg.layouts.spa')

@section('title', 'Reporte de selección - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Reporte general de selección',
        'subtitle' => ($reporte['convocatoria']['codigo'] ?? '') . ' — ' . ($reporte['convocatoria']['titulo'] ?? ''),
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Selección', 'url' => route('aitg.seleccion.index')],
            ['label' => 'Candidatos', 'url' => route('aitg.seleccion.candidatos', $convocatoria)],
            ['label' => 'Reporte general', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        <div class="mb-3 d-flex flex-wrap">
            <a href="{{ route('aitg.seleccion.candidatos', $convocatoria) }}" class="btn btn-outline-secondary btn-sm mr-2 mb-1">
                <i class="fas fa-arrow-left"></i> Volver a candidatos
            </a>
            <a href="{{ route('aitg.seleccion.reporte.general.pdf', $convocatoria) }}" class="btn btn-danger btn-sm mr-2 mb-1" data-aitg-spa-ignore="1">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('aitg.seleccion.reporte.general.excel', $convocatoria) }}" class="btn btn-success btn-sm mb-1" data-aitg-spa-ignore="1">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>

        <div class="aitg-card aitg-card--primary mb-3">
            <div class="aitg-card__body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Convocatoria:</strong> {{ $reporte['convocatoria']['titulo'] }}</p>
                        <p class="mb-1"><strong>Código:</strong> {{ $reporte['convocatoria']['codigo'] ?? '—' }}</p>
                        <p class="mb-1"><strong>Competencia:</strong> {{ $reporte['convocatoria']['competencia'] }}</p>
                        <p class="mb-0"><strong>Regional:</strong> {{ $reporte['convocatoria']['regional'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Estado:</strong> {{ $reporte['convocatoria']['estado_label'] }}</p>
                        <p class="mb-1"><strong>Generado:</strong> {{ $reporte['generado_en']->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Total candidatos:</strong> {{ $reporte['resumen']['total_candidatos'] }}</p>
                        <p class="mb-0">
                            <span class="badge badge-success">Seleccionados: {{ $reporte['resumen']['seleccionados'] }}</span>
                            <span class="badge badge-info">Suplentes: {{ $reporte['resumen']['suplentes'] }}</span>
                            <span class="badge badge-secondary">No seleccionados: {{ $reporte['resumen']['no_seleccionados'] }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @forelse($reporte['candidatos'] as $candidato)
            <div class="aitg-card aitg-card--primary mb-3">
                <div class="aitg-card__header d-flex justify-content-between align-items-center">
                    <h3 class="h6 mb-0">
                        #{{ $candidato['posicion'] }} — {{ $candidato['nombre'] }}
                        <small class="text-muted">({{ $candidato['documento'] }})</small>
                    </h3>
                    <div>
                        @php
                            $badge = match($candidato['resultado_clave']) {
                                'seleccionado' => 'success',
                                'suplente' => 'info',
                                'pendiente_seleccion' => 'warning',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ $candidato['resultado'] }}</span>
                        <a href="{{ route('aitg.seleccion.reporte.individual', [$convocatoria, $candidato['postulacion_id']]) }}" class="btn btn-xs btn-outline-primary ml-1">
                            Ver individual
                        </a>
                    </div>
                </div>
                <div class="aitg-card__body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>% Checklist:</strong> {{ $candidato['puntaje_checklist'] }}</div>
                        <div class="col-md-4"><strong>Bonus:</strong> {{ $candidato['puntaje_adicionales'] }}</div>
                        <div class="col-md-4"><strong>Total ranking:</strong> {{ $candidato['puntaje_total'] }}</div>
                    </div>
                    <p class="mb-2"><strong>Justificación:</strong></p>
                    <p class="text-justify mb-0">{{ $candidato['justificacion'] }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">No hay candidatos con evaluación para reportar en esta convocatoria.</div>
        @endforelse
    </div>
</section>
@endsection
