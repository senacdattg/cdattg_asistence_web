@extends('biogjgas.layout.public')

@section('title', 'Semilleros | BIOGJGAS Guaviare')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [['label' => 'Semilleros']]])

    <section class="mb-4">
        <h1 class="h3 font-weight-bold mb-2">Semilleros de investigación</h1>
        <p class="text-muted">Accede al detalle de cada semillero sin necesidad de iniciar sesión.</p>
    </section>

    <div class="row">
        @forelse ($semilleros as $semillero)
            <div class="col-sm-6 col-lg-3 mb-4">
                @include('biogjgas.public.partials.semillero-card', ['semillero' => $semillero])
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No hay semilleros publicados en este momento.</div>
            </div>
        @endforelse
    </div>
@endsection
