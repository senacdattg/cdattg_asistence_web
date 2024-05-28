@extends('layout.master-layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/dual-listbox/css/bootstrap-duallistbox.min.css') }}">
@endsection
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Actualizar ficha de caracterización</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fichaCaracterizacion.index') }}">Fichas de
                                    caracteriazción</a></li>
                            <li class="breadcrumb-item active">Actualizar ficha de caracterización</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content align-items-center">

            <div class="card">
 <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('fichaCaracterizacion.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                        <div class="form-group justify-content-center">
                            <form action="{{ route('fichaCaracterizacion.update', $fichaCaracterizacion->id) }}"
                                method="post">
                                @csrf
                                @method('put')
                                {{-- datos de la ficha --}}
                                <div class="row">
                                    <div class="col-md-6 div-sede">
                                        <label for="ficha" class="col-form-label">Introduzca el número de la ficha de
                                            caracterizacion</label>
                                        <input class="form-control @error('ficha') is-invalid @enderror" type="text"
                                            name="ficha" value="{{ old('ficha', $fichaCaracterizacion->ficha) }}"
                                            placeholder="Numero de la ficha de caracteriacion">
                                        <div class="alert alert-light" role="alert">
                                            En caso de no tener Ficha de Caracterizacion asignada escriba <span>0</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 div-sede">
                                        <label for="nombre_curso" class="col-form-label">Introduzca el nombre del
                                            programa</label>
                                        <input class="form-control @error('nombre_curso') is-invalid @enderror"
                                            type="text" name="nombre_curso"
                                            value="{{ old('nombre_curso', $fichaCaracterizacion->nombre_curso) }}"
                                            placeholder="Nombre del programa">
                                    </div>
                                </div>
                                {{-- escoger la regional --}}

                                <div class="row">

                                    <div class="col-md-6 div-piso">
                                        <label for="regional_id">Regional</label>
                                        <select name="regional_id" id="regional_id"
                                            class="form-control @error('regional_id') is-invalid @enderror" required>
                                            <option value="" disabled selected>Seleccione una regional</option>
                                            @foreach ($regionales as $regional)
                                                <option value="{{ $regional->id }}"
                                                    @if ($fichaCaracterizacion->regional_id == $regional->id) selected @endif>
                                                    {{ $regional->regional }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-6 ">
                                        <label for="status">Estado</label>
                                        <select name="status" id="status"
                                            class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>

                                    </div>

                                </div>
                                {{-- boton asistencia --}}
                                <div class="row text-center">

                                    <div class="div text-center justify-content-center boton-asistencia">
                                        <div class="card-body text-center">
                                            <button type="submit" class="btn btn-success">Actualizar ficha de
                                                caracterización</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                    {{-- escoger instructores  --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <form action="{{ route('fichaCaracterizacion.updateinstructoresFichaCaracterizacion') }}" method="post">
                                    {{-- <form action="{{ route('tema.update', $tema->id) }}" method="post"> --}}
                                        @csrf
                                        <label for="instructores[]">Seleccione los Instructores</label>
                                        <input type="hidden" name="ficha_id" value="{{ $fichaCaracterizacion->id }}">
                                        <select multiple="multiple" id="bootstrap-duallistbox-nonselected-list_"
                                            name="instructores[]" style="height: 120px;">
                                            @forelse ($instructores as $instructor)
                                                <option value="{{ $instructor->id }}"
                                                    @if ($fichaCaracterizacion->instructores->contains($instructor->id)) selected @endif>
                                                    {{ $instructor->persona->primer_nombre }} {{ $instructor->persona->primer_apellido }}</option>
                                            @empty
                                                <!-- Manejo si no hay parámetros disponibles -->
                                                <option value="" disabled>No hay parámetros disponibles</option>
                                            @endforelse
                                        </select>

                                        <button type="submit" class="btn btn-primary">Añadir instructores</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
 <script src="{{ asset('plugins/dual-listbox/js/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script>
        var demo1 = $('select[name="instructores[]"]').bootstrapDualListbox();
    </script>
@endsection
