@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Registro de asistencia</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
<div class="content">
    <div class="row">
        <div class="col">
            @include('entradaSalidas.create')
        </div>
        <div class="col">
            @include('entradaSalidas.edit')
        </div>
    </div>
</div>
        <section class="content">

            <div class="card">
                <div class="card-body">
                    <div class="card-body p-0">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        #
                                    </th>
                                    <th style="width: 20%">
                                        Aprendiz
                                    </th>
                                    <th style="width: 30%">
                                        entrada
                                    </th>
                                    <th style="width: 40%">
                                        salida
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @forelse ($registros as $registro)
                                    <tr>
                                        <td>
                                            {{ $i++ }}
                                            {{-- {{ $registro->id }} --}}
                                        </td>
                                        <td>
                                           {{ $registro->aprendiz }}
                                        </td>

                                        <td>
                                            {{ $registro->entrada }}
                                        </td>
                                        <td>
                                            {{ $registro->salida }}
                                        </td>
                                        <td>
                                            {{-- <span class="badge badge-{{ $registro->user->status === 1 ? 'success' : 'danger' }}">
                                        @if ($registro->user->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span> --}}
                                        </td>
                                        <td>
                                            {{-- <form id="cambiarEstadoForm" class=" d-inline"
                                            action="{{ route('registro.cambiarEstadoUser', ['registro' => $registro->user->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT') --}}
                                            <button type="submit" class="btn btn-success btn-sm"><i
                                                    class="fas fa-sync"></i></button>
                                            </form>
                                        </td>
                                        <td>
                                            {{-- <a class="btn btn-warning btn-sm"
                                            href="{{ route('registro.show', ['registro' => $registro->id]) }}"> --}}
                                            <i class="fas fa-eye"></i>

                                            </a>
                                        </td>
                                        <td>
                                            {{-- <a class="btn btn-info btn-sm"
                                            href="{{ route('registro.edit', ['registro' => $registro->id]) }}"> --}}
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            </a>
                                        </td>
                                        <td>
                                            {{-- <form action="{{ route('registros.destroy', ['registro' => $registro->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE') --}}

                                            {{-- <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este parámetro?')">

                                            <i class="fas fa-trash"></i>
                                        </button> --}}
                                            {{-- </form> --}}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">No hay personas registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
