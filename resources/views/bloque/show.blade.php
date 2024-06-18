@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $bloque->bloque }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bloque.index') }}">Blques</a></li>
                            <li class="breadcrumb-item active">
                                {{ $bloque->bloque }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('bloque.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="container">
                    <div class="card-body">
                        <div class="row">
                            <div class="col ">
                                <table class="table table-bordered border border-primary">
                                    <tr>
                                        <th>
                                            <strong>Bloque:</strong>
                                        </th>
                                        <td>
                                            <p>
                                                {{ $bloque->bloque }}
                                            </p>
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>
                                            <strong>Sede:</strong>
                                        </th>
                                        <td>
                                            {{ $bloque->sede->sede }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <strong>Creado por:</strong>
                                        </th>
                                        <td>
                                            {{ $bloque->userCreate->persona->primer_nombre }}
                                            {{ $bloque->userCreate->persona->primer_apellido }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <strong>Actualizado por:</strong>
                                        </th>
                                        <td>
                                            {{ $bloque->userEdit->persona->primer_nombre }}
                                            {{ $bloque->userEdit->persona->primer_apellido }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <strong>Estado:</strong>
                                        </th>
                                        <td>
                                            <span
                                                class="badge badge-{{ $bloque->status === 1 ? 'success' : 'danger' }}">
                                                @if ($bloque->status === 1)
                                                    ACTIVO
                                                @else
                                                    INACTIVO
                                                @endif
                                            </span>
                                        </td>
                                    </tr>

                                </table>
                            </div>


                        </div>
                    </div>
                </div>
                {{-- Botones --}}
                <div class="mb-3 text-center">

                    <form id="cambiarEstadoForm" class=" d-inline"
                        action="{{ route('bloque.cambiarEstado', ['bloque' => $bloque->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-sync"></i></button>
                    </form>
                    <a class="btn btn-info btn-sm"
                        href="{{ route('bloque.edit', ['bloque' => $bloque->id]) }}">
                        <i class="fas fa-pencil-alt">
                        </i>
                    </a>
                    <form class="formulario-eliminar btn"
                        action="{{ route('bloque.destroy', ['bloque' => $bloque->id]) }}"
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
