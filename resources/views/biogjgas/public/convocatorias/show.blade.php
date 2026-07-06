@extends('biogjgas.layout.public')

@section('title', $convocatoria->titulo . ' | Convocatorias')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [
        ['label' => 'Convocatorias', 'url' => route('biogjgas.convocatorias.index')],
        ['label' => $convocatoria->titulo],
    ]])
    <article class="card border-0 shadow-sm"><div class="card-body p-4">
        <span class="badge badge-{{ $convocatoria->estado_convocatoria === 'abierta' ? 'success' : 'secondary' }} mb-2">{{ ucfirst($convocatoria->estado_convocatoria) }}</span>
        <h1 class="h3 font-weight-bold">{{ $convocatoria->titulo }}</h1>
        @if ($convocatoria->fecha_apertura)<p class="text-muted small">Apertura: {{ $convocatoria->fecha_apertura->format('d/m/Y') }} · Cierre: {{ $convocatoria->fecha_cierre?->format('d/m/Y') ?? '—' }}</p>@endif
        @if ($convocatoria->descripcion)<p>{!! nl2br(e($convocatoria->descripcion)) !!}</p>@endif
        @if ($convocatoria->requisitos)<h2 class="h6 font-weight-bold">Requisitos</h2><p>{!! nl2br(e($convocatoria->requisitos)) !!}</p>@endif
        @if ($convocatoria->enlace_externo)<a href="{{ $convocatoria->enlace_externo }}" class="btn btn-success btn-sm" target="_blank">Postular / más información</a>@endif
    </div></article>
@endsection
