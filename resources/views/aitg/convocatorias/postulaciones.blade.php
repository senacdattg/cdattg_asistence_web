@extends('aitg.layouts.spa')

@section('title', 'Postulaciones - ' . $convocatoria->codigo)

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Postulaciones / propuestas',
        'subtitle' => $convocatoria->titulo,
        'breadcrumb' => [
            ['label' => 'Convocatorias', 'url' => route('aitg.convocatorias.index'), 'icon' => 'fa-bullhorn'],
            ['label' => 'Postulaciones', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        <div class="mb-3">
            <a href="{{ route('aitg.convocatorias.show', $convocatoria) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver a convocatoria</a>
        </div>

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Aspirante</th>
                                <th>Documento</th>
                                <th>Perfil</th>
                                <th>Estado postulación</th>
                                <th>Fase</th>
                                <th>Enviada</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($postulaciones as $p)
                                <tr>
                                    <td>{{ trim(($p->user->persona->primer_nombre ?? '') . ' ' . ($p->user->persona->primer_apellido ?? '')) ?: $p->user->email }}</td>
                                    <td>{{ $p->user->persona?->numero_documento ?? '—' }}</td>
                                    <td><small>{{ $p->perfilPlan?->descripcion_criterio ? \Illuminate\Support\Str::limit($p->perfilPlan->descripcion_criterio, 50) : 'Sin perfil' }}</small></td>
                                    <td><span class="badge badge-info">{{ $p->estado_label }}</span></td>
                                    <td><small>{{ $p->faseDocumentalLabel() }}</small></td>
                                    <td>{{ $p->fecha_envio?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="text-center">
                                        @can('VER SOLICITUD BANCO AITG')
                                            <a href="{{ route('aitg.validacion-banco.show', $p) }}" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Revisar</a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No hay postulaciones registradas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
