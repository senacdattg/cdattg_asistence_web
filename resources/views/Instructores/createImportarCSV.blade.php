@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear Instructor</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('instructor.index') }}">Instructores</a>
                            </li>
                            <li class="breadcrumb-item active">Importar CSV
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <a class="btn btn-warning btn-sm" href="{{ route('instructor.index') }}">
                            <i class="fas fa-arrow-left"></i>
                            </i>
                            Volver
                        </a>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="importFile">Seleccionar archivo</label>
                            <input type="file" class="form-control" id="importFile" name="importFile" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Importar</button>
                    </form>
                    <div class="alert alert-info" role="alert">
                        <p>Por favor importa el archivo CSV que contiene los datos de los instructores.</p>
                        <p>Recuerde que el archivo CSV debe tener el title, que es el nombre completo del instructor, el id_personal, que es el número de documento del instructor y el correo institucional.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
