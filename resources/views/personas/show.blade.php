@extends('layout.master-layout')
@section('css')
@endsection
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Perfil</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">Perfil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">

                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="javascript:history.back()">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                    
                </div>
                {{-- <div class="container"> --}}
                <div class="container-fluid ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline carne">
                                <div class="card-body box-profile">
                                    <h3 class="profile-username text-center">
                                        {{ $persona->primer_nombre }}
                                        {{ $persona->segundo_nombre }}
                                        {{ $persona->primer_apellido }}
                                        {{ $persona->segundo_apellido }}
                                    </h3>

                                    <p class="text-muted"><strong>Tipo de documento:</strong>
                                        {{ $persona->tipoDocumento->name }}</p>

                                    <p class="text-muted "><strong>Numero de documento:</strong>
                                        {{ $persona->numero_documento }}</p>

                                    <p class="text-muted "><strong>Fecha de nacimiento:</strong>
                                        {{ $persona->fecha_de_nacimiento }}</p>

                                    <p class="text-muted "><strong>Correo:</strong> {{ $persona->email }}</p>

                                    <p class="text-muted "><strong>Fecha de edad:</strong> {{ $persona->edad }}
                                    </p>

                                    <p class="text-muted "><strong>Genero:</strong>
                                        {{ $persona->tipoGenero->name }}
                                    </p>

                                    <p class="text-muted "><strong>estado:</strong>
                                        <span
                                            class="badge badge-{{ $persona->user->status === 1 ? 'success' : 'danger' }}">
                                            @if ($persona->user->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </p>
                                    @if ($persona->instructor)
                                        <p class="text-muted"> <strong>Regional: </strong>
                                            {{ $persona->instructor->regional->regional }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                       
                       
                    </div>

                </div>
                {{-- Botones --}}
                <div class="mb-3 text-center">
                    <a class="btn btn-info btn-sm" href="{{ route('persona.edit', ['persona' => $persona->id]) }}">
                        <i class="fas fa-pencil-alt">
                        </i>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
