@extends('aitg.layouts.spa')

@section('title', 'Validación Banco de Talento - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Validación de postulaciones',
        'subtitle' => 'Banco de Talento · Revisión de soportes por plan',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Validación', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__body">
                <form method="GET" class="row mb-3">
                    <div class="col-md-4">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(\App\Models\Aitg\Banco\PostulacionPlan::ESTADOS as $val => $label)
                                @if($val !== 'borrador')
                                    <option value="{{ $val }}" @selected(request('estado') === $val)>{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-block">Filtrar</button></div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Aspirante</th>
                                <th>Competencia / Plan</th>
                                <th>Estado</th>
                                <th>Fase</th>
                                <th>Enviada</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($postulaciones as $postulacion)
                                <tr>
                                    <td>{{ $postulacion->id }}</td>
                                    <td>
                                        {{ trim(($postulacion->user->persona->primer_nombre ?? '') . ' ' . ($postulacion->user->persona->primer_apellido ?? '')) ?: $postulacion->user->email }}
                                        <br><small class="text-muted">{{ $postulacion->user->persona?->numero_documento ?? '—' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $postulacion->plan->competencia->nombre ?? '—' }}</strong>
                                        <br><small class="text-muted">Plan #{{ $postulacion->plan_contratacion_id }}</small>
                                    </td>
                                    <td><span class="badge badge-info">{{ $postulacion->estado_label }}</span></td>
                                    <td><small>{{ $postulacion->faseDocumentalLabel() }}</small></td>
                                    <td>{{ $postulacion->fecha_envio?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('aitg.validacion-banco.show', $postulacion) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-search"></i> Revisar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No hay postulaciones pendientes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $postulaciones->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
