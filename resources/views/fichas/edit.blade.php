
@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="card-body"></div>
                    <form action=" {{ route('ficha.update', $ficha->id) }}" method="post">
                        @csrf
                      
                        <div class="form-group">
                            <label for="nombre_programa">Número de ficha</label>
                            <input type="number" name="numero_ficha" class="form-control" id="numero_ficha" required value="{{ old('ficha', $ficha->ficha) }}">
                        
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Programa de formación</label>
                            <select name="programa_id" class="form-control" id="sede_id" required>
                                
                                @foreach($programas as $programa)
                                    <option value="{{ $programa->id }}" {{ $programa->id == old('programa_id', $ficha->programa_id) ? 'selected' : '' }}>
                                        {{ $programa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>

            </div>
        </div>
        <div class="d-flex justify-content-left"></div>
            {{-- {{ $programas->links() }} --}}
        </div>
    </section>
</div>
@endsection


