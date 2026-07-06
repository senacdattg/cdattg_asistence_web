@extends('biogjgas.layout.public')

@section('title', $actividad->titulo . ' | Actividades')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [
        ['label' => 'Actividades', 'url' => route('biogjgas.actividades.index')],
        ['label' => $actividad->titulo],
    ]])
    <article class="card border-0 shadow-sm"><div class="card-body p-4">
        <span class="badge badge-info mb-2">{{ ucfirst($actividad->estado_actividad) }}</span>
        <h1 class="h3 font-weight-bold">{{ $actividad->titulo }}</h1>
        <p class="text-muted small">
            @if ($actividad->fecha){{ $actividad->fecha->format('d/m/Y') }} · @endif
            {{ $actividad->lugar ?? 'Por definir' }}
            @if ($actividad->modalidad) · {{ $actividad->modalidad }}@endif
        </p>
        @if ($actividad->semillero)<p class="small"><strong>Semillero:</strong> {{ $actividad->semillero->sigla }}</p>@endif
        @if ($actividad->descripcion)<p>{!! nl2br(e($actividad->descripcion)) !!}</p>@endif
    </div></article>
@endsection
