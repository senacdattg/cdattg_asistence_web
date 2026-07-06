@extends('adminlte::page')

@section('title', $tituloModulo . ' | BIOGJGAS')

@section('content_header')
    <x-page-header
        :icon="'fas ' . $icono"
        :title="$tituloModulo"
        subtitle="Gestión de contenido del módulo de investigación"
        :breadcrumb="[
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Investigación BIOGJGAS', 'url' => route('biogjgas.admin.dashboard')],
            ['label' => $tituloModulo, 'active' => true],
        ]"
    />
@endsection

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @include('components.session-alerts')
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas {{ $icono }} mr-2"></i>{{ $tituloModulo }}</h5>
                    <a href="{{ route($rutaBase . '.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Nuevo
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título / Nombre</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registros as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->titulo ?? $item->nombre ?? '—' }}</td>
                                        <td>
                                            @php $badge = match ($item->estado_publicacion) { 'publicado' => 'success', 'archivado' => 'secondary', default => 'warning' }; @endphp
                                            <span class="badge badge-{{ $badge }}">{{ ucfirst($item->estado_publicacion) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route($rutaBase . '.edit', $item->id) }}" class="btn btn-outline-primary btn-xs"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route($rutaBase . '.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar registro?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-xs"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Sin registros.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
