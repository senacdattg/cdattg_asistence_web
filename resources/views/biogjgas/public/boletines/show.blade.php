@extends('biogjgas.layout.public')

@section('title', $boletin->titulo . ' | Boletines')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [
        ['label' => 'Boletines', 'url' => route('biogjgas.boletines.index')],
        ['label' => $boletin->titulo],
    ]])
    <article class="card border-0 shadow-sm"><div class="card-body p-4">
        <h1 class="h3 font-weight-bold">{{ $boletin->titulo }}</h1>
        @if ($boletin->fecha)<p class="text-muted">{{ $boletin->fecha->format('d/m/Y') }} @if($boletin->numero)· No. {{ $boletin->numero }}@endif</p>@endif
        @if ($boletin->resumen)<p>{{ $boletin->resumen }}</p>@endif
        @if ($boletin->pdf_path)<a href="{{ $boletin->pdf_path }}" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-file-pdf mr-1"></i> Descargar PDF</a>@endif
    </div></article>
@endsection
