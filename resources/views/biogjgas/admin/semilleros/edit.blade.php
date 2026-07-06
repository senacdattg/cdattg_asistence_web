@extends('adminlte::page')

@section('title', 'Editar semillero')

@section('content_header')
    <x-page-header
        icon="fas fa-edit"
        title="Editar semillero: {{ $semillero->sigla }}"
        subtitle="Actualiza la información del semillero"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => 'Semilleros', 'url' => route('biogjgas.admin.semilleros.index')],
            ['label' => 'Editar', 'active' => true],
        ]"
    />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')

            <div class="card card-outline card-success">
                <form action="{{ route('biogjgas.admin.semilleros.update', $semillero) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @include('biogjgas.admin.semilleros.partials.form', ['semillero' => $semillero])
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Guardar cambios</button>
                        <a href="{{ route('biogjgas.admin.semilleros.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
