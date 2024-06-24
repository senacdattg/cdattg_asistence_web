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
                        <div class="col-md-3">

                            <div class="card card-primary card-outline carne">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="{{ asset('dist/img/LogoSena.jpeg') }}" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">
                                        {{ $persona->primer_nombre }}
                                        {{ $persona->segundo_nombre }}
                                        {{ $persona->primer_apellido }}
                                        {{ $persona->segundo_apellido }}
                                    </h3>
                                    <p class="h4 text-muted text-center">Información Básica</p>

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
                        {{-- mas información --}}
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-body">
                                            @if ($persona->instructor)
                                                <caption><strong>Fichas de caracterización</strong></caption>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Nombre de curso</th>
                                                            <th scope="col">Ficha de caracterización</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>

                                                        @foreach ($persona->instructor->fichas as $fichaCaracterizacion)
                                                            <tr>
                                                                <td>{{ $i++ }}</td>
                                                                <td>
                                                                    {{ $fichaCaracterizacion->nombre_curso }}
                                                                </td>
                                                                <td>
                                                                    {{ $fichaCaracterizacion->ficha }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
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
