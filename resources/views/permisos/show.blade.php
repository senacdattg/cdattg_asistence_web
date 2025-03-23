@extends('layout.master-layout')

@section('css')
    <!-- Estilos para Dual Listbox -->
    <link rel="stylesheet" href="{{ asset('plugins/dual-listbox/css/bootstrap-duallistbox.min.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la Página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Usuarios</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('permiso.index') }}">Usuarios</a>
                            </li>
                            <li class="breadcrumb-item active">Permisos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section class="content">
            <div class="container-fluid">
                <!-- Botón Volver -->
                <div class="mb-3">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('permiso.index') }}" title="Volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="row">
                    <!-- Columna Izquierda: Perfil del Usuario -->
                    <div class="col-md-3">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{ asset('dist/img/logoSena.png') }}" alt="User profile picture">
                                </div>
                                <h3 class="profile-username text-center">
                                    {{ $user->persona->nombre_completo }}
                                </h3>
                                <!-- Mostrar roles -->
                                <p class="text-muted text-center">
                                    {{ $user->getRoleNames()->implode(', ') }}
                                </p>
                                <p class="text-muted text-center">Información Básica</p>
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b><i class="fas fa-id-card"></i> Tipo de documento:</b>
                                        <span class="float-right">{{ $user->persona->tipoDocumento->name }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b><i class="fas fa-file-alt"></i> Número de documento:</b>
                                        <span class="float-right">{{ $user->persona->numero_documento }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b><i class="fas fa-birthday-cake"></i> Fecha de nacimiento:</b>
                                        <span class="float-right">{{ $user->persona->fecha_nacimiento }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b><i class="fas fa-envelope"></i> Correo:</b>
                                        <span class="float-right">{{ $user->persona->email }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b><i class="fas fa-hourglass-half"></i> Edad:</b>
                                        <span class="float-right">{{ $user->persona->edad }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b><i class="fas fa-venus-mars"></i> Género:</b>
                                        <span class="float-right">{{ $user->persona->tipoGenero->name }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b><i class="fas fa-toggle-on"></i> Estado:</b>
                                        <span
                                            class="badge badge-{{ $user->persona->user->status === 1 ? 'success' : 'danger' }} float-right">
                                            {{ $user->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                        </span>
                                    </li>
                                    @if ($user->persona->instructor && $user->persona->instructor->regional)
                                        <li class="list-group-item">
                                            <b><i class="fas fa-map-marker-alt"></i> Regional:</b>
                                            <span
                                                class="float-right">{{ $user->persona->instructor->regional->regional }}</span>
                                        </li>
                                    @endif
                                </ul>
                                @if (auth()->user()->hasAnyRole(['ADMINISTRADOR', 'SUPER ADMINISTRADOR']))
                                    <form action="{{ route('user.toggleStatus', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-primary"
                                            title="Cambiar estado de usuario">
                                            @if ($user->persona->user->status === 1)
                                                <i class="fas fa-user-slash"></i> Inactivar usuario
                                            @else
                                                <i class="fas fa-user-check"></i> Activar usuario
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Asignación de Permisos y Roles -->
                    <div class="col-md-9">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <h4 class="card-title"><i class="fas fa-key"></i> Permisos de Usuario</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('permiso.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <select multiple="multiple" name="permisos[]" class="form-control"
                                        style="height: 120px;">
                                        @forelse ($permisos as $permiso)
                                            <option value="{{ $permiso->name }}"
                                                @if ($user->hasAnyPermission([$permiso])) selected @endif>
                                                {{ $permiso->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>No hay permisos disponibles</option>
                                        @endforelse
                                    </select>
                                    <br>
                                    <button type="submit" class="btn btn-success btn-sm" title="Asignar permisos">
                                        <i class="fas fa-check"></i> Asignar permisos
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if (auth()->user()->hasRole('SUPER ADMINISTRADOR'))
                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="fas fa-user-tag"></i> Asignación de Roles</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('user.assignRoles', ['user' => $user->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH') <!-- Necesario porque la ruta usa PATCH -->
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <select multiple="multiple" name="roles[]" class="form-control"
                                            style="height: 120px;">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    @if ($user->hasRole($role->name)) selected @endif>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <br>
                                        <button type="submit" class="btn btn-success btn-sm" title="Asignar roles">
                                            <i class="fas fa-check"></i> Asignar roles
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div> <!-- Fin de row -->
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script src="{{ asset('plugins/dual-listbox/js/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('select[name="permisos[]"]').bootstrapDualListbox();
            $('select[name="roles[]"]').bootstrapDualListbox();
        });
    </script>
@endsection
