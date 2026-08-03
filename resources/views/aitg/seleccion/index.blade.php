@extends('aitg.layouts.spa')

@section('title', 'Selección - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Selección de instructor',
        'subtitle' => 'Paso final del flujo — candidatos con evaluación aprobada',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Selección', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        <div class="alert alert-info">
            <strong>Antes de llegar aquí</strong> el aspirante cargó el checklist, pasó <em>Validar solicitudes</em> y el comité lo evaluó en
            <a href="{{ route('aitg.evaluacion.index') }}">Evaluación documental</a> (cumple/no cumple sobre los mismos criterios).
        </div>

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Convocatoria</th>
                                <th>Competencia</th>
                                <th>Candidatos evaluados</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($convocatorias as $convocatoria)
                                <tr>
                                    <td>{{ $convocatoria->codigo ?? '—' }}</td>
                                    <td>{{ $convocatoria->titulo }}</td>
                                    <td>{{ $convocatoria->competencia->nombre ?? '—' }}</td>
                                    <td><span class="badge badge-success">{{ $convocatoria->candidatos_count }}</span></td>
                                    <td>{{ \App\Models\Aitg\Convocatoria\Convocatoria::ESTADOS[$convocatoria->estado] ?? $convocatoria->estado }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('aitg.seleccion.candidatos', $convocatoria) }}" class="btn btn-sm btn-primary mb-1">
                                            <i class="fas fa-user-check"></i>
                                            {{ $convocatoria->estado === 'finalizada' ? 'Ver candidatos' : 'Confirmar selección' }}
                                        </a>
                                        <a href="{{ route('aitg.seleccion.reporte.general', $convocatoria) }}" class="btn btn-sm btn-outline-secondary mb-1">
                                            <i class="fas fa-file-alt"></i> Reporte
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Aún no hay candidatos con evaluación aprobada.<br>
                                        <small>Complete primero <a href="{{ route('aitg.validacion-banco.index') }}">Validación</a> y luego
                                        <a href="{{ route('aitg.evaluacion.index') }}">Evaluación</a>.</small>
                                    </td>
                                </tr>
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
