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
                    <a class="btn btn-warning btn-sm" href="{{ route('tema.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="container">
                    <h1>tema: {{ $tema->name }}</h1>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row">Nombre:</th>
                                <td>{{ $tema->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Estado:</th>
                                <td>
                                    <span class="badge badge-{{ $tema->status === 1 ? 'success' : 'danger' }}">
                                        {{-- {{ $tema->status }} --}}
                                        @if ($tema->status === 1)
                                            ACTIVO
                                        @else
                                            INACTIVO
                                        @endif
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Creado Por:
                                </th>
                                <td>
                                    @if ($tema->userCreate)
                                        {{ $tema->userCreate->persona->primer_nombre }}
                                        {{ $tema->userCreate->persona->primer_apellido }}
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
                                    @if ($tema->userUpdate)
                                        {{ $tema->userUpdate->persona->primer_nombre }}
                                        {{ $tema->userUpdate->persona->primer_apellido }}
                                    @else
                                        Usuario no disponible
                                    @endif
                                </td>
                            <tr>
                                <th scope="row">Creado en:</th>
                                <td>{{ $tema->created_at }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Actualizado en:</th>
                                <td>{{ $tema->updated_at }}</td>
                            </tr>
                            <tr>
                        </tbody>


                    </table>
                </div>
                <div class="container">
                    <div class="content">
                        .<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">PARAMETROS</h4>
                            </div>
                            <table class="table table-striped">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                </tr>
                                @forelse ($tema->parametros as $parametro)
                                    <tr>
                                        <td>
                                            {{ $parametro->name }}

                                {{ $parametro->id }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $parametro->status === 1 ? 'success' : 'danger' }}">
                                                {{-- {{ $tema->status }} --}}
                                                @if ($parametro->status === 1)
                                                    ACTIVO
                                                    <i class="fas fa-check-circle"></i>
                                                @else
                                                    INACTIVO
                                                    <i class="fas fa-times-circle"></i>
                                                @endif

                                            </span>
                                        </td>
                                        <td>
                                            <form id="cambiarEstadoForm" class=" d-inline"
                                                action="{{ route('tema.cambiarEstadoParametro', ['parametro' => $parametro->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"><i
                                                        class="fas fa-sync"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Botones --}}
                <div class="mb-3 text-center">

                    <form id="cambiarEstadoForm" class=" d-inline"
                        action="{{ route('tema.cambiarEstado', ['tema' => $tema->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-sync"></i></button>
                    </form>
                    <a class="btn btn-info btn-sm" href="{{ route('tema.edit', ['tema' => $tema->id]) }}">
                        <i class="fas fa-pencil-alt">
                        </i>
                    </a>
                    <form action="{{ route('tema.destroy', ['tema' => $tema->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este parámetro?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </div>
            </div>
        @endsection
