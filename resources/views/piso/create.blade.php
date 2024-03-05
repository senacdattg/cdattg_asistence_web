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
                            <li class="breadcrumb-item">
                                {{-- <a href="{{ route('home.index') }}">Inicio</a> --}}
                            </li>
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
                    {{-- formulario de registro --}}
                    <h1>Crear piso</h1>
                    <form action="{{ route('piso.store') }}" method="post">
                        @csrf

                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="row">

                            <div class="col-md-6 div-sede">
                                <label for="sede_id">Seleccione la sede</label>
                                <select name="sede_id" id="sede_id" class="form-control" required autofocus>
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
                        <div class="row">
                            <div class="col-md-6">
                                <label for="descripcion">Descripcion</label>
                                <input type="text" class="form-control" value="{{ old('descripcion') }}"
                                    name="descripcion" placeholder="Descripion del piso" required >
                            </div>
                        </div>
                </div>


                {{-- Botón de Registro --}}
                <div class="text-center text-lg-start mt-4 pt-2">
                    <button type="submit" class="btn btn-primary btn-lg">Crear Bloque</button>
                </div>
                </form>

            </div>

    </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/jquery-selectDinamico.js') }}"></script>
@endsection
