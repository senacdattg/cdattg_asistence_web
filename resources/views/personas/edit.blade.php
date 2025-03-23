@extends('layout.master-layout')

@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la Página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $persona->primer_nombre }} {{ $persona->primer_apellido }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('personas.show', ['persona' => $persona->id]) }}">Perfil</a></li>
                            <li class="breadcrumb-item active">Editar Perfil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section class="content">
            <div class="container-fluid">
                <!-- Botón Volver -->
                <div class="mb-3">
                    <a class="btn btn-outline-secondary btn-sm"
                        href="{{ route('personas.show', ['persona' => $persona->id]) }}" title="Volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Editar Datos Personales</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('personas.update', $persona->id) }}" method="post">
                            @csrf
                            @method('put')

                            <!-- Card: Tipo de Documento y Número -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="tipo_documento">Tipo de Documento</label>
                                            <select class="form-control @error('tipo_documento') is-invalid @enderror"
                                                name="tipo_documento" autofocus>
                                                <option value="" disabled>Seleccione un tipo de documento</option>
                                                @forelse ($documentos->parametros as $parametro)
                                                    <option value="{{ $parametro->id }}"
                                                        {{ $persona->tipo_documento == $parametro->id ? 'selected' : '' }}>
                                                        {{ $parametro->name }}
                                                    </option>
                                                @empty
                                                    <option value="" disabled>No existe</option>
                                                @endforelse
                                            </select>
                                            @error('tipo_documento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="numero_documento">Número de Documento</label>
                                            <input type="text"
                                                class="form-control @error('numero_documento') is-invalid @enderror"
                                                value="{{ old('numero_documento', $persona->numero_documento) }}"
                                                name="numero_documento" placeholder="Número de Documento">
                                            @error('numero_documento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Nombres -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="primer_nombre">Primer Nombre</label>
                                            <input type="text"
                                                class="form-control @error('primer_nombre') is-invalid @enderror"
                                                value="{{ old('primer_nombre', $persona->primer_nombre) }}"
                                                placeholder="Primer Nombre" name="primer_nombre">
                                            @error('primer_nombre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="segundo_nombre">Segundo Nombre</label>
                                            <input type="text"
                                                class="form-control @error('segundo_nombre') is-invalid @enderror"
                                                value="{{ old('segundo_nombre', $persona->segundo_nombre) }}"
                                                placeholder="Segundo Nombre" name="segundo_nombre">
                                            @error('segundo_nombre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Apellidos -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="primer_apellido">Primer Apellido</label>
                                            <input type="text"
                                                class="form-control @error('primer_apellido') is-invalid @enderror"
                                                value="{{ old('primer_apellido', $persona->primer_apellido) }}"
                                                placeholder="Primer Apellido" name="primer_apellido">
                                            @error('primer_apellido')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="segundo_apellido">Segundo Apellido</label>
                                            <input type="text"
                                                class="form-control @error('segundo_apellido') is-invalid @enderror"
                                                value="{{ old('segundo_apellido', $persona->segundo_apellido) }}"
                                                placeholder="Segundo Apellido" name="segundo_apellido">
                                            @error('segundo_apellido')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Género y Fecha de Nacimiento -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="genero">Género</label>
                                            <select class="form-control @error('genero') is-invalid @enderror"
                                                name="genero">
                                                <option value="" disabled>Seleccione un género</option>
                                                @forelse ($generos->parametros as $parametro)
                                                    <option value="{{ $parametro->id }}"
                                                        {{ $persona->genero == $parametro->id ? 'selected' : '' }}>
                                                        {{ $parametro->name }}
                                                    </option>
                                                @empty
                                                    <option value="" disabled>No existe</option>
                                                @endforelse
                                            </select>
                                            @error('genero')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                            <input type="date"
                                                class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                                value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento) }}"
                                                name="fecha_nacimiento" placeholder="Fecha de Nacimiento">
                                            @error('fecha_nacimiento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Teléfono -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text"
                                                class="form-control @error('telefono') is-invalid @enderror"
                                                value="{{ old('telefono', $persona->telefono) }}" name="telefono"
                                                placeholder="Teléfono">
                                            @error('telefono')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="celular">Celular</label>
                                            <input type="text"
                                                class="form-control @error('celular') is-invalid @enderror"
                                                value="{{ old('celular', $persona->celular) }}" name="celular"
                                                placeholder="Celular">
                                            @error('celular')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Correo Electrónico -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="email">Correo Electrónico</label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Correo electrónico"
                                                value="{{ old('email', $persona->email) }}" name="email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Actualización -->
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Actualizar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
