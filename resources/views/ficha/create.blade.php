@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ficha de caracterizacion</h1>
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

        <section class="content align-items-center">

            <div class="card">

                <div class="card-body">
                    <div class="form-group justify-content-center">
                        <form action="{{ route('fichaCaracterizacion.store') }}" method="post">
                            @csrf
                            {{-- escoger departamento y municipio --}}
                            <div class="row">
                               <div class="col-md-6 div-departamento">
                                    <label for="departamento_id">Departamento</label>
                                    <select name="departamento_id" id="departamento_id" class="form-control" required>
                                        <option value="" disabled selected>Seleccione un departamento</option>
                                    </select>
                                </div>

                                <div class="col-md-6 div-municipio">
                                    <label for="municipio_id">municipio</label>
                                    <select name="municipio_id" id="municipio_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un municipio</option>
                                    </select>
                                </div>
                            </div>
                            {{-- datos de la ficha --}}
                            <div class="row">
                                <div class="col-md-6 div-sede">
                                    <label for="ficha" class="col-form-label">Introduzca el número de la ficha de
                                        caracterizacion</label>
                                    <input class="form-control" type="text" name="ficha" value="{{ old('ficha') }}"
                                        placeholder="Numero de la ficha de caracteriacion">
                                    <div class="alert alert-light" role="alert">
                                        En caso de no tener Ficha de Caracterizacion asignada escriba <span>0</span>
                                    </div>
                                </div>

                                <div class="col-md-6 div-sede">
                                    <label for="nombre_curso" class="col-form-label">Introduzca el nombre del programa</label>
                                    <input class="form-control" type="text" name="nombre_curso" value="{{ old('nombre_curso') }}"
                                        placeholder="Nombre del programa">
                                </div>
                            </div>
                            {{-- escoger el ambiente --}}
                            <div class="row">
                                <div class="col-md-6 div-sede">
                                    <label for="sede_id">Seleccione la sede</label>
                                    <select name="sede_id" id="sede_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona una sede</option>
                                    </select>
                                </div>

                                <div class="col-md-6 div-bloque">
                                    <label for="bloque_id">Seleccione el bloque</label>
                                    <select name="bloque_id" id="bloque_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un bloque</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Tipo de Documento y Número de Documento --}}
                            <div class="row">

                                <div class="col-md-6 div-piso">
                                    <label for="piso_id">Seleccione el piso</label>
                                    <select name="piso_id" id="piso_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un piso</option>
                                    </select>

                                </div>

                                <div class="col-md-6 div-ambiente">
                                    <label for="ambiente_id">Seleccione el ambiente</label>
                                    <select name="ambiente_id" id="ambiente_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un ambiente</option>
                                    </select>

                                </div>

                            </div>
                            {{-- boton asistencia --}}
                            <div class="row">
                                <div class="div justify-content-center boton-asistencia">
                                    <button type="submit" class="btn btn-success">Tomar Asistencia</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/jquery-selectDinamico.js') }}"></script>
@endsection
