@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Instructores</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Instructores
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('instructor.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar por nombre o documento" value="{{ request()->input('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Nombre y apellido
                                </th>
                                <th style="width: 30%">
                                    Numero de documento
                                </th>
                                <th style="width: 40%">
                                    Correo electronico
                                </th>
                                <th style="width: 50%">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            ?>
                            @forelse ($instructores as $instructor)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        {{ $instructor->persona->primer_nombre }}
                                        {{ $instructor->persona->primer_apellido }}
                                    </td>

                                    <td>
                                        {{ $instructor->persona->numero_documento }}
                                    </td>
                                    <td>
                                        {{ $instructor->persona->email }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $instructor->persona->user->status === 1 ? 'success' : 'danger' }}">
                                            @if ($instructor->persona->user->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </td>
                                    @can('EDITAR INSTRUCTOR')
                                        <td>
                                            <form id="cambiarEstadoForm" class=" d-inline"
                                                action="{{ route('persona.cambiarEstadoUser', ['persona' => $instructor->persona->user->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"><i
                                                        class="fas fa-sync"></i></button>
                                            </form>
                                        </td>
                                    @endcan
                                    @can('VER INSTRUCTOR')
                                        <td>
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ route('instructor.show', ['instructor' => $instructor->id]) }}">
                                                <i class="fas fa-eye"></i>

                                            </a>
                                        </td>
                                    @endcan
                                    @can('EDITAR INSTRUCTOR')
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('instructor.edit', ['instructor' => $instructor->id]) }}">
                                                <i class="fas fa-pencil-alt">
                                                </i>
                                            </a>
                                        </td>
                                    @endcan
                                    @can('ELIMINAR INSTRUCTOR')
                                        <td>


                                            <form class="formulario-eliminar btn"
                                                action="{{ route('instructor.destroy', ['instructor' => $instructor->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4">No hay instructores registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <div class="card-footer">
        <div class="float-right">
            {{ $instructores->links() }}
        </div>
    </div>
@endsection
