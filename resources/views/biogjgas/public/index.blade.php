@extends('biogjgas.layout.public')

@section('title', 'Investigación | BIOGJGAS Guaviare')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => []])

    <section class="biogjgas-hero rounded-lg shadow mb-4">
        <div class="container py-4 py-md-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge badge-light text-success text-uppercase px-3 py-2 mb-3 font-weight-bold">
                        Área de investigación
                    </span>
                    <h1 class="display-4 font-weight-bold mb-3">BIOGJGAS Guaviare</h1>
                    <p class="lead mb-0">
                        Consulta semilleros, divulgación científica y actividades de investigación del SENA Regional Guaviare.
                        @guest
                            <span class="d-block mt-2 small">No necesitas registrarte para explorar este contenido.</span>
                        @endguest
                    </p>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <i class="fas fa-microscope biogjgas-hero__icon"></i>
                </div>
            </div>
        </div>
    </section>

    @include('biogjgas.public.partials.banner-carousel', ['banners' => $banners])

    <section class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h4 font-weight-bold mb-0">Semilleros de investigación</h2>
            <a href="{{ route('biogjgas.semilleros.index') }}" class="btn btn-outline-success btn-sm">
                Ver todos
            </a>
        </div>
        <p class="text-muted mb-4">
            Selecciona un semillero para conocer su información, líneas y proyectos.
        </p>

        @if ($semilleros->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Próximamente publicaremos los semilleros de investigación.
            </div>
        @else
            <div class="row">
                @foreach ($semilleros as $semillero)
                    <div class="col-sm-6 col-lg-3 mb-4">
                        @include('biogjgas.public.partials.semillero-card', ['semillero' => $semillero])
                    </div>
                @endforeach
            </div>
        @endif
    <section class="mb-4">
        @include('biogjgas.public.partials.modulos-grid')
    </section>
    </section>
@endsection
