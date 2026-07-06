@extends('biogjgas.layout.public')

@section('title', $edicion->titulo . ' | Revista Rupícola')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [
        ['label' => 'Revista Rupícola', 'url' => route('biogjgas.revista.index')],
        ['label' => $edicion->titulo],
    ]])

    <article class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <span class="badge badge-success mb-2">Vol. {{ $edicion->volumen ?? '—' }} No. {{ $edicion->numero ?? '—' }} · {{ $edicion->anio }}</span>
            <h1 class="h3 font-weight-bold">{{ $edicion->titulo }}</h1>
            @if ($edicion->issn)<p class="text-muted small">ISSN: {{ $edicion->issn }}</p>@endif
            @if ($edicion->editorial)<p>{!! nl2br(e($edicion->editorial)) !!}</p>@endif

            @if (!empty($edicion->articulos))
                <h2 class="h5 font-weight-bold mt-4">Artículos</h2>
                @foreach ($edicion->articulos as $articulo)
                    <div class="border rounded p-3 mb-3">
                        <h3 class="h6 font-weight-bold mb-1">{{ $articulo['titulo'] ?? '' }}</h3>
                        @if (!empty($articulo['autores']))<p class="small text-muted mb-1">{{ $articulo['autores'] }}</p>@endif
                        @if (!empty($articulo['resumen']))<p class="small mb-0">{{ $articulo['resumen'] }}</p>@endif
                    </div>
                @endforeach
            @endif
        </div>
    </article>
@endsection
