@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Fichas de caracterización</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home.index') }}">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Fichas de caracterización
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">

                <div class="card-body">
                    <a href="{{ route('fichaCaracterizacion.create') }}" class="btn btn-success bnt-sm-2"><i
                            class="fas fa-clipboard-list"></i></a>

                </div>
               <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Ficha
                                </th>
                                <th style="width: 30%">
                                    Nombre del curso
                                </th>
                                <th style="width: 40%">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            ?>
                            @forelse ($fichas as $ficha)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        {{ $ficha->ficha }}
                                    </td>

                                    <td>
                                        {{ $ficha->nombre_curso }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ficha->status === 1 ? 'success' : 'danger' }}">
                                            @if ($ficha->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <form id="cambiarEstadoForm" class=" d-inline"
                                            action="{{ route('fichaCaracterizacion.cambiarEstado', ['fichaCaracterizacion' => $ficha->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm"><i
                                                    class="fas fa-sync"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('fichaCaracterizacion.show', ['fichaCaracterizacion' => $ficha->id]) }}">
                                            <i class="fas fa-eye"></i>

                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('fichaCaracterizacion.edit', ['fichaCaracterizacion' => $ficha->id]) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                        </a>
                                    </td>
                                    <td>
                                        
                                        <form class="formulario-eliminar btn" action="{{ route('fichaCaracterizacion.destroy', ['fichaCaracterizacion' => $ficha->id]) }}"
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
                                    <td colspan="4">No hay fichas de caracterización registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <div class="card-footer">
        <div class="float-right">
            {{ $fichas->links() }}
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery-selectDinamico.js') }}"></script>
@endsection
