@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            Temas
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('verificarLogin') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Temas
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-body">
                    {{-- mas adelante sera implementada esta opción --}}
                {{-- @include('temas.create') --}}

                <div class="card-body p-0">
                    <table class="table table-striped projects">


                                <thead>
                                    <tr>
                                        <th style="width: 1%">
                                            #
                                        </th>
                                        <th style="width: 20%">
                                            Nombre
                                        </th>
                                        <th style="width: 10%">
                                            Estado
                                        </th>
                                        <th class="project-state" style="width: 40%">
                                            Parametros
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1
                                    ?>
                                    @forelse ($temas as $tema)
                                        <tr>
                                            <td>
                                                {{ $i++ }}
                                            </td>
                                            <td>
                                                {{ $tema->name }}
                                            </td>

                                            <td class="project-state">
                                                <span class="badge badge-{{ $tema->status === 1 ? 'success' : 'danger' }}">
                                                    {{-- {{ $tema->status }} --}}
                                                    @if ($tema->status === 1)
                                                        ACTIVO
                                                    @else
                                                        INACTIVO
                                                    @endif
                                                </span>

                                            </td>
                                            <td class="project-state">
                                                @forelse ($tema->parametros as $parametro)
                                                        <span class="badge badge-info" >{{ $parametro->name }}</span>
                                                @empty
                                                    <p>No hay parametros asignados al tema {{ $tema->name }}</p>
                                                @endforelse
                                            </td>
                                            <td class="project-actions text-right">
                                                @can('EDITAR TEMA')

                                                <form id="cambiarEstadoForm" class=" d-inline"
                                                action="{{ route('tema.cambiarEstado', ['tema' => $tema->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"><i
                                                    class="fas fa-sync"></i></button>
                                                </form>
                                                @endcan
                                                @can('VER TEMA')

                                                <a class="btn btn-warning btn-sm"
                                                href="{{ route('tema.show', ['tema' => $tema->id]) }}">
                                                <i class="fas fa-eye"></i>

                                            </a>
                                            @endcan
                                            @can('EDITAR TEMA')

                                            <a class="btn btn-info btn-sm"
                                            href="{{ route('tema.edit', ['tema' => $tema->id]) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                        </a>
                                        @endcan
                                        @can('ELIMINAR TEMA')

                                        <form class="formulario-eliminar btn" action="{{ route('tema.destroy', ['tema' => $tema->id]) }}"
                                            method="POST" class="d-inline">
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
                                        <tr>
                                            <td colspan="4">No hay temas registrados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <div class="float-right">
                                {{ $temas->links() }}
                            </div>
                        </div>

                </div>
            </div>
        </section>
    </div>
@endsection
