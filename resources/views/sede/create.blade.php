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
                    <h1>Crear Sede</h1>
                    <form action="{{ route('sede.store') }}" method="post">
                        @csrf

                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="descripcion">Nombre de la sede</label>
                                <input type="text" class="form-control" value="{{ old('descripcion') }}" name="descripcion" placeholder="Nombre de la sede" required autofocus>
                            </div>
                            <div class="col-md-6">
                                <label for="direccion">Direccion</label>
                                <input type="text" class="form-control" value="{{ old('direccion') }}"
                                    name="direccion" placeholder="Direccion" required>
                            </div>
                        </div>

                        {{-- Nombres --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" class="form-control" value="{{ old('ciudad') }}"
                                    placeholder="ciudad" name="ciudad" required>
                            </div>
                        </div>
                        {{-- Botón de Registro --}}
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg">Crear sede</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
