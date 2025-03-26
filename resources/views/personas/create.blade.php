@extends('adminlte::page')

@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la Página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear Persona</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('verificarLogin') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('personas.index') }}">Personas</a></li>
                            <li class="breadcrumb-item active">Crear Persona</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section class="content">
            <div class="container-fluid">
                <a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('personas.index') }}">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <div class="card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <h3 class="card-title">Formulario para Crear Persona</h3>
                    </div>
                    <!-- Card Body: Formulario -->
                    <div class="card-body">
                        <form method="post" action="{{ route('personas.store') }}">
                            @csrf
                            <div class="row">
                                <!-- Tipo de Documento -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_documento">Tipo de Documento</label>
                                        <select class="form-control @error('tipo_documento') is-invalid @enderror"
                                            name="tipo_documento" autofocus>
                                            <option value="" disabled {{ old('tipo_documento') ? '' : 'selected' }}>
                                                Seleccione un tipo de documento</option>
                                            @forelse ($documentos->parametros as $parametro)
                                                <option value="{{ $parametro->id }}"
                                                    {{ old('tipo_documento') == $parametro->id ? 'selected' : '' }}>
                                                    {{ $parametro->name }}
                                                </option>
                                            @empty
                                                <option value="" disabled>No hay tipos de documentos</option>
                                            @endforelse
                                        </select>
                                        @error('tipo_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Número de Documento -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numero_documento">Número de Documento</label>
                                        <input type="text"
                                            class="form-control @error('numero_documento') is-invalid @enderror"
                                            id="numero_documento" name="numero_documento"
                                            value="{{ old('numero_documento') }}" placeholder="Número de documento"
                                            required>
                                        @error('numero_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Primer Nombre -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="primer_nombre">Primer Nombre</label>
                                        <input type="text"
                                            class="form-control @error('primer_nombre') is-invalid @enderror"
                                            id="primer_nombre" name="primer_nombre" value="{{ old('primer_nombre') }}"
                                            placeholder="Primer nombre" required>
                                        @error('primer_nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Segundo Nombre -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="segundo_nombre">Segundo Nombre</label>
                                        <input type="text"
                                            class="form-control @error('segundo_nombre') is-invalid @enderror"
                                            id="segundo_nombre" name="segundo_nombre" value="{{ old('segundo_nombre') }}"
                                            placeholder="Segundo nombre">
                                        @error('segundo_nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Primer Apellido -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="primer_apellido">Primer Apellido</label>
                                        <input type="text"
                                            class="form-control @error('primer_apellido') is-invalid @enderror"
                                            id="primer_apellido" name="primer_apellido"
                                            value="{{ old('primer_apellido') }}" placeholder="Primer apellido" required>
                                        @error('primer_apellido')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Segundo Apellido -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="segundo_apellido">Segundo Apellido</label>
                                        <input type="text"
                                            class="form-control @error('segundo_apellido') is-invalid @enderror"
                                            id="segundo_apellido" name="segundo_apellido"
                                            value="{{ old('segundo_apellido') }}" placeholder="Segundo apellido">
                                        @error('segundo_apellido')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Género -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="genero">Género</label>
                                        <select class="form-control @error('genero') is-invalid @enderror" name="genero">
                                            <option value="" disabled {{ old('genero') ? '' : 'selected' }}>
                                                Seleccione un género</option>
                                            @forelse ($generos->parametros as $parametro)
                                                <option value="{{ $parametro->id }}"
                                                    {{ old('genero') == $parametro->id ? 'selected' : '' }}>
                                                    {{ $parametro->name }}
                                                </option>
                                            @empty
                                                <option value="" disabled>No hay géneros</option>
                                            @endforelse
                                        </select>
                                        @error('genero')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Fecha de Nacimiento -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                        <input type="date"
                                            class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                            id="fecha_nacimiento" name="fecha_nacimiento"
                                            value="{{ old('fecha_nacimiento') }}" required>
                                        @error('fecha_nacimiento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Teléfono -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                            id="telefono" name="telefono" value="{{ old('telefono') }}"
                                            placeholder="Teléfono" required>
                                        @error('telefono')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="celular">Celular</label>
                                        <input type="text" class="form-control @error('celular') is-invalid @enderror"
                                            id="celular" name="celular" value="{{ old('celular') }}"
                                            placeholder="Celular" required>
                                        @error('celular')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Correo Electrónico ocupa toda la fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Correo Electrónico</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Correo electrónico" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Crear
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
