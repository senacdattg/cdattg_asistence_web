@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header mt-3">
            <div class="container-fluid mt-3">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Asistecias por fecha</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Asistencias de formación
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content"></section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">Listado de Asistencias</h3>
                                </div>
                                <div class="col-md-6">
                                    <a href="" class="btn btn-primary float-right">Descargar Reporte</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="asistenciasTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Ficha</th>
                                        <th class="text-center">Instructor</th>
                                        <th class="text-center">Nombres</th>
                                        <th class="text-center">Apellidos</th>
                                        <th class="text-center">Documento</th>
                                        <th class="text-center">Entrada</th>
                                        <th class="text-center">Salida</th>
                                        <th class="text-center">Novedad</th>
                                        <th class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asistencias as $asistencia)
                                    <tr></tr>
                                        <td class="text-center">{{ $asistencia->caracterizacion->ficha->ficha }}</td>
                                        <td class="text-center">{{ $asistencia->caracterizacion->persona->primer_nombre }}</td>
                                        <td class="text-center">{{ $asistencia->nombres }}</td>
                                        <td class="text-center">{{ $asistencia->apellidos }}</td>
                                        <td class="text-center">{{ $asistencia->numero_identificacion }}</td>
                                        <td class="text-center">{{ $asistencia->hora_ingreso }}</td>
                                        <td class="text-center">{{ $asistencia->hora_salida }}</td>
                                        <td class="text-center">{{ $asistencia->novedad_salida }}</td>
                                        <td class="text-center">{{ $asistencia->created_at->format('Y-m-d') }}</td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>
    </div>
@endsection