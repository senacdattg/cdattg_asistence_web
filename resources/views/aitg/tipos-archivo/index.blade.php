@extends('aitg.layouts.spa')

@section('title', 'Tipos de archivo AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Tipos de archivo',
        'subtitle' => 'Catálogo documental del Banco de Instructores',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Tipos de archivo', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="mb-3">
        @can('CREAR TIPO ARCHIVO AITG')
            <a href="{{ route('aitg.tipos-archivo.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo tipo</a>
        @endcan
        @can('VER MOTIVO RECHAZO AITG')
            <a href="{{ route('aitg.motivos-rechazo.index') }}" class="btn btn-outline-secondary">Motivos de rechazo</a>
        @endcan
    </div>

    @foreach([
        'inicial' => ['titulo' => 'Documentos de postulación (validación inicial)', 'tipos' => $tiposInicial],
        'post_seleccion' => ['titulo' => 'Documentos de formalización y firma de contrato (post-selección)', 'tipos' => $tiposPostSeleccion],
    ] as $faseKey => $bloque)
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0">{{ $bloque['titulo'] }}</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Obligatorio</th>
                            <th>Activo</th>
                            <th>Orden</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bloque['tipos'] as $tipo)
                            <tr>
                                <td>{{ $tipo->codigo }}</td>
                                <td>{{ $tipo->nombre }}</td>
                                <td>{{ \App\Models\Aitg\Banco\TipoArchivo::CATEGORIAS[$tipo->categoria] ?? $tipo->categoria }}</td>
                                <td>{{ $tipo->es_obligatorio ? 'Sí' : 'No' }}</td>
                                <td>{{ $tipo->activo ? 'Sí' : 'No' }}</td>
                                <td>{{ $tipo->orden }}</td>
                                <td class="text-right">
                                    @can('EDITAR TIPO ARCHIVO AITG')
                                        <a href="{{ route('aitg.tipos-archivo.edit', $tipo) }}" class="btn btn-sm btn-info">Editar</a>
                                    @endcan
                                    @can('ELIMINAR TIPO ARCHIVO AITG')
                                        <form action="{{ route('aitg.tipos-archivo.destroy', $tipo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-muted text-center py-4">No hay tipos configurados para esta fase.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
</section>
@endsection
