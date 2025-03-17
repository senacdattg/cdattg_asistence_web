@extends('layout.master-layout-registro')
@section('content')

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    {{-- Bienvenida al login --}}
                    <img src="{{ asset('dist/img/LogoSena.png') }}" alt="Logo del sena" style="width: 150px; height: auto;">
                    <h1>Bienvenido</h1>
                </div>
                {{-- logo del sena --}}
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <p class="login-box-msg"><strong>¡Para comenzar inicie sesión!</strong></p>
                    </div>
                    <form action="{{ route('iniciarSesion') }}" method="POST">
                        @csrf
                        {{-- correo institucional --}}
                        <label for="email">Usuario</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Correo Institucional"
                                value="{{ old('email') }}" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        {{-- contraseña --}}
                        <label for="password">Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña"
                                value="{{ old('password') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-6">
                                <button type="submit" class="btn btn-outline-success btn-block">Iniciar Sesión</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
