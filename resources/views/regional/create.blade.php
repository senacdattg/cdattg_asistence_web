@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear regional</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('regional.index') }}">regionales</a>
                            </li>
                            <li class="breadcrumb-item active">Crear regional
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('regional.store') }}" method="post">
                        @csrf

                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="row">
                            <div class="col-md-12">
                                <label for="regional">Nombre de la regional</label>
                                <input type="text" class="form-control @error('regional')
                                    is-invalid
                                @enderror" value="{{ old('regional') }}" name="regional" placeholder="Nombre de la regional" required autofocus>
                            </div>
                        </div>
                        {{-- Botón de Registro --}}
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-success btn-lg">Crear regional</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
@endsection

