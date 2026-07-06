@extends('adminlte::page')

@section('title', 'Crear banner')

@section('content_header')
    <x-page-header
        icon="fas fa-plus-circle"
        title="Nuevo banner"
        subtitle="Agrega un slide al carrusel del portal"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => 'Banners', 'url' => route('biogjgas.admin.banners.index')],
            ['label' => 'Crear', 'active' => true],
        ]"
    />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')

            <div class="card card-outline card-primary">
                <form action="{{ route('biogjgas.admin.banners.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @include('biogjgas.admin.banners.partials.form')
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Guardar</button>
                        <a href="{{ route('biogjgas.admin.banners.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
