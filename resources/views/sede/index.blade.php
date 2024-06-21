@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sedes


                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Sedes
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">

                <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Sede
                                </th>
                                <th style="width: 30%">
                                    Direccion
                                </th>
                                <th style="width: 40%">
                                    Municipio
                                </th>
                                <th style="width: 50%">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            ?>
                            @forelse ($sedes as $sede)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        {{ $sede->sede }}
                                    </td>

                                    <td>
                                        {{ $sede->direccion }}
                                    </td>

                                    <td>
                                        {{ $sede->municipio->municipio }}
                                    </td>

                                    <td>
                                        <span class="badge badge-{{ $sede->status === 1 ? 'success' : 'danger' }}">
                                            @if ($sede->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </td>
                                    @can('EDITAR SEDE')
                                        <td>
                                            <form id="cambiarEstadoForm" class=" d-inline"
                                                action="{{ route('sede.cambiarEstado', ['sede' => $sede->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"><i
                                                        class="fas fa-sync"></i></button>
                                            </form>
                                        </td>
                                    @endcan
                                    @can('VER SEDE')
                                        <td>
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ route('sede.show', ['sede' => $sede->id]) }}">
                                                <i class="fas fa-eye"></i>

                                            </a>
                                        </td>
                                    @endcan
                                    @can('EDITAR SEDE')

                                    <td>
                                        <a class="btn btn-info btn-sm"
                                        href="{{ route('sede.edit', ['sede' => $sede->id]) }}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                    </a>
                                </td>
                                @endcan
                                @can('ELIMINAR SEDE')

                                <td>
                                    <form class="formulario-eliminar "
                                    action="{{ route('sede.destroy', ['sede' => $sede->id]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm">

                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endcan
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4">No hay sedes registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <div class="card-footer">
        <div class="float-right">
            {{ $sedes->links() }}
        </div>
    </div>
@endsection
