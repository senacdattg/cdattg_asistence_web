@extends('adminlte::page')

@section('title', 'Editar ' . $tituloModulo)

@section('content_header')
    <x-page-header :icon="'fas ' . $icono" :title="'Editar: ' . $tituloModulo"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => $tituloModulo, 'url' => route($rutaBase . '.index')],
            ['label' => 'Editar', 'active' => true],
        ]" />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')
            <div class="card card-outline card-primary">
                <form action="{{ route($rutaBase . '.update', $registro->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card-body">@include($formView, ['registro' => $registro])</div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Guardar cambios</button>
                        <a href="{{ route($rutaBase . '.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
