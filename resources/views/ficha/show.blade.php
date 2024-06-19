@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ver Ficha
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fichaCaracterizacion.index') }}">Fichas de
                                    caracterización</a></li>
                            <li class="breadcrumb-item active">
                                Ver Ficha
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('fichaCaracterizacion.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="container">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 ">
                                <table class="table table-bordered border border-primary">
                                    <tr>
                                        <th>
                                            <strong>Nombre del curso:</strong>
                                        </th>
                                        <td>
                                            <p>
                                                @if ($fichaCaracterizacion->nombre_curso)
                                                    {{ $fichaCaracterizacion->nombre_curso }}
                                                @else
                                                    'Sin asignar nombre.'
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <strong>Ficha de caracterización: </strong>

                                        </th>
                                        <td>
                                            <p>
                                                @if ($fichaCaracterizacion->ficha)
                                                    {{ $fichaCaracterizacion->ficha }}
                                                @else
                                                    'Sin asignar ficha de caracterización.'
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <strong>Creado por:</strong>
                                        </th>
                                        <td>
                                            {{ $fichaCaracterizacion->userCreate->persona->primer_nombre }}
                                            {{ $fichaCaracterizacion->userCreate->persona->primer_apellido }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <strong>Actualizado por:</strong>
                                        </th>
                                        <td>
                                            {{ $fichaCaracterizacion->userCreate->persona->primer_nombre }}
                                            {{ $fichaCaracterizacion->userCreate->persona->primer_apellido }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <strong>Estado:</strong>
                                        </th>
                                        <td>
                                            <span
                                                class="badge badge-{{ $fichaCaracterizacion->status === 1 ? 'success' : 'danger' }}">
                                                @if ($fichaCaracterizacion->status === 1)
                                                    ACTIVO
                                                @else
                                                    INACTIVO
                                                @endif
                                            </span>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-sm-4">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Instructores asignados</strong></li>

                                        @forelse ($fichaCaracterizacion->instructores as $instructor)
                                            <li class="list-group-item">{{ $instructor->persona->primer_nombre }}
                                                {{ $instructor->persona->segundo_nombre }}
                                                {{ $instructor->persona->primer_apellido }}
                                                {{ $instructor->persona->segundo_apellido }}
                                            </li>
                                        @empty
                                            <li class="list-group-item">No hay instructores asignados
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- Botones --}}
                <div class="mb-3 text-center">

                    <form id="cambiarEstadoForm" class=" d-inline"
                        action="{{ route('fichaCaracterizacion.cambiarEstado', ['fichaCaracterizacion' => $fichaCaracterizacion->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-sync"></i></button>
                    </form>
                    <a class="btn btn-info btn-sm"
                        href="{{ route('fichaCaracterizacion.edit', ['fichaCaracterizacion' => $fichaCaracterizacion->id]) }}">
                        <i class="fas fa-pencil-alt">
                        </i>
                    </a>
                    <form class="formulario-eliminar btn"
                        action="{{ route('fichaCaracterizacion.destroy', ['fichaCaracterizacion' => $fichaCaracterizacion->id]) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </div>
            </div>
        @endsection
