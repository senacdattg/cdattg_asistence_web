@extends('layout.master-layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/dual-listbox/css/bootstrap-duallistbox.min.css') }}">
@endsection
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Usuarios</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('permiso.index') }}">Usuarios</a></li>
                            <li class="breadcrumb-item active">Permisos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('permiso.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                {{-- <div class="container"> --}}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">

                            <div class="card card-primary card-outline carne">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="{{ asset('dist/img/logoSena.png') }}" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">
                                        {{ $user->persona->primer_nombre }}
                                        {{ $user->persona->segundo_nombre }}
                                        {{ $user->persona->primer_apellido }}
                                        {{ $user->persona->segundo_apellido }}
                                    </h3>
                                    <p class="h4 text-muted text-center">Información Básica</p>

                                    <p class="text-muted"><strong>Tipo de documento:</strong>
                                        {{ $user->persona->tipoDocumento->name }}</p>

                                    <p class="text-muted "><strong>Numero de documento:</strong>
                                        {{ $user->persona->numero_documento }}</p>

                                    <p class="text-muted "><strong>Fecha de nacimiento:</strong>
                                        {{ $user->persona->fecha_de_nacimiento }}</p>

                                    <p class="text-muted "><strong>Correo:</strong> {{ $user->persona->email }}</p>

                                    <p class="text-muted "><strong>Fecha de edad:</strong> {{ $user->persona->edad }}
                                    </p>

                                    <p class="text-muted "><strong>Genero:</strong>
                                        {{ $user->persona->tipoGenero->name }}
                                    </p>

                                    <p class="text-muted "><strong>estado:</strong>
                                        <span
                                            class="badge badge-{{ $user->persona->user->status === 1 ? 'success' : 'danger' }}">
                                            @if ($user->persona->user->status === 1)
                                                ACTIVO
                                            @else
                                                INACTIVO
                                            @endif
                                        </span>
                                    </p>
                                    @if ($user->persona->instructor && $user->persona->instructor->regional)
                                        <p class="text-muted"> <strong>Regional: </strong>
                                            {{ $user->persona->instructor->regional->regional }}</p>
                                    @endif

                                </div>
                            </div>

                        </div>
                        <div class="col-md-9">

                            <div class="card card-primary card-outline">

                                <div class="card-body">
                                    {{-- <ul class="list-group col-sm-4">
                                        <li class="list-group-item active">
                                            Permisos del usuario
                                        </li>
                                        @forelse ($permisos as $permiso)
                                            @if ($user->hasAnyPermission([$permiso]))
                                                <li class="list-group-item">{{ $permiso->name }}</li>
                                            @endif
                                        @empty
                                        @endforelse

                                    </ul> --}}
                                    <div class="bootstrap-duallistbox-container  row moveonselect moveondoubleclick"
                                        id="permisos-container">
                                        <div class="card-body">
                                            <h4><strong>Permisos de usuario</strong></h4>
                                        </div>
                                        {{-- <div class="box1 ">  --}}
                                        <form action="{{ route('permiso.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                                            <select multiple="multiple" id="bootstrap-duallistbox-nonselected-list_"
                                                name="permisos[]" style="height: 120px;">
                                                @forelse ($permisos as $permiso)
                                                    <option value="{{ $permiso->name }}"
                                                        @if ($user->hasAnyPermission([$permiso])) selected @endif>
                                                        {{ $permiso->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <br>

                                            <button type="submit" class="btn btn-success btn-sm-3">Asignar
                                                permisos</button>
                                        </form>
                                        {{-- </div> --}}
                                        {{-- <div class="box2 ">

                                        </div> --}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- dual box --}}


                    </div>

        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('plugins/dual-listbox/js/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script>
        var demo1 = $('select[name="permisos[]"]').bootstrapDualListbox();
        // var demo1 = $ ( 'select[name="permisos"]' ).arranqueDualListbox();
    </script>
@endsection
