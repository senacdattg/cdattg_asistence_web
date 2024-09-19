
@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{route('caracterizacion.index')}}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="card-body"></div>
                    <form action="{{route('caracterizacion.store')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ficha_id">Ficha de Caracterización</label>
                                    <select name="ficha_id" class="form-control" id="sede_id" required>
                                    @if(count($fichas) > 0)
                                        @foreach($fichas as $ficha)
                                            <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                        @endforeach
                                    @else
                                        <option value="">No hay fichas disponibles</option>
                                    @endif
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="programa_formacion_id">Programa de formación</label>
                                    <select name="programa_formacion_id" class="form-control" id="programa_id" required>
                                        @if(count($programas) > 0)
                                            @foreach($programas as $programa)
                                                <option value="{{ $programa->id }}">{{ $programa->nombre }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No hay programas disponibles</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instructor_persona_id">Instructor</label>
                                    <select name="instructor_persona_id" class="form-control" id="instructor_persona_id" required>
                                       
                                        @if(count($instructores) > 0)
                                            @foreach($instructores as $instructor)
                                                <option value="{{ $instructor->persona_id }}">{{ $instructor->persona->primer_nombre }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No hay instructores disponibles</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jornada_id">Jornada de formación</label>
                                    <select name="jornada_id" class="form-control" id="jornada_id" required>
                                      
                                        @if(count($jornadas) > 0)
                                            @foreach($jornadas as $jornada)
                                                <option value="{{ $jornada->id }}">{{ $jornada->jornada }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No hay jornadas disponibles</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sede_id">Sede</label>
                                    <select name="sede_id" class="form-control" id="sede_id" required>
                                      
                                        @if(count($sedes) > 0)
                                            @foreach($sedes as $sede)
                                                <option value="{{ $sede->id }}">{{ $sede->sede }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No hay sedes disponibles</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection


