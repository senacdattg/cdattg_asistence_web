@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header mt-3">
        <div class="container-fluid mt-3">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Consultar Asistencias</h1>
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
                            <h3 class="card-title">Consultar Asistencias por Fichas</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('asistencia.getAttendanceByFicha')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="ficha">Número de Ficha:</label>
                                    <select name="ficha" id="ficha" class="form-control">
                                        <option value="">Seleccione una ficha</option>
                                        @foreach($fichas as $ficha)
                                        <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Consultar</button>
                            </form>
                        </div>
                    </div>
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
                            <h3 class="card-title">Consultar Asistencias por Ficha y Fecha</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('asistencia.getAttendanceByDateAndFicha')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="ficha">Número de Ficha:</label>
                                    <select name="ficha" id="ficha" class="form-control">
                                        <option value="">Seleccione una ficha</option>
                                        @foreach($fichas as $ficha)
                                            <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="fecha_inicio">Fecha Inicio:</label>
                                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fecha_fin">Fecha Fin:</label>
                                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Consultar</button>
                            </form>
                        </div>
                    </div>
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
                            <h3 class="card-title">Consultar Asistencias por Ficha y Número de Documento</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('asistencia.getDocumentsByFicha')}}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="ficha">Número de Ficha:</label>
                                        <select name="ficha" id="ficha" class="form-control">
                                            <option value="">Seleccione una ficha</option>
                                            @foreach($fichas as $ficha)
                                                <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection