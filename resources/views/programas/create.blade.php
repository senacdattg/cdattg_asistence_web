@extends('adminlte::page')
@section('content')

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <div class="card-body">
                        <a class="btn btn-warning btn-sm" href="{{ route('instructor.index') }}">
                            <i class="fas fa-arrow-left"></i>
                            </i>
                            Volver
                        </a>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card-body"></div>
                    <form action="{{ route('programa.save') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre_programa">Nombre del Programa</label>
                            <input type="text" name="nombre_programa" class="form-control" id="nombre_programa" required>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <span>el programa no debe de existir en la lista o Verifica que no contenga
                                            caracteres
                                            númericos</span>
                                    @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="tipo_programa_id">Tipo programa</label>
                            <select name="tipo_programa_id" class="form-control" id="tipo_programa_id" required>
                                <option value="">Tipo de programa</option>
                                <!-- Add options dynamically from the database -->
                                @foreach ($tipos as $tipo)
                                    @if ($tipo !== null)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Sede</label>
                            <select name="sede_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccione una sede</option>
                                <!-- Add options dynamically from the database -->
                                @foreach ($sedes as $sede)
                                    @if ($sede !== null)
                                        <option value="{{ $sede->id }}">{{ $sede->sede }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>
    </div>
    </section>
    </div>
@endsection
