@extends('adminlte::page')

@section('title', 'Banners BIOGJGAS')

@section('content_header')
    <x-page-header
        icon="fas fa-images"
        title="Banners del portal"
        subtitle="Gestiona el carrusel de la página de investigación"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => 'Banners', 'active' => true],
        ]"
    />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')

            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-list mr-2"></i>Listado de banners</h5>
                    <a href="{{ route('biogjgas.admin.banners.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Nuevo banner
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Título</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banners as $banner)
                                    <tr>
                                        <td>{{ $banner->orden }}</td>
                                        <td>{{ $banner->titulo }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $banner->vigente_desde?->format('d/m/Y') ?? '—' }}
                                                —
                                                {{ $banner->vigente_hasta?->format('d/m/Y') ?? '—' }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $badge = match ($banner->estado_publicacion) {
                                                    'publicado' => 'success',
                                                    'archivado' => 'secondary',
                                                    default => 'warning',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $badge }}">{{ ucfirst($banner->estado_publicacion) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('biogjgas.admin.banners.edit', $banner) }}"
                                                class="btn btn-outline-primary btn-xs" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('biogjgas.admin.banners.destroy', $banner) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('¿Eliminar este banner?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-xs" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No hay banners registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
