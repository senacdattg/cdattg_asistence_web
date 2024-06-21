@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Permisos</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Permisos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('permiso.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o documento" value="{{ request()->input('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 20%">Nombre y apellido</th>
                                <th style="width: 30%">Número de documento</th>
                                <th style="width: 40%">Correo electrónico</th>
                                <th style="width: 50%">Estado</th>
                                <th style="width: 1%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse ($users as $user)
                                @if ($user->id != Auth::user()->id)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $user->persona->primer_nombre }} {{ $user->persona->primer_apellido }}</td>
                                        <td>{{ $user->persona->numero_documento }}</td>
                                        <td>{{ $user->persona->email }}</td>
                                        <td>
                                            <span class="badge badge-{{ $user->persona->user->status === 1 ? 'success' : 'danger' }}">
                                                @if ($user->persona->user->status === 1)
                                                    ACTIVO
                                                @else
                                                    INACTIVO
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-warning btn-sm" href="{{ route('permiso.showUserPermiso', ['user' => $user->id]) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6">No hay usuarios registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <div class="float-right">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
