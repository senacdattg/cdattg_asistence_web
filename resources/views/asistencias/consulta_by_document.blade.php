@extends('adminlte::page')
@section('content')
        <section class="content-header mt-3">
            <div class="container-fluid mt-3">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Asistecias por Documento</h1>
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
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Seleccionar Documento</h3>
                            </div>
                            <form action="{{route('asistencia.getAttendanceByDocument')}}" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="documento">Documento</label>
                                        <select class="form-control" id="documento" name="documento">
                                            <option value="">Seleccione un documento</option>
                                            @foreach($documentos as $documento)
                                                <option value="{{ $documento->numero_identificacion }}">{{ $documento->numero_identificacion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection