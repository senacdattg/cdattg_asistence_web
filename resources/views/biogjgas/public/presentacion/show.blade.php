@extends('biogjgas.layout.public')

@section('title', 'Presentación | BIOGJGAS Guaviare')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [['label' => 'Presentación']]])

    <section class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h1 class="h3 font-weight-bold mb-4">Presentación institucional</h1>
            @if ($presentacion->objetivo_general)
                <p class="lead">{{ $presentacion->objetivo_general }}</p>
            @endif
            @if ($presentacion->mision)
                <h2 class="h5 font-weight-bold">Misión</h2>
                <p>{{ $presentacion->mision }}</p>
            @endif
            @if ($presentacion->vision)
                <h2 class="h5 font-weight-bold">Visión</h2>
                <p>{{ $presentacion->vision }}</p>
            @endif
            @if ($presentacion->historia)
                <h2 class="h5 font-weight-bold">Historia</h2>
                <p>{!! nl2br(e($presentacion->historia)) !!}</p>
            @endif
            @if (!empty($presentacion->equipo))
                <h2 class="h5 font-weight-bold mt-4">Equipo directivo</h2>
                <div class="row">
                    @foreach ($presentacion->equipo as $miembro)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <strong>{{ $miembro['nombre'] ?? '' }}</strong>
                                @if (!empty($miembro['cargo']))<div class="text-muted small">{{ $miembro['cargo'] }}</div>@endif
                                @if (!empty($miembro['contacto']))<div class="small">{{ $miembro['contacto'] }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($presentacion->video_url)
                <h2 class="h5 font-weight-bold mt-4">Video institucional</h2>
                <a href="{{ $presentacion->video_url }}" class="btn btn-outline-success btn-sm" target="_blank">Ver video</a>
            @endif
        </div>
    </section>
@endsection
