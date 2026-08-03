@extends('aitg.layouts.spa')

@section('title', 'Motivos de rechazo AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Motivos de rechazo',
        'subtitle' => 'Catálogo de motivos para validación documental',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Motivos de rechazo', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="mb-3">
        @can('CREAR MOTIVO RECHAZO AITG')
            <a href="{{ route('aitg.motivos-rechazo.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo motivo</a>
        @endcan
        @can('VER TIPO ARCHIVO AITG')
            <a href="{{ route('aitg.tipos-archivo.index') }}" class="btn btn-outline-secondary">Tipos de archivo</a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead><tr><th>Código</th><th>Nombre</th><th>Activo</th><th>Orden</th><th></th></tr></thead>
                <tbody>
                    @foreach($motivos as $motivo)
                        <tr>
                            <td>{{ $motivo->codigo }}</td>
                            <td>{{ $motivo->nombre }}</td>
                            <td>{{ $motivo->activo ? 'Sí' : 'No' }}</td>
                            <td>{{ $motivo->orden }}</td>
                            <td class="text-right">
                                @can('EDITAR MOTIVO RECHAZO AITG')
                                    <a href="{{ route('aitg.motivos-rechazo.edit', $motivo) }}" class="btn btn-sm btn-info">Editar</a>
                                @endcan
                                @can('ELIMINAR MOTIVO RECHAZO AITG')
                                    <form action="{{ route('aitg.motivos-rechazo.destroy', $motivo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $motivos->links() }}</div>
    </div>
</div>
</section>
@endsection
