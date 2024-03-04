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
                                {{-- <a href="{{ route('home.index') }}">Inicio</a> --}}
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

                <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Nombre
                                </th>
                                <th style="width: 30%">
                                    Sede
                                </th>
                                <th style="width: 50%">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bloques as $bloque)
                                <tr>
                                    <td>
                                        {{ $bloque->id }}
                                    </td>
                                    <td>
                                        {{ $bloque->descripcion }}
                                    </td>

                                    <td>
                                        {{ $bloque->sede->descripcion }}
                                    </td>

                                    <td>
                                        <span class="badge badge-{{ $bloque->status === 1 ? 'success' : 'danger' }}">
                                            @if ($bloque->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        {{-- <form id="cambiarEstadoForm" class=" d-inline"
                                            action="{{ route('bloque.cambiarEstadoUser', ['bloque' => $bloque->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT') --}}
                                            <button type="submit" class="btn btn-success btn-sm"><i
                                                    class="fas fa-sync"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('bloque.show', ['bloque' => $bloque->id]) }}">
                                            <i class="fas fa-eye"></i>

                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('bloque.edit', ['bloque' => $bloque->id]) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('bloque.destroy', ['bloque' => $bloque->id]) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar este parámetro?')">

                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4">No hay bloques registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <div class="card-footer">
        <div class="float-right">
            {{ $bloques->links() }}
        </div>
    </div>
@endsection
