@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear ficha de caracterización</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fichaCaracterizacion.index') }}">Fichas de caracteriazción</a></li>
                            <li class="breadcrumb-item active">Crear ficha de caracterización</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content align-items-center">

            <div class="card">

                <div class="card-body">
                    <div class="form-group justify-content-center">
                        <form action="{{ route('fichaCaracterizacion.store') }}" method="post">
                            @csrf
                            {{-- datos de la ficha --}}
                            <div class="row">
                                <div class="col-md-6 div-sede">
                                    <label for="ficha" class="col-form-label">Introduzca el número de la ficha de
                                        caracterizacion</label>
                                    <input class="form-control" type="text" name="ficha" value="{{ old('ficha') }}"
                                        placeholder="Numero de la ficha de caracteriacion">
                                    <div class="alert alert-light" role="alert">
                                        En caso de no tener Ficha de Caracterizacion asignada escriba <span>0</span>
                                    </div>
                                </div>

                                <div class="col-md-6 div-sede">
                                    <label for="nombre_curso" class="col-form-label">Introduzca el nombre del programa</label>
                                    <input class="form-control" type="text" name="nombre_curso" value="{{ old('nombre_curso') }}"
                                        placeholder="Nombre del programa">
                                </div>
                            </div>
                            {{-- escoger la regional --}}

                            <div class="row">

                                <div class="col-md-6 div-piso">
                                    <label for="regional_id">Regional</label>
                                    <select name="regional_id" id="regional_id" class="form-control" required>
                                        <option value="" disabled selected>Seleccione una regional</option>
                                        @foreach ($regionales as $regional )
                                            <option value="{{ $regional->id }}">{{ $regional->regional }}</option>
                                        @endforeach
                                    </select>

                                </div>

                            </div>
                            {{-- boton asistencia --}}
                            <div class="row text-center">

                                <div class="div text-center justify-content-center boton-asistencia">
                                        <div class="card-body text-center">
                                        <button type="submit" class="btn btn-success">Crear ficha de caracterización</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/jquery-selectDinamico.js') }}"></script>
@endsection
