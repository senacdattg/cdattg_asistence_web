@extends('adminlte::page')

@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la Página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Permisos</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">Permisos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <!-- Buscador -->
                    <div class="card-header">
                        <form method="GET" action="{{ route('permiso.index') }}">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Buscar por nombre o documento" value="{{ request()->input('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de Usuarios -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Nombre y Apellido</th>
                                        <th>Número de Documento</th>
                                        <th>Correo Electrónico</th>
                                        <th>Roles Asignados</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        @if ($user->id != Auth::user()->id)
                                            <tr class="text-center">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->persona->nombre_completo }}</td>
                                                <td>{{ $user->persona->numero_documento }}</td>
                                                <td>{{ $user->persona->email }}</td>
                                                <td>
                                                    {{ $user->getRoleNames()->implode(', ') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $user->persona->user->status === 1 ? 'success' : 'danger' }}">
                                                        {{ $user->persona->user->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('permiso.show', ['user' => $user->id]) }}"
                                                        title="Ver Permisos">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No hay usuarios registrados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="card-footer">
                        <div class="float-right">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
