@extends('aitg.layouts.spa')

@section('title', $convocatoria->codigo . ' - Convocatoria')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => $convocatoria->titulo,
        'subtitle' => $convocatoria->codigo . ' · ' . $convocatoria->estado_label,
        'breadcrumb' => [
            ['label' => 'Convocatorias', 'url' => route('aitg.convocatorias.index'), 'icon' => 'fa-bullhorn'],
            ['label' => 'Detalle', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="mb-3">
            <a href="{{ route('aitg.convocatorias.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
            @can('EDITAR CONVOCATORIA AITG')
                <a href="{{ route('aitg.convocatorias.edit', $convocatoria) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
            @endcan
            <a href="{{ route('aitg.convocatorias.postulaciones', $convocatoria) }}" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> Ver postulaciones ({{ $convocatoria->postulaciones()->count() }})</a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="aitg-card aitg-card--primary mb-3"><div class="aitg-card__body">
                    <p><strong>Competencia:</strong> {{ $convocatoria->competencia->nombre ?? '—' }}</p>
                    <p><strong>Plan asociado:</strong> #{{ $convocatoria->plan_contratacion_id }} · {{ $convocatoria->plan->periodo ?? '' }}</p>
                    <p><strong>Objeto contractual:</strong><br>{{ $convocatoria->objeto_contractual ?: '—' }}</p>
                    <p class="mb-0"><strong>Requisitos:</strong><br>{{ $convocatoria->requisitos ?: '—' }}</p>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="aitg-card aitg-card--info mb-3"><div class="aitg-card__body">
                    <p><strong>Estado:</strong> {{ $convocatoria->estado_label }}</p>
                    <p><strong>Publicación:</strong> {{ $convocatoria->fecha_inicio_publicacion?->format('d/m/Y') ?? '—' }} – {{ $convocatoria->fecha_fin_publicacion?->format('d/m/Y') ?? '—' }}</p>
                    <p><strong>Contrato:</strong> {{ $convocatoria->fecha_inicio_contrato?->format('d/m/Y') ?? '—' }} – {{ $convocatoria->fecha_fin_contrato?->format('d/m/Y') ?? '—' }}</p>
                    <p><strong>Regional:</strong> {{ $convocatoria->regional->nombre ?? '—' }}</p>
                    <p><strong>Centro:</strong> {{ $convocatoria->centroFormacion->nombre ?? '—' }}</p>
                    <p class="mb-0"><strong>Competencia:</strong> {{ $convocatoria->competencia->nombre ?? '—' }}</p>
                </div></div>
                <div class="aitg-card aitg-card--warning"><div class="aitg-card__body">
                    <p><strong>CDP:</strong> {{ $convocatoria->codigo_cdp ?: '—' }}</p>
                    <p><strong>Valor total:</strong> ${{ number_format($convocatoria->valor_total ?? 0, 0, ',', '.') }}</p>
                    <p class="mb-0"><strong>Honorarios:</strong> ${{ number_format($convocatoria->valor_contrato_honorarios ?? 0, 0, ',', '.') }}</p>
                </div></div>
            </div>
        </div>
    </div>
</section>
@endsection
