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

                <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Nombre y apellido
                                </th>
                                <th style="width: 30%">
                                    Numero de documento
                                </th>
                                <th style="width: 40%">
                                    Correo electronico
                                </th>
                                <th style="width: 50%">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($personas as $persona)
                                <tr>
                                    <td>
                                        {{ $persona->id }}
                                    </td>
                                    <td>
                                        {{ $persona->primer_nombre }} {{ $persona->primer_apellido }}
                                    </td>

                                    <td>
                                        {{ $persona->numero_documento }}
                                    </td>
                                    <td>
                                        {{ $persona->email }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $persona->user->status === 1 ? 'success' : 'danger' }}">
                                        @if ($persona->user->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <form id="cambiarEstadoForm" class=" d-inline"
                                            action="{{ route('persona.cambiarEstadoUser', ['persona' => $persona->user->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm"><i
                                                class="fas fa-sync"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('persona.show', ['persona' => $persona->id]) }}">
                                            <i class="fas fa-eye"></i>

                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('persona.edit', ['persona' => $persona->id]) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                        </a>
                                    </td>
                                    <td>
                                        {{-- <form action="{{ route('personas.destroy', ['persona' => $persona->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE') --}}

                                        {{-- <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este parámetro?')">

                                            <i class="fas fa-trash"></i>
                                        </button> --}}
                                        {{-- </form> --}}
                                    </td>

                </div>
            </div>
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

    <div class="card-footer">
        <div class="float-right">
            {{ $personas->links() }}
        </div>
    </div>
@endsection
