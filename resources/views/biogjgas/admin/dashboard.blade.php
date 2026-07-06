@extends('adminlte::page')

@section('title', 'Investigación BIOGJGAS')

@section('content_header')
    <x-page-header icon="fas fa-flask" title="Investigación BIOGJGAS"
        subtitle="Administra todo el contenido del portal de investigación"
        :breadcrumb="[['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'], ['label' => 'Investigación BIOGJGAS', 'active' => true]]" />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')
            <div class="row">
                @foreach ([
                    ['perm' => 'GESTIONAR SEMILLERO BIOGJGAS', 'color' => 'success', 'icon' => 'fa-seedling', 'titulo' => 'Semilleros', 'desc' => 'Información de cada semillero.', 'route' => 'biogjgas.admin.semilleros.index'],
                    ['perm' => 'GESTIONAR BANNER BIOGJGAS', 'color' => 'primary', 'icon' => 'fa-images', 'titulo' => 'Banners', 'desc' => 'Carrusel del hub de investigación.', 'route' => 'biogjgas.admin.banners.index'],
                ] as $modulo)
                    @can($modulo['perm'])
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card card-outline card-{{ $modulo['color'] }} h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas {{ $modulo['icon'] }} mr-2"></i>{{ $modulo['titulo'] }}</h5>
                                    <p class="text-muted small">{{ $modulo['desc'] }}</p>
                                    <a href="{{ route($modulo['route']) }}" class="btn btn-{{ $modulo['color'] }} btn-sm"><i class="fas fa-list mr-1"></i> Gestionar</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endforeach
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card card-outline card-info h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-globe mr-2"></i>Portal público</h5>
                            <p class="text-muted small">Vista previa para visitantes.</p>
                            <a href="{{ route('biogjgas.home') }}" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-external-link-alt mr-1"></i> Abrir portal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
