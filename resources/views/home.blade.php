@extends('layout.master-layout')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('verificarLogin') }}">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body">
                    <div class="jumbotron">
                        @auth
                            <h1 class="display-4">
                                Hola nuevamente, {{ ucfirst(strtolower(Auth::user()->getRoleNames()->first())) }}
                                <strong>{{ optional(Auth::user()->persona)->primer_nombre }}</strong>!
                            </h1>

                            <p class="lead">
                                Aquí podrás tomar la asistencia de los aprendices en los días de formación.
                            </p>
                            <hr class="my-4">
                            <p>
                                Recuerda que primero debes ingresar la ficha de caracterización y luego escanear
                                para tomar la asistencia a la hora de entrada y salida.
                            </p>
                            @role('INSTRUCTOR')
                                <a class="btn btn-primary btn-lg" href="{{ route('fichaCaracterizacion.index') }}" role="button">
                                    Comencemos
                                </a>
                            @endrole
                        @else
                            <h1 class="display-4">Bienvenido</h1>
                            <p class="lead">
                                Por favor, <a href="{{ route('iniciarSesion') }}">inicia sesión</a> para acceder al sistema.
                            </p>
                        @endauth
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
