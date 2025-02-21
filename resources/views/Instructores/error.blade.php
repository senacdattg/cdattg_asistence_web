@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Instructores</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Instructores
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p>
                    {{ session('success') }}
                </p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p>
                    {{ session('error') }}
                </p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($personasSinUsuario->count() > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p>
                    los instructores a continuación no tienen un usuario asociado, por favor vuelve a registralos o
                    eliminalos.
                </p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <section class="content">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 20%">
                                    Nombre y apellido
                                </th>
                                <th style="width: 30%">
                                    Numero de documento
                                </th>
                                <th style="width: 40%">
                                    Correo electronico
                                </th>
                                <th style="width: 10%">
                                    Opciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personasSinUsuario as $user): ?>
                            <tr>
                                <td>{{ $user->primer_nombre }} {{ $user->primer_apellido }}</td>
                                <td>{{ $user->numero_documento }}</td>
                                <td>{{ $user->email ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="" class="btn btn-primary mr-2">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                        <a href="{{ route('instructor.deleteWithoudUser', ['id' => $user->id]) }}"
                                            class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                    </table>
                </div>
            </div>
    </div>
@endsection
