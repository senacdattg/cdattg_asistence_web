<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Blank Page</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <link rel="stylesheet" href="">

    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="../../dist/css/adminlte.min.css?v=3.2.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('verificarLogin') }}" class="nav-link">Inicio</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            {{-- cierre de sesion --}}
            <ul class="navbar-nav ml-auto">


                <li class="nav-item dropdown user-menu ml-auto ">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="
                        {{ asset('dist/img/LogoSena.png') }}" class="user-image img-circle elevation-2"
                            alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->persona->primer_nombre }}
                            {{ Auth::user()->persona->primer_apellido }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                        <li class="user-header bg-primary">
                            <img src="
                            {{ asset('dist/img/LogoSena.png') }}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Auth::user()->persona->primer_nombre }} {{ Auth::user()->persona->primer_apellido }}
                                {{-- <small>Member since Nov. 2012</small> --}}
                            </p>
                        </li>

                        {{-- <li class="user-body">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>

                        </li> --}}

                        <li class="user-footer">
                            <a href="{{ route('persona.show', ['persona' => Auth::user()->persona->id]) }}" class="btn btn-default btn-flat">Perfil</a>
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-right">Cerrar
                                sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>

        </nav>
