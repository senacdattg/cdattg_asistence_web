@extends('adminlte::page')
@section('css')
@endsection
@section('content')

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $instructor->persona->primer_nombre }} {{ $instructor->persona->primer_apellido }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('instructor.index') }}">Instructores</a></li>
                            <li class="breadcrumb-item active">{{ $instructor->persona->primer_nombre }}
                                {{ $instructor->persona->primer_apellido }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">

                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('instructor.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
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
                                        {{ $instructor->persona->primer_nombre }}
                                        {{ $instructor->persona->segundo_nombre }}
                                        {{ $instructor->persona->primer_apellido }}
                                        {{ $instructor->persona->segundo_apellido }}
                                    </h3>
                                    <p class="h4 text-muted text-center">Información Básica</p>

                                    <p class="text-muted"><strong>Tipo de documento:</strong>
                                        {{ $instructor->persona->tipoDocumento->name }}</p>

                                    <p class="text-muted "><strong>Numero de documento:</strong>
                                        {{ $instructor->persona->numero_documento }}</p>

                                    <p class="text-muted "><strong>Fecha de nacimiento:</strong>
                                        {{ $instructor->persona->fecha_de_nacimiento }}</p>

                                    <p class="text-muted "><strong>Correo:</strong> {{ $instructor->persona->email }}</p>

                                    <p class="text-muted "><strong>Fecha de edad:</strong> {{ $instructor->persona->edad }}
                                    </p>

                                    <p class="text-muted "><strong>Genero:</strong>
                                        {{ $instructor->persona->tipoGenero->name }}
                                    </p>

                                    <p class="text-muted "><strong>estado:</strong>
                                        <span
                                            class="badge badge-{{ $instructor->persona->user->status === 1 ? 'success' : 'danger' }}">
                                            @if ($instructor->persona->user->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </p>
                                    <p class="text-muted"> <strong>Regional: </strong>
                                        {{ $instructor->regional->regional }}</p>
                                </div>
                            </div>

                        </div>
                        {{-- mas información --}}
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-body">
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
                                                    @foreach ($instructor->fichas as $fichaCaracterizacion)
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                {{-- Botones --}}
                <div class="mb-3 text-center">

                    @can('EDITAR INSTRUCTOR')
                        <a class="btn btn-info btn-sm"
                            href="{{ route('instructor.edit', ['instructor' => $instructor->id]) }}">
                            <i class="fas fa-pencil-alt">
                            </i>
                        </a>
                        <form id="cambiarEstadoForm" class=" d-inline"
                            action="{{ route('persona.cambiarEstadoUser', ['persona' => $instructor->persona->user->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-sync"></i></button>
                        </form>
                    @endcan
                    @can('ELIMINAR INSTRUCTOR')
                        <form class="formulario-eliminar btn"
                            action="{{ route('instructor.destroy', ['instructor' => $instructor->id]) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endcan

                </div>
            </div>
        </section>
    </div>
@endsection
