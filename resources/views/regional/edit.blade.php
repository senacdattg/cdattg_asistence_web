@extends('adminlte::page')

@section('content')
        <!-- Encabezado de la Página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $regional->nombre }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('verificarLogin') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('regional.index') }}">Regionales</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $regional->nombre }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section class="content">
            <!-- Botón Volver -->
            <a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('regional.index') }}" title="Volver">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <div class="card">
                <!-- Card Header -->
                <div class="card-header">
                    <h3 class="card-title">Editar Regional</h3>
                </div>
                <!-- Card Body con el formulario de edición -->
                <div class="card-body">
                    <form method="POST" action="{{ route('regional.update', $regional->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="regional" class="col-form-label">Nombre de la Regional:</label>
                            <input type="text" name="nombre" id="nombre"
                                class="form-control @error('regional') is-invalid @enderror"
                                value="{{ old('regional', $regional->nombre) }}" required autofocus>
                            @error('regional')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Estado:</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="1" {{ $regional->status == 1 ? 'selected' : '' }}>ACTIVO</option>
                                <option value="0" {{ $regional->status == 0 ? 'selected' : '' }}>INACTIVO</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Actualizar Regional
                        </button>

                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
