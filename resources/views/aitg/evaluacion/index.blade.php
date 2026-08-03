@extends('aitg.layouts.spa')

@section('title', 'Evaluación - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Evaluación y Selección',
        'subtitle' => 'Flujo unificado: validación → evaluación → selección',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Evaluación', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="alert alert-info">
            <strong>Flujo del proceso</strong>
            <ol class="mb-0 pl-3">
                <li>Aspirante postula y carga el <strong>checklist del plan</strong> (criterios documentales).</li>
                <li><strong>Validar solicitudes</strong> aprueba documentos → estado <em>preseleccionado</em>.</li>
                <li>Aquí el comité evalúa cumple/no cumple sobre los <strong>mismos ítems del checklist</strong>.</li>
                <li>Los aprobados pasan a <strong>Selección de instructor</strong>.</li>
            </ol>
        </div>

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Convocatoria</th>
                                <th class="text-center" title="Pendientes en Validar solicitudes">Validación</th>
                                <th class="text-center" title="Listos para evaluar">Evaluación</th>
                                <th class="text-center" title="Aprobados en evaluación">Selección</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($convocatorias as $convocatoria)
                                @php
                                    $pendientes = $convocatoria->cnt_pendiente_revision ?? 0;
                                    $preseleccionados = $convocatoria->cnt_preseleccionado ?? 0;
                                    $aprobados = $convocatoria->cnt_evaluacion_aprobada ?? 0;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $convocatoria->codigo ?? '—' }}</strong><br>
                                        <span class="text-muted small">{{ $convocatoria->titulo }}</span><br>
                                        <span class="badge badge-{{ $convocatoria->badgeClassEstado() }}">{{ $convocatoria->estado_label }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($pendientes > 0)
                                            <span class="badge badge-warning">{{ $pendientes }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($preseleccionados > 0)
                                            <span class="badge badge-primary">{{ $preseleccionados }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($aprobados > 0)
                                            <span class="badge badge-success">{{ $aprobados }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($pendientes > 0)
                                            <a href="{{ route('aitg.validacion-banco.index', ['estado' => 'pendiente_revision']) }}" class="btn btn-sm btn-warning mb-1">
                                                <i class="fas fa-check-double"></i> Validar primero
                                            </a><br>
                                        @endif
                                        @if($preseleccionados > 0)
                                            <a href="{{ route('aitg.evaluacion.postulaciones', $convocatoria) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-tasks"></i> Evaluar ({{ $preseleccionados }})
                                            </a>
                                        @elseif($pendientes === 0)
                                            <span class="text-muted small">Sin postulados en evaluación</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No hay convocatorias con postulaciones aún.<br>
                                        <small>El aspirante debe postular en <a href="{{ route('aitg.convocatorias.publicas.index') }}">Convocatorias públicas</a>, cargar el checklist y enviar a revisión.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $convocatorias->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
