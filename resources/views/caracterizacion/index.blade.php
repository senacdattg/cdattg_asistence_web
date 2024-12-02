
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
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                }, 3000);
            </script>
         @endif
         @if (session('error'))
            <div class="alert alert-danger" id="error-message">
                {{ session('error') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('error-message').style.display = 'none';
                }, 3000);
            </script>
        @endif

         

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
                                <th class="text-center" style="width: 16%;">
                                    Sede
                                </th>
                                <th class="text-center" style="width: 16%;">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($caracteres as $caracter)
                          <tr>
                                <td class="text-center">{{$caracter->ficha->ficha ?? 'N/A'}}</td>
                                <td class="text-center">{{$caracter->programaFormacion->nombre ?? 'N/A'}}</td>
                                <td class="text-center">{{$caracter->persona->primer_nombre }} {{$caracter->persona->primer_apellido ?? ''}}</td>
                                <td class="text-center">{{$caracter->jornada->jornada ?? 'N/A' }}</td>
                                <td class="text-center">{{$caracter->sede->sede}}</td>
                                
                            
                                <td class="text-center">
                                    @can('VER PROGRAMA DE CARACTERIZACION')
                                    <div class="btn-group d-flex justify-content-center" role="group" aria-label="Acciones" style="gap: 10px;">
                                        <a href="" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> 
                                        </a>
                                        <a href="{{route('caracterizacion.edit', $caracter->id)}}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> 
                                        </a>
                                        <a href="{{route('caracterizacion.destroy', $caracter->id)}}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> 
                                        </a>
                                      
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
                {{-- {{ $programas->links() }} --}}
            </div>
            
    </div>

@endsection
