@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header mt-3">
            <div class="container-fluid mt-3">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Programas de formación


                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Programas de formación
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form method="get" action="{{ route('programa.search') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar por nombre de programa" value="{{ request()->get('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    Id
                                </th>
                                <th style="width: 20%">
                                    Programa
                                </th>
                                <th style="width: 30%">
                                    Tipo Programa
                                </th>
                                <th style="width: 40%">
                                    Sede
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$programas || $programas->isEmpty()):  ?>

                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p>Error al consultar lista de programas</p>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <?php endif; ?>
                            @foreach ($programas as $progrma)
                                <tr>
                                    <td>{{ $progrma->id }}</td>
                                    <td>{{ $progrma->nombre }}</td>
                                    <td>{{ $progrma->TipoPrograma->nombre }}</td>
                                    <td>
                                        {{ $progrma->sede->sede }}
                                    </td>
                                    <td>
                                        @can('VER PROGRAMA DE CARACTERIZACION')
                                            <div class="btn-group" role="group" aria-label="Acciones" style="gap: 10px;">

                                                <a href="/programa/{{ $progrma->id }}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('programa.destroy', ['id' => $progrma->id]) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Estás seguro de eliminar este programa?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $programas->links() }}
            </div>

    </div>
@endsection
