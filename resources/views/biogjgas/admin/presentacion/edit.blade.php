@extends('adminlte::page')

@section('title', 'Presentación institucional')

@section('content_header')
    <x-page-header icon="fas fa-building" title="Presentación institucional"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => 'Presentación', 'active' => true],
        ]" />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')
            <div class="card card-outline card-success">
                <form action="{{ route('biogjgas.admin.presentacion.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card-body">@include('biogjgas.admin.forms.presentacion', ['registro' => $presentacion])</div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Guardar</button>
                        <a href="{{ route('biogjgas.presentacion.show') }}" class="btn btn-outline-info" target="_blank">Ver en portal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
