
@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">

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
                    <form action="{{route('fichaCaracterizacion.store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="nombre_programa">Número de ficha</label>
                            <input type="number" name="numero_ficha" class="form-control" id="numero_ficha" required>
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Programa de formación</label>
                            <select name="programa_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccionar programa</option>
                                @foreach ($programas as $programa)
                                    <option value="{{$programa->id}}">
                                        {{$programa->nombre}}
                                    </option>  
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


