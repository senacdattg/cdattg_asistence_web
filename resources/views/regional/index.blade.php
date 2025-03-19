@extends('layout.master-layout')

@section('content')
    <div class="content-wrapper">
        <!-- Encabezado de la página -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Regionales</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('verificarLogin') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Regionales</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido principal -->
        <section class="content">
            @can('CREAR REGIONAL')
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Crear Regional</h5>
                    </div>
                    <div class="card-body">
                        @include('regional.create')
                    </div>
                </div>
            @endcan

            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Regional</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($regionales as $regional)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $regional->regional }}</td>
                                    <td class="project-state">
                                        <span class="badge badge-{{ $regional->status === 1 ? 'success' : 'danger' }}">
                                            {{ $regional->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                        </span>
                                    </td>
                                    <td class="project-actions">
                                        @can('EDITAR REGIONAL')
                                            <form class="d-inline" action="{{ route('regional.cambiarEstado', $regional->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm" title="Cambiar Estado">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can('VER REGIONAL')
                                            <a class="btn btn-warning btn-sm" href="{{ route('regional.show', $regional->id) }}"
                                                title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('EDITAR REGIONAL')
                                            <a class="btn btn-info btn-sm" href="{{ route('regional.edit', $regional->id) }}"
                                                title="Editar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endcan
                                        @can('ELIMINAR REGIONAL')
                                            <form class="d-inline eliminar-regional-form"
                                                action="{{ route('regional.destroy', $regional->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">No hay regionales registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="card-footer">
                    <div class="float-right">
                        {{ $regionales->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.eliminar-regional-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Está seguro de eliminar la Reional?',
                        text: "¡Esta acción no se podrá revertir!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
