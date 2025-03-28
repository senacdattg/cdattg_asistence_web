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
                <div class="card-body"></div>
                <form action="{{ route('programa.update', $programa->id) }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="nombre_programa">Nombre del Programa</label>
                        <input type="text" name="nombre_programa" class="form-control" id="nombre_programa" required value="{{$programa->nombre}}">
                    </div>
                    <div class="form-group">
                        <label for="tipo_programa_id">Tipo programa</label>
                        <select name="tipo_programa_id" class="form-control" id="tipo_programa_id" required>
                            @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ $programa->tipo_programa_id == $tipo->id  ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="sede_id">Sede</label>
                        <select name="sede_id" class="form-control" id="sede_id" required>
                            <option value="">Seleccione una sede</option>
                            <!-- Add options dynamically from the database -->
                            @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}" {{ $programa->sede_id == $sede->id ? 'selected' : '' }}>
                                {{ $sede->sede }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>

        </div>
</div>
</section>
</div>
@endsection