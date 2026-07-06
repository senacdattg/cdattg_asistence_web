@extends('adminlte::page')

@section('title', 'Crear semillero')

@section('content_header')
    <x-page-header
        icon="fas fa-plus-circle"
        title="Nuevo semillero"
        subtitle="Registra un semillero de investigación"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => 'Semilleros', 'url' => route('biogjgas.admin.semilleros.index')],
            ['label' => 'Crear', 'active' => true],
        ]"
    />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')

            <div class="card card-outline card-success">
                <form action="{{ route('biogjgas.admin.semilleros.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @include('biogjgas.admin.semilleros.partials.form')
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Guardar</button>
                        <a href="{{ route('biogjgas.admin.semilleros.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
