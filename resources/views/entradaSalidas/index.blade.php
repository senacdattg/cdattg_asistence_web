@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Asistencia @if ($ficha->ficha)
                                {{ $ficha->ficha }}
                            @else
                                {{ $ficha->nombre_curso }}
                            @endif
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fichaCaracterizacion.index') }}">Fichas de caracterización</a></li>
                            <li class="breadcrumb-item active">Asistencia @if ($ficha->ficha)
                                {{ $ficha->ficha }}
                            @else
                                {{ $ficha->nombre_curso }}
                            @endif</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="content">
            {{-- <div class="row">
                <div class="col">
                    @include('entradaSalidas.create', ['ficha' => $ficha->id])
                </div>
                <div class="col">
                    @include('entradaSalidas.edit')
                </div>
            </div> --}}
        </div>
        <section class="content">

            <div class="card">
                <div class="card-body">
                    {{-- boton de qr --}}
                    <div class="row justify-content-center">
                        <form action="{{ route('entradaSalida.cargarDatos') }}"  >
                            @csrf
                            <select name="evento" id="evento" class="form-control">
                                <option value="1">Entrada</option>
                                <option value="0">Salida</option>
                            </select>
                            <input type="hidden" value="{{ $ficha->id }}" name="ficha_caracterizacion_id">
                            <br>
                            <button type="submit" class="bnt btn-success btn-sm-2">
                                <i class="fas fa-qrcode"></i>
                            </button>
                        </form>
                    </div><br>

                    {{-- datos de la ficha y la fecha --}}
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="card card-body">
                                <p class="card-text">Fecha: {{ $fecha }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="card card-body">
                                <p class="card-text">Ambiente: {{ $ficha->ambiente->title }}</p>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="card card-body">
                                <p class="card-text">Ficha: {{ $ficha->ficha }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="card card-body">
                                <p class="card-text">Nombre del curso: {{ $ficha->nombre_curso }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- finaliza datos --}}
                    <div class="card-body p-0">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        #
                                    </th>
                                    <th style="width: 20%">
                                        Aprendiz
                                    </th>
                                    <th style="width: 30%">
                                        entrada
                                    </th>
                                    <th style="width: 40%">
                                        salida
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @forelse ($registros as $registro)
                                    <tr>
                                        <td>
                                            {{ $i++ }}
                                            {{-- {{ $registro->id }} --}}
                                        </td>
                                        <td>
                                            {{ $registro->aprendiz }}
                                        </td>

                                        <td>
                                            {{ $registro->entrada }}
                                        </td>
                                        <td>
                                            {{ $registro->salida }}
                                        </td>
                                        <td>
                                            <form class="formulario-eliminar btn" action="{{ route('entradaSalida.destroy', ['entradaSalida' => $registro->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">

                                            <i class="fas fa-trash"></i>
                                        </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No hay personas registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row align-self-center">
                    <div class="col align-self-center">
                        <a href="{{ route('entradaSalida.generarCSV', ['ficha' => $ficha->id]) }}" id="btn-generarCSV"
                            class="btn btn-warning btn-sm"><i class="fas fa-file-csv" style="font-size: 2em;"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var btnGenerarCSV = $('#btn-generarCSV');

            btnGenerarCSV.click(function() {
                // Simular un formulario oculto y realizar la descarga
                var iframe = $('<iframe style="display: none;"></iframe>');
                $('body').append(iframe);

                iframe.attr('src', '{{ route('entradaSalida.generarCSV', ['ficha' => $ficha]) }}');

                // Redirigir después de la descarga
                setTimeout(function() {
                    window.location.href = '{{ route('fichaCaracterizacion.index') }}';
                }, 1000); // 2000 milisegundos (2 segundos) de retraso
            });
        });
    </script>
@endsection
