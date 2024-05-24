@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Editar regional</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('regional.index') }}">regionales</a>
                            </li>
                            <li class="breadcrumb-item active">Editar Regional
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('regional.update', $regional->id) }}" method="post">
                        @csrf
                        @method('put')
                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="row">
                            <div class="col-md-12">
                                <label for="regional">Nombre de la regional</label>
                                <input type="text" class="form-control @error('regional')
                                    is-invalid
                                @enderror" value="{{ old('regional', $regional->regional) }}" name="regional"  required autofocus>
                            </div>
                            <div class="col-md-12">
                                <label for="regional">Estado</label>
                                <select name="status" id="" class="form-control">
                                   <option value="1" {{ $regional->status == (1) ? 'selected' : '' ; }}>ACTIVO</option>
                                   <option value="0" {{ $regional->status == (0) ? 'selected' : '' ; }}>INACTIVO</option>
                                </select>
                            </div>
                        </div>
                        {{-- Botón de Registro --}}
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-success btn-lg">Actualizar regional</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
@endsection

