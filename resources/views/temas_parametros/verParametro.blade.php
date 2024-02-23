@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ request()->path() }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('verificarLogin') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">{{ request()->path() }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ request()->path() }}</h3>
                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div> --}}
                </div>
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('parametros') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="container">
                    <h1>Parametro: {{ $parametro->name }}</h1>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row">Nombre:</th>
                                <td>{{ $parametro->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Estado:</th>
                                <td>{{ $parametro->status }}</td>
                            </tr>
                            <tr>
                                <th>
                                    Creado Por:
                                </th>
                                <td>
                                    @if ($parametro->userCreate)
                                        {{ $parametro->userCreate->primer_nombre }}
                                        {{ $parametro->userCreate->primer_apellido }}
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
                                    @if ($parametro->userUpdate)
                                        {{ $parametro->userUpdate->primer_nombre }}
                                        {{ $parametro->userUpdate->primer_apellido }}
                                    @else
                                        Usuario no disponible
                                    @endif
                                </td>
                            <tr>
                                <th scope="row">Creado en:</th>
                                <td>{{ $parametro->created_at }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Actualizado en:</th>
                                <td>{{ $parametro->updated_at }}</td>
                            </tr>
                            <tr>
                            </tbody>


                        </table>
                    </div>
                    {{-- Botones --}}
                    <div class="mb-3 text-center">

                        <a class="btn btn-success btn-sm" href="#">
                            <i class="fas fa-sync"></i>

                            cambiar estado
                        </a>
                        <a class="btn btn-info btn-sm" href="#">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Editar
                        </a>
                        <a class="btn btn-danger btn-sm" href="#">
                            <i class="fas fa-trash">
                            </i>
                            Eliminar
                        </a>
                    </div>
            @endsection
