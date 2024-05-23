@extends('layout.master-layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/dual-listbox/css/bootstrap-duallistbox.min.css') }}">

    @endsection
@section('content')
    <div class="content-wrapper contenido">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $tema->name }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('tema.index') }}">Temas</a>
                            </li>
                            <li class="breadcrumb-item active">Editar
                            </li>
                            <li class="breadcrumb-item active">{{ $tema->name }}
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
                        <a class="btn btn-warning btn-sm" href="{{ route('tema.index') }}">
                            <i class="fas fa-arrow-left"></i>
                            </i>
                            Volver
                        </a>
                    </div>
                    <form method="POST" action="{{ route('tema.update', $tema->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name" class="col-form-label">Nombre:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $tema->name) }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-form-label">Estado:</label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ $tema->status ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ $tema->status ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Tema</button>
                    </form>
                </div>

                {{-- nuevo --}}
                <div class="card">
                    <div class="card-body">

                    <div class="col-sm-12">
                        <div class="form-group">
                            {{-- <form action="{{ route('temas.updateParametrosTemas') }}" method="post"> --}}
                                <form action="{{ route('tema.update', $tema->id) }}" method="post">
                                @csrf
                                <label for="parametros[]">Seleccione los parámetros</label>
                                <input type="hidden" name="tema_id" value="{{ $tema->id }}">
                                <select multiple="multiple" id="bootstrap-duallistbox-nonselected-list_" name="parametros[]"
                                    style="height: 120px;">
                                    @forelse ($parametros as $parametro)

                                        <option value="{{ $parametro->id }}" @if ($tema->parametros->contains($parametro->id))
                                        selected
                                    @endif>{{ $parametro->name }}</option>
                                    @empty
                                        <!-- Manejo si no hay parámetros disponibles -->
                                        <option value="" disabled>No hay parámetros disponibles</option>
                                    @endforelse
                                </select>
                                {{-- <select class="js-example-basic-multiple form-control parametros" name="parametros[]"
                                    multiple aria-placeholder="Seleccione los parámetros">
                                    @forelse ($parametros as $parametro)
                                        <option value="{{ $parametro->id }}">{{ $parametro->name }}</option>
                                    @empty
                                        <!-- Manejo si no hay parámetros disponibles -->
                                        <option value="" disabled>No hay parámetros disponibles</option>
                                    @endforelse
                                </select> --}}
                                <button type="submit" class="btn btn-primary">Añadir parametros</button>
                            </form>
                        </div>

                        {{-- <button type="submit" class="btn btn-primary">Enviar</button> --}}
                    </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    @endsection
    {{-- @section('scripts')
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.parametros').select2({
                width: '100%', // Establece un ancho del 100%
                placeholder: 'Selecciona los parametros', // Texto de marcador de posición
                allowClear: true, // Permite borrar la selección
                tags: true
            });
        });
    </script>
    @endsection --}}
    @section('scripts')
    <script src="{{ asset('plugins/dual-listbox/js/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script>
        var demo1 = $('select[name="parametros[]"]').bootstrapDualListbox();
        // var demo1 = $ ( 'select[name="permisos"]' ).arranqueDualListbox();
    </script>
@endsection
