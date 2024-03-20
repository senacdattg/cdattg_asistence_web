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
                            <li class="breadcrumb-item"><a href="{{ route('verificarLogin') }}">Inicio</a></li>
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
                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div> --}}
                    <div class="card-body">
                        <a class="btn btn-warning btn-sm" href="{{ route('parametro.index') }}">
                            <i class="fas fa-arrow-left"></i>
                            </i>
                            Volver
                        </a>
                    </div>
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
                                <option value="1" {{ $parametro->status ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !$parametro->status ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Parámetro</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
