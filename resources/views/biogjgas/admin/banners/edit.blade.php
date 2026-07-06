@extends('adminlte::page')

@section('title', 'Editar banner')

@section('content_header')
    <x-page-header
        icon="fas fa-edit"
        title="Editar banner"
        subtitle="Actualiza la información del banner"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => 'Banners', 'url' => route('biogjgas.admin.banners.index')],
            ['label' => 'Editar', 'active' => true],
        ]"
    />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')

            <div class="card card-outline card-primary">
                <form action="{{ route('biogjgas.admin.banners.update', $banner) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @include('biogjgas.admin.banners.partials.form', ['banner' => $banner])
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Guardar cambios</button>
                        <a href="{{ route('biogjgas.admin.banners.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
