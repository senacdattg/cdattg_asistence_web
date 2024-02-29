@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ request()->path() }}


                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                {{-- <a href="{{ route('home.index') }}">Inicio</a> --}}
                            </li>
                            <li class="breadcrumb-item active">{{ request()->path() }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    {{-- <h3 class="card-title">{{ request()->path() }}</h3> --}}
                    {{-- formulario de registro --}}
                    <h1>Crear Instructor</h1>
                    <form action="{{ route('persona.store') }}" method="post">
                        @csrf

                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tipo_documento">Tipo de Documento</label>
                                <select class="form-control" name="tipo_documento" autofocus>
                                    <option value="CEDULA DE CIUDADANIA">CEDULA DE CIUDADANIA</option>
                                    <option value="PASAPORTE"> PASAPORTE</option>
                                    <option value="CEDULA DE EXTRANJERIA">CEDULA DE EXTRANJERIA</option>
                                    <option value="SIN DOCUMENTO">SIN DOCUMENTO</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="numero_documento">Número de Documento</label>
                                <input type="text" class="form-control" value="{{ old('numero_documento') }}"
                                    name="numero_documento" placeholder="Número de Documento">
                            </div>
                        </div>

                        {{-- Nombres --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="primer_nombre">Primer Nombre</label>
                                <input type="text" class="form-control" value="{{ old('primer_nombre') }}"
                                    placeholder="Primer Nombre" name="primer_nombre">
                            </div>
                            <div class="col-md-6">
                                <label for="segundo_nombre">Segundo Nombre</label>
                                <input type="text" class="form-control" value="{{ old('segundo_nombre') }}"
                                    placeholder="Segundo Nombre" name="segundo_nombre">
                            </div>
                        </div>

                        {{-- Apellidos --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="primer_apellido">Primer Apellido</label>
                                <input type="text" class="form-control" value="{{ old('primer_apellido') }}"
                                    placeholder="Primer Apellido" name="primer_apellido">
                            </div>
                            <div class="col-md-6">
                                <label for="segundo_apellido">Segundo Apellido</label>
                                <input type="text" class="form-control" value="{{ old('segundo_apellido') }}"
                                    placeholder="Segundo Apellido" name="segundo_apellido">
                            </div>
                        </div>

                        {{-- Género y Fecha de Nacimiento --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="genero">Género</label>
                                <select class="form-control" name="genero">
                                    <option value="MASCULINO">MASCULINO</option>
                                    <option value="FEMENINO">FEMENINO</option>
                                    <option value="SIN DEFINIR">SIN DEFINIR</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_de_nacimiento">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" value="{{ old('fecha_de_nacimiento') }}"
                                    name="fecha_de_nacimiento" placeholder="Fecha de Nacimiento">
                            </div>
                        </div>

                        {{-- Correo Electrónico y cargo --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" class="form-control" placeholder="Correo email"
                                    value="{{ old('email') }}" name="email">
                            </div>
                            <div class="col-md-6">
                                <label for="cargo">Cargo</label>
                                <select class="form-control" name="cargo">
                                    <option value="INGENIERO">INGENIERO</option>
                                    <option value="INSTRUCTOR">INSTRUCTOR</option>
                                    <option value="TECNICO">TECNICO</option>
                                </select>
                            </div>
                        </div>

                        {{-- Botón de Registro --}}


                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg">Crear instructor</button>
                        </div>
                    </form>

                </div>
            @endsection
