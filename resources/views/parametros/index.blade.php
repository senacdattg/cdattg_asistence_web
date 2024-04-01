@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ request()->path() }}


                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('verificarLogin') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">{{ request()->path() }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ request()->path() }}</h3>
                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div> --}}
                </div>
                @include('parametros.create')

                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    name
                                </th>
                                <th style="width: 30%">
                                    estado
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($parametros as $parametro)
                                <tr>
                                    <td>
                                        {{ $parametro->id }}
                                    </td>
                                    <td>
                                        {{ $parametro->name }}
                                    </td>

                                    <td class="project-state">
                                        <span class="badge badge-{{ $parametro->status === 1 ? 'success' : 'danger' }}">
                                            {{-- {{ $parametro->status }} --}}
                                            @if ($parametro->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>

                                    </td>
                                    <td class="project-actions text-right">
                                        <form id="cambiarEstadoForm" class=" d-inline"
                                            action="{{ route('parametro.cambiarEstado', ['parametro' => $parametro->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-sync"></i></button>
                                        </form>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('parametro.show', ['parametro' => $parametro->id]) }}">
                                            <i class="fas fa-eye"></i>

                                        </a>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('parametro.edit', ['parametro' => $parametro->id]) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                        </a>
                                        <form class="formulario-eliminar btn" action="{{ route('parametro.destroy', ['parametro' => $parametro->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No hay parametros registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <div class="float-right">
                        {{ $parametros->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
