@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear sede</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('sede.index') }}">Sedes</a>
                            </li>
                            <li class="breadcrumb-item active">Crear sede
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
                                <label for="sede">Nombre de la sede</label>
                                <input type="text" class="form-control" value="{{ old('sede') }}" name="sede" placeholder="Nombre de la sede" required autofocus>
                            </div>
                            <div class="col-md-6">
                                <label for="direccion">Direccion</label>
                                <input type="text" class="form-control" value="{{ old('direccion') }}"
                                    name="direccion" placeholder="Direccion" required>
                            </div>
                        </div>
                        {{-- departamentos y municipios --}}
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
                            <button type="submit" class="btn btn-primary btn-lg">Crear sede</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
{{-- <script>
    var sedeDepartamentoId = {{ $sede->municipio->departamento->id ?? 'null' }};
    var sedeMunicipioId = {{ $sede->municipio_id ?? 'null' }};
</script> --}}
    <script src="{{ asset('js/jquery-selectDinamico.js') }}"></script>
@endsection

