@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">

                <div class="card-body">
                    <div class="jumbotron">
                        <h1 class="display-4">Hola nuevamente, {{ Auth::user()->persona->cargo }}
                            <strong>{{ Auth::user()->persona->primer_nombre }}</strong>!</h1>
                        <p class="lead">Aqui podras tomar la asistencia de los aprendices en los días de formación</p>
                        <hr class="my-4">
                        <p>Recuerda que primero dedes de ingresar la ficha de caracterización y luego escanear tomar la asistencia a la hora de entrada y de salida</p>
                        @role('INSTRUCTOR')
                        <a class="btn btn-primary btn-lg" href="{{ route('fichaCaracterizacion.create') }}" role="button">Comencemos</a>
                        @endrole
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
