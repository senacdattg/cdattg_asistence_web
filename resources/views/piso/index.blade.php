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
                                <th style="width: 40%">
                                    Bloque
                                </th>
                                <th style="width: 50%">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1 ?>
                            @forelse ($pisos as $piso)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        {{ $piso->piso }}
                                    </td>
                                    <td>
                                        {{ $piso->bloque->sede->sede }}
                                    </td>
                                    <td>
                                        {{ $piso->bloque->bloque }}
                                    </td>

                                    <td>
                                        <span class="badge badge-{{ $piso->status === 1 ? 'success' : 'danger' }}">
                                            @if ($piso->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </td>
                                    {{-- <td> --}}
                                        {{-- <form id="cambiarEstadoForm" class=" d-inline"
                                            action="{{ route('piso.cambiarEstadoUser', ['piso' => $piso->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT') --}}
                                            {{-- <button type="submit" class="btn btn-success btn-sm"><i
                                                    class="fas fa-sync"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('piso.show', ['piso' => $piso->id]) }}">
                                            <i class="fas fa-eye"></i>

                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('piso.edit', ['piso' => $piso->id]) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                        </a>
                                    </td>--}}
                                    <td>
                                        <form class="formulario-eliminar " action="{{ route('piso.destroy', ['piso' => $piso->id]) }}" method="POST"
                                            class="d-inline">
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
                                    <td colspan="4">No hay pisos registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <div class="card-footer">
        <div class="float-right">
            {{ $pisos->links() }}
        </div>
    </div>
@endsection
