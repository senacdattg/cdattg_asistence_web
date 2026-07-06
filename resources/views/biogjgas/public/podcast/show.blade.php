@extends('biogjgas.layout.public')

@section('title', $episodio->titulo . ' | Podcast')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [
        ['label' => 'Podcast', 'url' => route('biogjgas.podcast.index')],
        ['label' => $episodio->titulo],
    ]])
    <article class="card border-0 shadow-sm"><div class="card-body p-4">
        <h1 class="h3 font-weight-bold">{{ $episodio->titulo }}</h1>
        @if ($episodio->fecha)<p class="text-muted">{{ $episodio->fecha->format('d/m/Y') }} @if($episodio->duracion)· {{ $episodio->duracion }}@endif</p>@endif
        @if ($episodio->descripcion)<p>{{ $episodio->descripcion }}</p>@endif
        @if ($episodio->invitados)<p class="small"><strong>Invitados:</strong> {{ $episodio->invitados }}</p>@endif
        @if ($episodio->audio_url)
            <audio controls class="w-100 mt-3"><source src="{{ $episodio->audio_url }}"></audio>
        @endif
    </div></article>
@endsection
