
@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header mt-3">
            <div class="container-fluid mt-3">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Cosultar Caracterizaciones</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="">Inicio</a>
                            </li>
                            <li class="breadcrumb-item active">Caracterizacion de Programas
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <form method="get" action="">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre de programa" value="{{ request()->get('search') }}">
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
                                <th class="text-center" style="width: 15%;">
                                    Ficha
                                </th>
                                <th class="text-center" style="width: 15%;">
                                    Programa
                                </th>
                                <th class="text-center" style="width: 15%;">
                                    Instructor
                                </th>
                                <th class="text-center" style="width: 15%;">
                                    Jornada
                                </th>
                                <th class="text-center" style="width: 20%;">
                                    Sede
                                </th>
                                <th class="text-center" style="width: 20%;">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                          
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                               
                                <td class="text-center">
                                    @can('VER PROGRAMA DE CARACTERIZACION')
                                    <div class="btn-group d-flex justify-content-center" role="group" aria-label="Acciones" style="gap: 10px;">
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
                                    @endcan
                                </td>
                            </tr>
                         
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{-- {{ $programas->links() }} --}}
            </div>
            
    </div>

@endsection
