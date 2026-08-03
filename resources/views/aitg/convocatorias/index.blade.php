@extends('aitg.layouts.spa')

@section('title', 'Convocatorias - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Convocatorias',
        'subtitle' => 'Gestión de procesos de contratación de instructores',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Convocatorias', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--primary"><i class="fas fa-bullhorn"></i></span>
                    <h3 class="aitg-card__title">Listado de convocatorias</h3>
                </div>
                @can('CREAR CONVOCATORIA AITG')
                    <a href="{{ route('aitg.convocatorias.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Nueva convocatoria</a>
                @endcan
            </div>
            <div class="aitg-card__body">
                <form method="GET" class="row mb-3">
                    <div class="col-md-5"><input type="text" name="busqueda" class="form-control" placeholder="Buscar por código, título o competencia..." value="{{ request('busqueda') }}"></div>
                    <div class="col-md-3">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(\App\Models\Aitg\Convocatoria\Convocatoria::ESTADOS as $v => $l)
                                <option value="{{ $v }}" @selected(request('estado') === $v)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-block">Filtrar</button></div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Título</th>
                                <th>Estado</th>
                                <th>Postulaciones</th>
                                <th>Publicación</th>
                                <th>Cierre</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($convocatorias as $conv)
                                <tr>
                                    <td><code>{{ $conv->codigo }}</code></td>
                                    <td>{{ $conv->titulo }}</td>
                                    <td><span class="badge badge-{{ match($conv->estado) { 'publicada' => 'success', 'cerrada' => 'warning', 'finalizada' => 'info', default => 'secondary' } }}">{{ $conv->estado_label }}</span></td>
                                    <td class="text-center">{{ $conv->postulaciones_count }}</td>
                                    <td>{{ $conv->fecha_inicio_publicacion?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ $conv->fecha_fin_publicacion?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('aitg.convocatorias.show', $conv) }}" class="btn btn-xs btn-info" title="Ver"><i class="fas fa-eye"></i></a>
                                        @can('EDITAR CONVOCATORIA AITG')
                                            <a href="{{ route('aitg.convocatorias.edit', $conv) }}" class="btn btn-xs btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        <a href="{{ route('aitg.convocatorias.postulaciones', $conv) }}" class="btn btn-xs btn-primary" title="Postulaciones"><i class="fas fa-users"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No hay convocatorias registradas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $convocatorias->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
