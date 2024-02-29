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
                            {{-- <li class="breadcrumb-item"><a href="{{ route('/home') }}">Inicio</a></li> --}}
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
                    <a class="btn btn-warning btn-sm" href="{{ route('persona.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                {{-- <div class="container"> --}}
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3">

                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle"
                                                src="{{ asset('dist/img/LogoSena.png') }}" alt="User profile picture">
                                        </div>
                                        <h3 class="profile-username text-center">{{ $persona->primer_nombre }}
                                            {{ $persona->segundo_nombre }}
                                            {{ $persona->primer_apellido }} {{ $persona->segundo_apellido }}</h3>
                                        <p class="text-muted text-center">{{ $persona->cargo }}</p>
                                        <h1>Informacion basica</h1>

                                        <p class="text-muted text-center">Tipo de documento:
                                            {{ $persona->tipo_documento }}</p>

                                        <p class="text-muted text-center">Numero de documento:
                                            {{ $persona->numero_documento}}</p>

                                        <p class="text-muted text-center">Fecha de nacimiento:
                                            {{ $persona->fecha_de_nacimiento }}</p>

                                        <p class="text-muted text-center">Correo: {{ $persona->email }}</p>

                                        <p class="text-muted text-center">Fecha de edad: {{ $persona->edad }}</p>

                                        <p class="text-muted text-center">Genero: {{ $persona->genero }}</p>

                                        <p class="text-muted text-center">estado: {{ $persona->user->status }}</p>
                                    </div>

                                </div>

                            </div>

                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card" style="width: 18rem;">
                                                    <div class="card-body">
                                                        <h1>Fichas</h1>
                                                        <hr>
                                                        <p>Se podria añadir las fichas aqui</p>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card" style="width: 18rem;">
                                                    <div class="card-body">
                                                        <h1>Pendientes</h1>
                                                        <hr>
                                                        <p>Aqui la ista de csv pendientes</p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            bueno, hay que considerar, talvez los vehiculos del pre-registro
                                        </div>



                                    </div>
                                </div>

                            </div>

                        </div>

                    {{-- </div> --}}
                </div>
                {{-- Botones --}}
                <div class="mb-3 text-center">

                    <a class="btn btn-info btn-sm" href="{{ route('persona.edit', ['persona' => $persona->id]) }}">
                        <i class="fas fa-pencil-alt">
                        </i>
                    </a>
                    {{-- <form id="cambiarEstadoForm" class=" d-inline"
                        action="{{ route('parametros.cambiarEstado', ['parametro' => $parametro->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-sync"></i></button>
                    </form>
                    <form action="{{ route('parametros.destroy', ['parametro' => $parametro->id]) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este parámetro?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form> --}}

                </div>
            @endsection
