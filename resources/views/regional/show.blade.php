@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

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
                                <a href="{{ route('regional.index') }}">regionales</a>
                            </li>
                            <li class="breadcrumb-item active">Ver Regional
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('regional.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="container">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Regional:</th>
                                    <td>{{ $regional->regional }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        Creado Por:
                                    </th>
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
                                    <th>
                                        Actualizado Por:
                                    </th>
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
                                            @if ($regional->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
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
                                <tr>
                            </tbody>


                        </table>
                    </div>
                </div>
                 <div class="mb-3 text-center">

                    <form id="cambiarEstadoForm" class=" d-inline"
                        action="{{ route('regional.cambiarEstado', ['regional' => $regional->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-sync"></i></button>
                    </form>
                    <a class="btn btn-info btn-sm" href="{{ route('regional.edit', ['regional' => $regional->id]) }}">
                        <i class="fas fa-pencil-alt">
                        </i>
                    </a>
                    <form class="formulario-eliminar btn" action="{{ route('regional.destroy', ['regional' => $regional->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
