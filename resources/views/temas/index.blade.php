@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Temas</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('verificarLogin') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Temas</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido principal -->
        <section class="content">
            @can('CREAR TEMA')
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Crear Tema</h5>
                    </div>
                    <div class="card-body">
                        @include('temas.create')
                    </div>
                </div>
            @endcan

            <div class="card">
                <div class="card-body">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped projects">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Parámetros</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($temas as $tema)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $tema->name }}</td>
                                            <td class="project-state">
                                                <span class="badge badge-{{ $tema->status === 1 ? 'success' : 'danger' }}">
                                                    {{ $tema->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                </span>
                                            </td>
                                            <td>
                                                @forelse ($tema->parametros as $parametro)
                                                    <span class="badge badge-info">{{ $parametro->name }}</span>
                                                @empty
                                                    <small>No hay parámetros asignados al tema {{ $tema->name }}</small>
                                                @endforelse
                                            </td>
                                            <td class="project-actions text-right">
                                                @can('EDITAR TEMA')
                                                    <form id="cambiarEstadoForm" class="d-inline"
                                                        action="{{ route('tema.cambiarEstado', ['tema' => $tema->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            title="Cambiar Estado">
                                                            <i class="fas fa-sync"></i>
                                                        </button>
                                                    </form>
                                                @endcan

                                                @can('VER TEMA')
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('tema.show', ['tema' => $tema->id]) }}" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('EDITAR TEMA')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('tema.edit', ['tema' => $tema->id]) }}" title="Editar">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endcan

                                                @can('ELIMINAR TEMA')
                                                    <form class="formulario-eliminar d-inline"
                                                        action="{{ route('tema.destroy', ['tema' => $tema->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No hay temas registrados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="card-footer">
                        <div class="float-right">
                            {{ $temas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
