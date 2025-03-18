@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $parametro->name }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('verificarLogin') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('parametro.index') }}">Parámetros</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $parametro->name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido principal -->
        <section class="content">
            <a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('parametro.index') }}" title="Volver">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <div class="card">
                <!-- Card Header -->
                <div class="card-header">
                    <h3 class="card-title">Editar Parámetro</h3>
                </div>
                <!-- Card Body con el formulario de edición -->
                <div class="card-body">
                    <form method="POST" action="{{ route('parametro.update', $parametro->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name" class="col-form-label">Nombre:</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $parametro->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Estado:</label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ $parametro->status == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ $parametro->status == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Actualizar
                        </button>
                        
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
