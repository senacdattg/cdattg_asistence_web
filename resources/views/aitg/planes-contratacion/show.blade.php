@extends('aitg.layouts.spa')

@section('title', ($plan->competencia->nombre ?? 'Plan') . ' - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => $plan->competencia->nombre ?? 'Plan de contratación',
        'subtitle' => 'Vista detallada · ' . $plan->tipo_registro_perfil_label,
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Planes', 'url' => route('aitg.planes-contratacion.index'), 'icon' => 'fa-file-contract'],
            ['label' => 'Detalle', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
@php
    $catalogo = app(\App\Services\Aitg\AitgCatalogoService::class);
@endphp
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button></div>
        @endif

        <div class="mb-3 text-right">
            @can('EDITAR PLAN CONTRATACION')
                <a href="{{ route('aitg.planes-contratacion.edit', $plan) }}" class="btn btn-primary">
                    <i class="fas fa-pencil-alt"></i> Editar plan
                </a>
            @endcan
        </div>

        <div class="aitg-card aitg-card--primary mb-4">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--primary"><i class="fas fa-file-alt"></i></span>
                    <h3 class="aitg-card__title">Datos generales</h3>
                </div>
            </div>
            <div class="aitg-card__body">
                <div class="row">
                    <div class="col-md-4"><strong>Competencia:</strong> {{ $plan->competencia->nombre ?? 'N/A' }}</div>
                    <div class="col-md-2"><strong>Modalidad:</strong> {{ $plan->modalidad_label }}</div>
                    <div class="col-md-2"><strong>Regional:</strong> {{ $plan->regional->nombre ?? 'N/A' }}</div>
                    <div class="col-md-2"><strong>Período:</strong> {{ $plan->periodo }}</div>
                    <div class="col-md-2"><strong>Estado:</strong> {{ $plan->estado_label }}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4"><strong>Forma de registro:</strong> {{ $plan->tipo_registro_perfil_label }}</div>
                    <div class="col-md-4"><strong>Vigencia:</strong> {{ $plan->fecha_inicio->format('d/m/Y') }} — {{ $plan->fecha_fin->format('d/m/Y') }}</div>
                    <div class="col-md-4"><strong>Tope global:</strong> {{ $plan->tope_global ?? '—' }}</div>
                </div>
                @if($plan->observaciones)
                    <div class="mt-2"><strong>Observaciones:</strong> {{ $plan->observaciones }}</div>
                @endif
            </div>
        </div>

        <div class="aitg-card aitg-card--success mb-4">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--success"><i class="fas fa-layer-group"></i></span>
                    <h3 class="aitg-card__title">Perfiles del plan</h3>
                </div>
            </div>
            <div class="aitg-card__body">
                @forelse($plan->perfiles as $perfil)
                    <div class="card mb-3 aitg-perfil-block">
                        <div class="card-header py-2">
                            <span class="badge badge-success mr-2">{{ $perfil->consecutivo }}</span>
                            <strong>{{ $catalogo->etiquetaBloque($plan, $perfil->consecutivo, $totalPerfiles) }}</strong>
                        </div>
                        <div class="card-body py-2">
                            <p class="mb-2"><strong>Criterio:</strong> {{ $perfil->descripcion_criterio }}</p>
                            @if($perfil->descripcion_criterio_programa)
                                <p class="mb-2"><strong>Criterio (programa):</strong> {{ $perfil->descripcion_criterio_programa }}</p>
                            @endif
                            @if($perfil->incluye_experiencia)
                                <div class="row">
                                    <div class="col-md-6"><strong>Exp. relacionada:</strong> {{ $perfil->experiencia_relacionada_meses }} meses</div>
                                    <div class="col-md-6"><strong>Exp. docencia:</strong> {{ $perfil->experiencia_docencia_meses }} meses</div>
                                </div>
                            @else
                                <p class="text-muted mb-0">Sin requisito de meses de experiencia.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No hay perfiles registrados.</p>
                @endforelse
            </div>
        </div>

        <div class="aitg-card aitg-card--info mb-4">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--info"><i class="fas fa-tasks"></i></span>
                    <h3 class="aitg-card__title">Checklist</h3>
                </div>
            </div>
            <div class="aitg-card__body">
                @forelse($plan->checklist as $item)
                    <div class="card mb-3 aitg-checklist-block">
                        <div class="card-header py-2">
                            <span class="badge badge-info mr-2">{{ $item->consecutivo }}</span>
                            <strong>Checklist {{ $item->consecutivo }}</strong>
                        </div>
                        <div class="card-body py-2">
                            <p class="mb-0"><strong>Criterio:</strong> {{ $item->descripcion_criterio }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No hay ítems de checklist registrados.</p>
                @endforelse
            </div>
        </div>

        <div class="aitg-card aitg-card--warning">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--warning"><i class="fas fa-list-ul"></i></span>
                    <h3 class="aitg-card__title">Puntos adicionales</h3>
                </div>
            </div>
            <div class="aitg-card__body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr><th>N°</th><th>Punto adicional</th><th>Puntaje</th></tr>
                    </thead>
                    <tbody>
                        @forelse($plan->puntosAdicionales as $punto)
                            <tr>
                                <td>{{ $punto->consecutivo }}</td>
                                <td>{{ $punto->descripcion }}</td>
                                <td>{{ number_format($punto->puntaje_adicional, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Sin puntos adicionales.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
