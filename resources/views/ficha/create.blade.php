@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ficha de caracterizacion</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content align-items-center">

            <div class="card">

                <div class="card-body">
                    <div class="form-group justify-content-center">
                        <form action="{{ route('fichaCaracterizacion.store') }}" method="post">
                            @csrf
                            <label for="ficha_caracterizacion" class="col-form-label">Introduzca el número de la ficha de
                                caracterizacion</label>
                            <input class="form-control" type="text" name="ficha_caracterizacion"
                                value="{{ old('name') }}" placeholder="Numero de la ficha de caracteriacion">
                            <div class="alert alert-light" role="alert">
                                En caso de no tener Ficha de Caracterizacion asignada escriba <span>0</span>
                            </div>
                            <div class="div justify-content-center">
                                <button type="submit" class="btn btn-success">Tomar Asistencia</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endsection
