@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
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

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form method="get" action="{{ route('programa.search') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o documento" value="{{ request()->get('search') }}">
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
                            @foreach ($programas as $progrma)
                                <tr>
                                    <td>{{ $progrma->id }}</td>
                                    <td>{{ $progrma->nombre }}</td>
                                    <td>{{ $progrma->TipoPrograma->nombre }}</td>
                                    <td>
                                        {{$progrma->sede->sede}}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Acciones" style="gap: 10px;">
                                            <a href="" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> 
                                            </a>
                                            <a href="" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> 
                                            </a>
                                            <form action="" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este programa?')">
                                                    <i class="fas fa-trash"></i> 
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

@endsection
