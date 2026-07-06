@extends('adminlte::page')

@section('title', 'Semilleros BIOGJGAS')

@section('content_header')
    <x-page-header
        icon="fas fa-seedling"
        title="Semilleros de investigación"
        subtitle="Gestiona el contenido de cada semillero del portal público"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => 'Semilleros', 'active' => true],
        ]"
    />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')

            <div class="card card-outline card-success shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-list mr-2"></i>Listado de semilleros</h5>
                    <a href="{{ route('biogjgas.admin.semilleros.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Nuevo semillero
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Sigla</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($semilleros as $semillero)
                                    <tr>
                                        <td>{{ $semillero->orden }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $semillero->color_identidad }}">
                                                <i class="fas {{ $semillero->icono }} mr-1"></i>{{ $semillero->sigla }}
                                            </span>
                                        </td>
                                        <td>{{ $semillero->nombre }}</td>
                                        <td>
                                            @php
                                                $badge = match ($semillero->estado_publicacion) {
                                                    'publicado' => 'success',
                                                    'archivado' => 'secondary',
                                                    default => 'warning',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $badge }}">{{ ucfirst($semillero->estado_publicacion) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('biogjgas.semilleros.show', $semillero->slug) }}"
                                                class="btn btn-outline-info btn-xs" target="_blank" title="Ver en portal">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('biogjgas.admin.semilleros.edit', $semillero) }}"
                                                class="btn btn-outline-primary btn-xs" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('biogjgas.admin.semilleros.destroy', $semillero) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('¿Eliminar este semillero?');">
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
                                        <td colspan="5" class="text-center text-muted py-4">No hay semilleros registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($semilleros->hasPages())
                    <div class="card-footer">{{ $semilleros->links() }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
