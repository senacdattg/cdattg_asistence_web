@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Fichas de caracterización</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Fichas de caracterización
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">

                <div class="card-body">
                    <a href="{{ route('fichaCaracterizacion.create') }}" class="btn btn-success bnt-sm-2"><i
                            class="fas fa-clipboard-list"></i></a>

                </div>
                <div class="card-body">
                    <div class="form-group justify-content-center">
                        <form action="{{ route('entradaSalida.registros') }}" method="post">
                            @csrf
                            {{-- seleccionar la ficha --}}
                            <div class="row">
                                {{-- <div class="col-md-6 div-departamento"> --}}
                                <label for="ficha_id">Ficha</label>
                                <select name="ficha_id" id="" class="form-control" required>
                                    <option value="" disabled selected>Seleccione la ficha de caracterización</option>
                                    @forelse ($fichas as $ficha)

                                        <option value="{{ $ficha->id }}">{{ $ficha->ficha }} {{ $ficha->nombre_curso }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                {{-- </div> --}}


                            </div>
                            {{-- escoger departamento y municipio --}}
                            <div class="row">
                                <div class="col-md-6 div-departamento">
                                    <label for="departamento_id">Departamento</label>
                                    <select name="departamento_id" id="departamento_id" class="form-control" required>
                                        <option value="" disabled selected>Seleccione un departamento</option>
                                    </select>
                                </div>

                                <div class="col-md-6 div-municipio">
                                    <label for="municipio_id">municipio</label>
                                    <select name="municipio_id" id="municipio_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un municipio</option>
                                    </select>
                                </div>
                            </div>
                            {{-- escoger el ambiente --}}
                            <div class="row">
                                <div class="col-md-6 div-sede">
                                    <label for="sede_id">Seleccione la sede</label>
                                    <select name="sede_id" id="sede_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona una sede</option>
                                    </select>
                                </div>

                                <div class="col-md-6 div-bloque">
                                    <label for="bloque_id">Seleccione el bloque</label>
                                    <select name="bloque_id" id="bloque_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un bloque</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Tipo de Documento y Número de Documento --}}
                            <div class="row">

                                <div class="col-md-6 div-piso">
                                    <label for="piso_id">Seleccione el piso</label>
                                    <select name="piso_id" id="piso_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un piso</option>
                                    </select>

                                </div>

                                <div class="col-md-6 div-ambiente">
                                    <label for="ambiente_id">Seleccione el ambiente</label>
                                    <select name="ambiente_id" id="ambiente_id" class="form-control" required>
                                        <option value="" disabled selected>Selecciona un ambiente</option>
                                    </select>

                                </div>

                            </div>
                            <div class="row">

                                {{-- <div class="col-md-6 div-ambiente"> --}}
                                <label for="descripcion">Describe el tema del día</label>
                                <input type="text" class="form-control" name="descripcion" required>
                                {{-- </div> --}}
                            </div>
                            {{-- boton asistencia --}}
                            <div class="row">

                                <div class="div justify-content-center boton-asistencia">

                                    {{-- <a href="{{ route('entradaSalida.registros', ['fichaCaracterizacion' => $ficha->id]) }}" class="btn btn-success btn-sm">

                                        </a> --}}
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-list-ul"></i> Tomar asistencia</button>
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
