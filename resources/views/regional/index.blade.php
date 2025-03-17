@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Regionales</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Regionales</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body mt-3 mb-3">
                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Regional</th>
                                <th>Estado</th>
                                <th colspan="4">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($regionales as $regional)
                                <tr>
                                    <td>{{ $loop->iteration }}</td> {{-- ✅ Usa $loop->iteration en lugar de una variable manual --}}
                                    <td>{{ $regional->regional }}</td>
                                    <td>
                                        <span class="badge badge-{{ $regional->status === 1 ? 'success' : 'danger' }}">
                                            {{ $regional->status === 1 ? 'ACTIVO' : 'INACTIVO' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @can('EDITAR REGIONAL')
                                            <form class="d-inline"
                                                action="{{ route('regional.cambiarEstado', ['regional' => $regional->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            </form>
                                        @endcan

                                        @can('VER REGIONAL')
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ route('regional.show', ['regional' => $regional->id]) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('EDITAR REGIONAL')
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('regional.edit', ['regional' => $regional->id]) }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endcan

                                        @can('ELIMINAR REGIONAL')
                                            <form class="formulario-eliminar d-inline"
                                                action="{{ route('regional.destroy', ['regional' => $regional->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="5">No hay regionales registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <div class="card-footer">
            <div class="float-right">
                @if ($regionales instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $regionales->links() }} {{-- ✅ Evita error si $regionales no es paginador --}}
                @endif
            </div>
        </div>
    </div>
@endsection
