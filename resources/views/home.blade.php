@extends('adminlte::page')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Inicio</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('verificarLogin') }}">Inicio</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="jumbotron py-3">
        @auth
            @php
                $nombreCompleto = optional(Auth::user()->persona)->nombre_completo ?: 'Usuario';
            @endphp

            @role('SUPER ADMINISTRADOR')
                <h1 class="display-4">
                    Bienvenido <strong>{{ $nombreCompleto }}</strong>!
                </h1>
                <p class="lead">Tienes acceso completo a todas las herramientas del sistema.</p>
            @elserole('ADMINISTRADOR')
                <h1 class="display-4">
                    Bienvenido <strong>{{ $nombreCompleto }}</strong>!
                </h1>
                <p class="lead">Tienes acceso a la administración del sistema. Revisa las configuraciones y reportes.</p>
            @elserole('INSTRUCTOR')
                <h1 class="display-4">
                    Hola Instructor <strong>{{ $nombreCompleto }}</strong>!
                </h1>
                <p class="lead">Recuerda tomar la asistencia y revisar tus asignaciones.</p>
                <hr class="my-4">
                <p>Para comenzar, haz clic en el siguiente botón:</p>
                <a class="btn btn-outline-secondary btn-lg" href="{{ route('fichaCaracterizacion.index') }}" role="button">
                    Comencemos
                </a>
            @else
                <h1 class="display-4">
                    Hola, <strong>{{ $nombreCompleto }}</strong>!
                </h1>
                <p class="lead">Consulta las notificaciones y actualizaciones en el sistema.</p>
            @endrole
        @else
            <h1 class="display-4">Bienvenido</h1>
            <p class="lead">
                Por favor, <a href="{{ route('iniciarSesion') }}">inicia sesión</a> para acceder al sistema.
            </p>
        @endauth
    </div>
@endsection
