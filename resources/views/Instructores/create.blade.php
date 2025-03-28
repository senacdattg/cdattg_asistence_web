@extends('adminlte::page')
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
                            <li class="breadcrumb-item active">Crear Instructor
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <div class="card-body">
                        <a class="btn btn-warning btn-sm" href="{{ route('instructor.index') }}">
                            <i class="fas fa-arrow-left"></i>
                            </i>
                            Volver
                        </a>
                    </div>
                    @if (session('error'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('instructor.store') }}" method="post">
                        @csrf
                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="tipo_documento">Tipo de Documento</label>
                                        <select class="form-control @error('tipo_documento') is-invalid @enderror"
                                            name="tipo_documento" autofocus>
                                            <option value="" disabled selected>Seleccione un tipo de documento
                                            </option>
                                            @forelse ($documentos->parametros as $parametro)
                                                <option value="{{ $parametro->id }}">{{ $parametro->name }}</option>
                                            @empty
                                                <option value="" disabled>No existe</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="numero_documento">Número de Documento</label>
                                        <input type="text"
                                            class="form-control @error('numero_documento') is-invalid @enderror"
                                            value="{{ old('numero_documento') }}" name="numero_documento"
                                            placeholder="Número de Documento">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nombres --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="primer_nombre">Primer Nombre</label>
                                        <input type="text"
                                            class="form-control @error('primer_nombre') is-invalid @enderror"
                                            value="{{ old('primer_nombre') }}" placeholder="Primer Nombre"
                                            name="primer_nombre">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="segundo_nombre">Segundo Nombre</label>
                                        <input type="text"
                                            class="form-control @error('segundo_nombre') is-invalid @enderror"
                                            value="{{ old('segundo_nombre') }}" placeholder="Segundo Nombre"
                                            name="segundo_nombre">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="primer_apellido">Primer Apellido</label>
                                        <input type="text"
                                            class="form-control @error('primer_apellido') is-invalid @enderror"
                                            value="{{ old('primer_apellido') }}" placeholder="Primer Apellido"
                                            name="primer_apellido">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="segundo_apellido">Segundo Apellido</label>
                                        <input type="text"
                                            class="form-control @error('segundo_apellido') is-invalid @enderror"
                                            value="{{ old('segundo_apellido') }}" placeholder="Segundo Apellido"
                                            name="segundo_apellido">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Género y Fecha de Nacimiento --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="genero">Género</label>
                                        <select class="form-control @error('genero') is-invalid @enderror" name="genero">
                                            <option value="" disabled selected>Seleccione un género</option>
                                            @forelse ($generos->parametros as $parametro)
                                                <option value="{{ $parametro->id }}">{{ $parametro->name }}</option>
                                            @empty
                                                <option value="" disabled>No existe</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="fecha_de_nacimiento">Fecha de Nacimiento</label>
                                        <input type="date"
                                            class="form-control @error('fecha_de_nacimiento') is-invalid @enderror"
                                            value="{{ old('fecha_de_nacimiento') }}" name="fecha_de_nacimiento"
                                            placeholder="Fecha de Nacimiento">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Correo Electrónico --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="email">Correo Electrónico</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Correo electrónico" value="{{ old('email') }}" name="email">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Regional --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="regional_id">Regional</label>
                                        <select name="regional_id"
                                            class="form-control @error('regional_id') is-invalid @enderror">
                                            <option value="">Seleccione una regional</option>
                                            @foreach ($regionales as $regional)
                                                <option value="{{ $regional->id }}">{{ $regional->regional }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Botón de Registro --}}


                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg">Crear instructor</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
