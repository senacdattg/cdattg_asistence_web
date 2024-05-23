@extends('components.layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Actualizar Sede / {{ $sede->descripcion }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('sede.index') }}">Sedes</a>
                            </li>
                            <li class="breadcrumb-item active">Actualizar Sede / {{ $sede->descripcion }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('sede.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="card-body">

                    <form action="{{ route('sede.update', ['sede' => $sede->id]) }}" method="post">
                        @csrf
                        @method('put')
                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="descripcion">Nombre de la sede</label>
                                <input type="text"
                                    class="form-control @error('descripcion') is-invalid
                                    
                                @enderror"
                                    value="{{ old('descripcion', $sede->descripcion) }}" name="descripcion" placeholder=""
                                    required autofocus>
                                @error('descripcion')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="direccion">Dirección</label>
                                <input type="text"
                                    class="form-control @error('direccion') is-invalid
                                    
                                @enderror"
                                    value="{{ old('direccion', $sede->direccion) }}" name="direccion"
                                    placeholder="Direccion" required>
                                @error('direccion')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Nombres --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="departamento">Departamento</label>
                                <select name="departamento_id" class="form-control" id="departamento_id">

                                </select>

                            </div>
                            <div class="col-md-6">
                                <label for="municipio_id">Municipio</label>
                                <select name="municipio_id"
                                    class="form-control @error('municipio_id')
                                    is-invalid
                                @enderror"
                                    id="municipio_id">

                                </select>
                                @error('municipio_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                        </div>
                        {{-- Botón de Registro --}}
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg">Actualizar sede</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
{{-- <script>
    var sedeDepartamentoId = {{ $sede->municipio->departamento->id ?? 'null' }};
    var sedeMunicipioId = {{ $sede->municipio_id ?? 'null' }};
</script> --}}
    <script src="{{ asset('js/jquery-departamentosMunicipios.js') }}"></script>
@endsection
