@extends('aitg.layouts.spa')

@section('title', 'Convocatorias instructores - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Convocatorias instructores',
        'subtitle' => 'Un perfil y un centro por convocatoria · una postulación activa por regional',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Convocatorias instructores', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        @if($misPostulaciones->isNotEmpty())
            <div class="aitg-card aitg-card--success mb-4">
                <div class="aitg-card__header py-2"><h4 class="aitg-card__title mb-0">Mis postulaciones a convocatorias</h4></div>
                <div class="aitg-card__body">
                    @foreach($misPostulaciones as $p)
                        <div class="d-flex justify-content-between align-items-center flex-wrap border-bottom py-2 gap-2">
                            <div>
                                <strong>{{ $p->convocatoria->titulo ?? 'Convocatoria #' . $p->convocatoria_id }}</strong>
                                <span class="badge badge-secondary ml-1">{{ $p->estado_label }}</span>
                                @if($p->convocatoria?->regional)
                                    <small class="text-muted ml-1">· {{ $p->convocatoria->regional->nombre }}</small>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <a href="{{ route('aitg.convocatorias.publicas.show', $p->convocatoria_id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                @if($p->puedeEditar())
                                    <a href="{{ route('aitg.convocatorias.publicas.postular', $p->convocatoria_id) }}" class="btn btn-sm btn-primary">Continuar</a>
                                @endif
                                @if($p->puedeEliminar())
                                    <form action="{{ route('aitg.convocatorias.publicas.postulacion.destroy', $p->convocatoria_id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar su postulación a esta convocatoria?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Eliminar postulación</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="aitg-card aitg-card--primary mb-4">
            <div class="aitg-card__body">
                <form method="GET" class="row align-items-end">
                    <div class="col-md-4 form-group mb-md-0">
                        <label>Buscar</label>
                        <input type="text" name="competencia" class="form-control" placeholder="Competencia o título..." value="{{ $filtros['competencia'] ?? '' }}">
                    </div>
                    <div class="col-md-3 form-group mb-md-0">
                        <label>Regional</label>
                        <select name="regional_id" class="form-control">
                            <option value="">Todas</option>
                            @foreach($regionales as $r)
                                <option value="{{ $r->id }}" @selected(($filtros['regional_id'] ?? '') == $r->id)>{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-md-0">
                        <label>Etiqueta / estado</label>
                        <select name="estado" class="form-control">
                            <option value="">Todos visibles</option>
                            @foreach(\App\Models\Aitg\Convocatoria\Convocatoria::ESTADOS as $val => $label)
                                @if($val !== 'borrador' || $puedeVerBorrador)
                                    <option value="{{ $val }}" @selected(($filtros['estado'] ?? '') === $val)>{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-md-0">
                        <button class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filtrar</button>
                    </div>
                </form>

                <div class="mt-3 d-flex flex-wrap">
                    @foreach(\App\Models\Aitg\Convocatoria\Convocatoria::ESTADOS as $val => $label)
                        @if($val !== 'borrador' || $puedeVerBorrador)
                            <a href="{{ route('aitg.convocatorias.publicas.index', array_merge($filtros, ['estado' => $val])) }}"
                               class="badge badge-{{ match($val) { 'publicada' => 'success', 'cerrada' => 'warning', 'finalizada' => 'info', default => 'secondary' } }} mr-2 mb-2 p-2">
                                {{ $label }}
                            </a>
                        @endif
                    @endforeach
                    <a href="{{ route('aitg.convocatorias.publicas.index') }}" class="badge badge-light border mr-2 mb-2 p-2">Quitar filtros</a>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($tarjetas as $tarjeta)
                <div class="col-md-6 col-xl-4 mb-4">
                    @include('aitg.convocatorias.publicas.partials.convocatoria-card', $tarjeta)
                </div>
            @empty
                <div class="col-12"><p class="text-muted text-center py-4">No hay convocatorias con los filtros seleccionados.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
