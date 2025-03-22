@extends('layout.master-layout')

@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la Página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ver Regional</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('regional.index') }}">Regionales</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $regional->nombre }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section class="content">
            <div class="container-fluid">
                <!-- Botón Volver -->
                <div class="mb-3">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('regional.index') }}" title="Volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <!-- Card de Detalles de Regional -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detalles de la Regional</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row">Regional:</th>
                                        <td>{{ $regional->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <th>Creado Por:</th>
                                        <td>
                                            @if ($regional->userCreated)
                                                {{ $regional->userCreated->persona->primer_nombre }}
                                                {{ $regional->userCreated->persona->primer_apellido }}
                                            @else
                                                Usuario no disponible
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Actualizado Por:</th>
                                        <td>
                                            @if ($regional->userEdited)
                                                {{ $regional->userEdited->persona->primer_nombre }}
                                                {{ $regional->userEdited->persona->primer_apellido }}
                                            @else
                                                Usuario no disponible
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Estado:</th>
                                        <td>
                                            <span class="badge badge-{{ $regional->status === 1 ? 'success' : 'danger' }}">
                                                {{ $regional->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Creado en:</th>
                                        <td>{{ $regional->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Actualizado en:</th>
                                        <td>{{ $regional->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Bloque de Acciones -->
                    <div class="card-footer text-center">
                        @can('EDITAR REGIONAL')
                            <form class="d-inline" action="{{ route('regional.cambiarEstado', $regional->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm" title="Cambiar Estado">
                                    <i class="fas fa-sync"></i> Cambiar Estado
                                </button>
                            </form>
                            <a class="btn btn-info btn-sm" href="{{ route('regional.edit', $regional->id) }}" title="Editar">
                                <i class="fas fa-pencil-alt"></i> Editar
                            </a>
                        @endcan
                        @can('ELIMINAR REGIONAL')
                            <form class="d-inline" action="{{ route('regional.destroy', $regional->id) }}" method="POST"
                                onsubmit="return confirm('¿Está seguro de eliminar esta regional?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
