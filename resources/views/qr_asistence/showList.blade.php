<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Aprendices</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        @if($asistencias->isEmpty())
            <div class="alert alert-warning" role="alert">
                No hay asistencias registradas.
            </div>
        @endif
        <header>
            <a href="{{ url()->previous() }}" class="btn btn-success btn-sm mr-2 mb-3">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <div class="row">
            <div class="col-md-6 d-flex align-items-center jusitfy-content-start">
                <h5>Lista de Aprendices</h5>
            </div>
            <div class="col-md-6 d-flex align-items-center justify-content-end">
                <h5>N° Ficha: {{ $ficha }}</h5>
            </div>
            </div>
        </header>
        <section style="width: 100%">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-responsive table-striped w-100" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="text-align: center;">#</th>
                                <th class="text-center" style="width: 10%; text-align: center;">Nombres</th>
                                <th class="text-center" style="width: 10%; text-align: center;">Apellidos</th>
                                <th class="text-center" style="width: 10%; text-align: center;">N° identificación</th>
                                <th class="text-center" style="width: 10%; text-align: center;">Hora ingreso</th>
                                <th class="text-center" style="width: 10%; text-align: center;">Novedad entrada</th>
                                <th class="text-center" style="width: 10%; text-align: center;">Novedad salida</th>
                                <th class="text-center" style="width: 10%; text-align: center;">Fecha de asistencia</th>

                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asistencias as $asistencia)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $asistencia->nombres }}</td>
                                    <td>{{ $asistencia->apellidos }}</td>
                                    <td>{{ $asistencia->numero_identificacion }}</td>
                                    <td>{{ $asistencia->hora_ingreso }}</td>
                                    <td>{{ $asistencia->novedad_entrada }}</td>
                                    <td>{{ $asistencia->novedad_salida }}</td>
                                    <td>{{ $asistencia->created_at }}</td>
                                    
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-primary btn-sm" style="font-size: 12px; margin-right: 2%">Novedad Entrada</button>
                                            <button class="btn btn-danger btn-sm" style="font-size: 12px; margin-right: 2%">Novedad Salida</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
           
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


