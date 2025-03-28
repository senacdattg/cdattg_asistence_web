@extends('adminlte::page')
@section('css')
    <style>
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
@endsection
@section('content')

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
                    <form action="{{ route('instructor.storeImportarCSV') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="archivoCSV">Seleccionar archivo</label>
                            <input type="file" class="form-control @error('archivoCSV') is-invalid @enderror" id="archivoCSV" name="archivoCSV" >
                        </div>
                        <button type="submit" class="btn btn-primary" id="btn-importar" onclick="showSpinner()">Importar</button>
                    </form>
                </div>

                <div class="card-body">

                    <div class="alert alert-info" role="alert">
                        <p>Por favor importa el archivo CSV que contiene los datos de los instructores.</p>
                        <p>Recuerde que el archivo CSV debe tener el title, que es el nombre completo del instructor, el
                            id_personal, que es el número de documento del instructor y el correo institucional.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Spinner and Overlay -->
    <div id="overlay" style="display: none;">
        <div class="spinner-border text-success" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
@endsection
@section('script')
    <script>
    // Asegúrate de que el DOM esté completamente cargado antes de añadir los listeners
    document.addEventListener('DOMContentLoaded', (event) => {
        // Obtén el botón por su ID
        let btn_importar = document.getElementById('btn-importar');

        // Añade un evento click al botón para ejecutar la función showSpinner
        btn_importar.addEventListener('click', function() {
            showSpinner();
        });
    });

    // La función showSpinner que muestra el spinner
    function showSpinner() {
        document.getElementById('overlay').style.display = 'flex';
    }
</script>

    {{-- <script>
        let mensaje =  `Recuerde que el archivo CSV debe tener el title, que es el nombre completo del instructor, el
                            id_personal, que es el número de documento del instructor y el correo institucional.`
        document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Importante',
                    text: mensaje
                });

        })
    </script> --}}
@endsection
