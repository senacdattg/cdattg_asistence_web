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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                @include('temas_parametros.crear-parametro')

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
                                    <tr>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                           nombre parameto
                                        </td>

                                        <td class="project-state">
                                            <span class="badge badge-success">Activo</span>
                                            <span class="badge badge-danger">Inactivo</span>
                                        </td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-success btn-sm" href="#">
                                                <i class="fas fa-sync"></i>

                                                cambiar estado
                                            </a>
                                            <a class="btn btn-warning btn-sm" href="#">
                                                <i class="fas fa-folder">
                                                </i>
                                                Ver
                                            </a>
                                            <a class="btn btn-info btn-sm" href="#">
                                                <i class="fas fa-pencil-alt">
                                                </i>
                                                Editar
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="#">
                                                <i class="fas fa-trash">
                                                </i>
                                                Eliminar
                                            </a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                @endsection
            </div>
        </div>
    </section>
</div>
