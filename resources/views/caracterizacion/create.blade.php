@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{route('caracterizacion.index')}}">
                        <i class="fas fa-arrow-left"></i>
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
                                    <select name="ficha_id" class="form-control select2" id="ficha_id" required>
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
                                    <button type="submit" class="btn btn-primary" style="margin-top: 32px;">Seleccionar</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection