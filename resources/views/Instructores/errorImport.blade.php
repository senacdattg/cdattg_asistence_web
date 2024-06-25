@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Instructores


                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Instructores
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-warning" href="{{ route('instructor.index') }}">
                        <i class="fas fa-arrow-left"></i> Volver al listado
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($errores) > 0)
                        <div class="alert alert-danger">
                            <h5>Errores encontrados:</h5>
                            <ul>
                                @foreach ($errores as $error)
                                    <li>{{ implode(', ', $error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-success">
                            Todos los registros fueron procesados exitosamente.
                        </div>
                    @endif

                    <a class="btn btn-warning" href="{{ route('instructor.index') }}">
                        <i class="fas fa-arrow-left"></i> Volver al listado
                    </a>
                </div>
            </div>
    </div>

    <div class="card-footer">
        <div class="float-right">
            {{-- {{ $errores->links() }} --}}
        </div>
    </div>
@endsection
