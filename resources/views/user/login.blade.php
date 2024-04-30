@extends('layout.master-layout-registro')
@section('content')

    <body class="hold-transition login-page">
        <div class="login-box">

            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    {{-- Bienvenida al login --}}
                    <a href="{{ route('login') }}" class="h1"><b>Bienvenido al registro de asistencias SENA </b></a>
                </div>
                {{-- logo del sena --}}
                <div class="card-body ">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('dist/img/LogoSena.jpeg') }}" alt="Logo del sena"
                            style="width: 150px; height: auto;">
                    </div>
                    <br>
                    <p class="login-box-msg" ><strong>¡Para comenzar inicie sesión!</strong></p>
                    <form action="{{ route('iniciarSesion') }}" method="post">
                        @csrf
                        {{-- correo institucional --}}
                        <label for="email">Usuario</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Correo Institucional" value="{{ old('email')}}" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        {{-- contraseña --}}
                        <label for="password">Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Aquí va un número" value="{{ old('password') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            {{-- opcional boton de recordarme. mas adelante se implementara --}}
                            {{-- <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">
                                        Recordarme
                                    </label>
                                </div>
                            </div> --}}

                            <div class="col-6 ">
                                <button type="submit" class="btn btn-success btn-block">Iniciar sesión <i class="fas fa-key"></i> </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

        </div>



@endsection
