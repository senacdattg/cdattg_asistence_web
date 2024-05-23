@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sede: {{ $sede->sede }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('sede.index') }}">Sedes</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $sede->sede }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('sede.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="container">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Dirección:</th>
                                    <td>{{ $sede->direccion }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Municipio:</th>
                                    <td>
                                        {{ $sede->municipio->municipio }},
                                        {{ $sede->municipio->departamentos->departamento }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Creado Por:
                                    </th>
                                    <td>
                                        @if ($sede->userCreated)
                                            {{ $sede->userCreated->persona->primer_nombre }}
                                            {{ $sede->userCreated->persona->primer_apellido }}
                                        @else
                                            Usuario no disponible
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <th>
                                        Actualizado Por:
                                    </th>
                                    <td>
                                        @if ($sede->userEdited)
                                            {{ $sede->userEdited->persona->primer_nombre }}
                                            {{ $sede->userEdited->persona->primer_apellido }}
                                        @else
                                            Usuario no disponible
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Estado:</th>
                                    <td>
                                        <span class="badge badge-{{ $sede->status === 1 ? 'success' : 'danger' }}">
                                            @if ($sede->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Creado en:</th>
                                    <td>{{ $sede->created_at }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Actualizado en:</th>
                                    <td>{{ $sede->updated_at }}</td>
                                </tr>
                                <tr>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
