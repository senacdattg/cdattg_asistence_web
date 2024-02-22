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
                    <p class="login-box-msg">Para comenzar inicie sesión</p>
                    <form action="../../index3.html" method="post">
                        {{-- correo institucional --}}
                        <label for="Email">Correo Institucional</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="Email" placeholder="Correo Institucional" value="{{ old('Email')}}" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        {{-- contraseña --}}
                        <label for="password">Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña" value="{{ old('password') }}">
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
                                <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                            </div>

                        </div>
                    </form>


                    <p class="mb-1">
                        <a href="forgot-password.html">Olvide mi contraseña</a>
                    </p>
                    <p class="mb-0">
                        <a href="{{ route('registro') }}" class="text-center">Registrarme</a>
                    </p>
                </div>

            </div>

        </div>


        <script src="../../plugins/jquery/jquery.min.js"></script>

        <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

        <script src="../../dist/js/adminlte.min.js?v=3.2.0"></script>
    </body>
@endsection
