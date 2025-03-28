@extends('adminlte::page')

@section('content')
    <!-- Encabezado de la página -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Parámetros</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('verificarLogin') }}">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Parámetros</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content">
        @can('CREAR PARAMETRO')
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Crear Parámetro</h5>
            </div>
            <div class="card-body">
                @include('parametros.create')
            </div>
        </div>
        @endcan

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th colspan="4">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parametros as $parametro)
                        <tr class="text-center">
                            <!-- Numeración secuencial -->
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $parametro->name }}</td>
                            <td class="project-state">
                                <span class="badge badge-{{ $parametro->status === 1 ? 'success' : 'danger' }}">
                                    {{ $parametro->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                </span>
                            </td>
                            <td class="project-actions">
                                @can('EDITAR PARAMETRO')
                                <form id="cambiarEstadoForm" class="d-inline"
                                    action="{{ route('parametro.cambiarEstado', ['parametro' => $parametro->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                                @endcan
                                @can('VER PARAMETRO')
                                <a class="btn btn-warning btn-sm"
                                    href="{{ route('parametro.show', ['parametro' => $parametro->id]) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                @can('EDITAR PARAMETRO')
                                <a class="btn btn-info btn-sm"
                                    href="{{ route('parametro.edit', ['parametro' => $parametro->id]) }}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                @endcan
                                @can('ELIMINAR PARAMETRO')
                                <form class="formulario-eliminar d-inline"
                                    action="{{ route('parametro.destroy', ['parametro' => $parametro->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No hay parámetros registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="card-footer">
                <div class="float-right">
                    {{ $parametros->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection