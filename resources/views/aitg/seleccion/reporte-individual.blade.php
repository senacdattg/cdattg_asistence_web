@extends('aitg.layouts.spa')

@section('title', 'Reporte individual - Selección AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Reporte individual de selección',
        'subtitle' => $reporte['candidato']['nombre'] . ' — ' . ($reporte['convocatoria']['codigo'] ?? ''),
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Selección', 'url' => route('aitg.seleccion.index')],
            ['label' => 'Candidatos', 'url' => route('aitg.seleccion.candidatos', $convocatoria)],
            ['label' => 'Reporte individual', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @php $c = $reporte['candidato']; @endphp

        <div class="mb-3 d-flex flex-wrap">
            <a href="{{ route('aitg.seleccion.candidatos', $convocatoria) }}" class="btn btn-outline-secondary btn-sm mr-2 mb-1">
                <i class="fas fa-arrow-left"></i> Volver a candidatos
            </a>
            <a href="{{ route('aitg.seleccion.reporte.general', $convocatoria) }}" class="btn btn-outline-primary btn-sm mr-2 mb-1">
                <i class="fas fa-list"></i> Reporte general
            </a>
            <a href="{{ route('aitg.seleccion.reporte.individual.pdf', [$convocatoria, $c['postulacion_id']]) }}" class="btn btn-danger btn-sm mb-1" data-aitg-spa-ignore="1">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
        </div>

        <div class="aitg-card aitg-card--primary mb-3">
            <div class="aitg-card__body">
                <div class="row">
                    <div class="col-md-7">
                        <p class="mb-1"><strong>Candidato:</strong> {{ $c['nombre'] }}</p>
                        <p class="mb-1"><strong>Documento:</strong> {{ $c['documento'] }}</p>
                        <p class="mb-1"><strong>Perfil:</strong> {{ $c['perfil'] }}</p>
                        <p class="mb-0"><strong>Convocatoria:</strong> {{ $reporte['convocatoria']['titulo'] }} ({{ $reporte['convocatoria']['codigo'] ?? '—' }})</p>
                    </div>
                    <div class="col-md-5">
                        @php
                            $badge = match($c['resultado_clave']) {
                                'seleccionado' => 'success',
                                'suplente' => 'info',
                                'pendiente_seleccion' => 'warning',
                                default => 'secondary',
                            };
                        @endphp
                        <p class="mb-1"><strong>Resultado:</strong> <span class="badge badge-{{ $badge }}">{{ $c['resultado'] }}</span></p>
                        <p class="mb-1"><strong>Posición ranking:</strong> #{{ $c['posicion'] }}</p>
                        <p class="mb-1"><strong>Puntaje total:</strong> {{ $c['puntaje_total'] }}</p>
                        <p class="mb-0"><strong>Generado:</strong> {{ $reporte['generado_en']->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="aitg-card aitg-card--primary mb-3">
            <div class="aitg-card__header"><h3 class="h6 mb-0">Justificación detallada</h3></div>
            <div class="aitg-card__body">
                <p class="text-justify mb-0">{{ $c['justificacion'] }}</p>
            </div>
        </div>

        <div class="aitg-card aitg-card--primary mb-3">
            <div class="aitg-card__header"><h3 class="h6 mb-0">Detalle de criterios evaluados</h3></div>
            <div class="aitg-card__body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Criterio</th>
                                <th>Obligatorio</th>
                                <th>Resultado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($c['criterios'] as $crit)
                                <tr>
                                    <td>{{ $crit['nombre'] }}</td>
                                    <td>{{ $crit['obligatorio'] ? 'Sí' : 'No' }}</td>
                                    <td>
                                        @if($crit['cumple'] === true)
                                            <span class="text-success">Cumple</span>
                                        @elseif($crit['cumple'] === false)
                                            <span class="text-danger">No cumple</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $crit['observaciones'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(count($c['puntos_adicionales']) > 0)
            <div class="aitg-card aitg-card--primary mb-3">
                <div class="aitg-card__header"><h3 class="h6 mb-0">Puntos adicionales</h3></div>
                <div class="aitg-card__body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th>Puntaje</th>
                                    <th>Resultado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($c['puntos_adicionales'] as $punto)
                                    <tr>
                                        <td>{{ $punto['descripcion'] }}</td>
                                        <td>+{{ number_format($punto['puntaje'], 2) }}</td>
                                        <td>
                                            @if(! $punto['tiene_evidencia'])
                                                <span class="text-muted">Sin evidencia</span>
                                            @elseif($punto['cumple'] === true)
                                                <span class="text-success">Cumple</span>
                                            @elseif($punto['cumple'] === false)
                                                <span class="text-danger">No cumple</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $punto['observaciones'] ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="aitg-card aitg-card--primary mb-3">
            <div class="aitg-card__header"><h3 class="h6 mb-0">Contexto del ranking de la convocatoria</h3></div>
            <div class="aitg-card__body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Documento</th>
                                <th>Nombre</th>
                                <th>Puntaje</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reporte['contexto_ranking'] as $row)
                                <tr @if($row['documento'] === $c['documento']) class="table-primary" @endif>
                                    <td>{{ $row['posicion'] }}</td>
                                    <td>{{ $row['documento'] }}</td>
                                    <td>{{ $row['nombre'] }}</td>
                                    <td>{{ $row['puntaje_total'] }}</td>
                                    <td>{{ $row['resultado'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
