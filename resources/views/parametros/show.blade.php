@extends('adminlte::page')
@section('content')
        <!-- Encabezado de la página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $parametro->name }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('verificarLogin') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('parametro.index') }}">Parámetros</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $parametro->name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido principal -->
        <section class="content">
            <a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('parametro.index') }}">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <div class="card">
                <!-- Card Header -->
                <div class="card-header">
                    <h3 class="card-title ml-3">Detalle del Parámetro</h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive table-striped">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $parametro->name }}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="badge badge-{{ $parametro->status === 1 ? 'success' : 'danger' }}">
                                            {{ $parametro->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Creado Por:</th>
                                    <td>
                                        @if ($parametro->userCreate)
                                            {{ $parametro->userCreate->persona->primer_nombre }}
                                            {{ $parametro->userCreate->persona->primer_apellido }}
                                        @else
                                            Usuario no disponible
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Actualizado Por:</th>
                                    <td>
                                        @if ($parametro->userUpdate)
                                            {{ $parametro->userUpdate->persona->primer_nombre }}
                                            {{ $parametro->userUpdate->persona->primer_apellido }}
                                        @else
                                            Usuario no disponible
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Creado en:</th>
                                    <td>{{ $parametro->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Actualizado en:</th>
                                    <td>{{ $parametro->updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card Footer (Acciones) -->
                <div class="card-footer text-center">
                    @can('EDITAR PARAMETRO')
                        <form id="cambiarEstadoForm" class="d-inline"
                            action="{{ route('parametro.cambiarEstado', ['parametro' => $parametro->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-sync"></i> Cambiar Estado
                            </button>
                        </form>
                        <a class="btn btn-info btn-sm" href="{{ route('parametro.edit', ['parametro' => $parametro->id]) }}">
                            <i class="fas fa-pencil-alt"></i> Editar
                        </a>
                    @endcan
                    @can('ELIMINAR PARAMETRO')
                        <form class="formulario-eliminar d-inline"
                            action="{{ route('parametro.destroy', ['parametro' => $parametro->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </section>
@endsection
