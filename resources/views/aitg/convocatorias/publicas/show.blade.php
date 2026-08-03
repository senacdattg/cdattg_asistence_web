@extends('aitg.layouts.spa')

@section('title', $convocatoria->titulo)

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => $convocatoria->titulo,
        'subtitle' => $convocatoria->codigo . ' · ' . ($convocatoria->competencia->nombre ?? ''),
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Convocatorias instructores', 'url' => route('aitg.convocatorias.publicas.index'), 'icon' => 'fa-bullhorn'],
            ['label' => 'Detalle', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('aitg.convocatorias.publicas.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
            <span class="badge badge-{{ $convocatoria->badgeClassEstado() }}">{{ $convocatoria->estado_label }}</span>
            @if($postulacion)<span class="badge badge-info">Su postulación: {{ $postulacion->estado_label }}</span>@endif
        </div>

        @if($convocatoria->estado === 'finalizada' && $convocatoria->postulacionSeleccionada)
            @php $sel = $convocatoria->postulacionSeleccionada; @endphp
            <div class="alert alert-success">
                <h5 class="alert-heading mb-2"><i class="fas fa-trophy"></i> Instructor seleccionado</h5>
                <p class="mb-1"><strong>{{ $sel->user->persona->nombre_completo ?? $sel->user->email }}</strong></p>
                @if($sel->perfilPlan)
                    <p class="mb-0 small">Perfil: {{ $sel->perfilPlan->descripcion_criterio }}</p>
                @endif
            </div>
        @endif

        @if($mensajeBloqueo && ! $postulacion)
            <div class="alert alert-warning">{{ $mensajeBloqueo }}</div>
        @elseif($mensajeBancoRecomendado ?? null)
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> {{ $mensajeBancoRecomendado }}
                @if(! ($bancoHabilitado ?? false))
                    <a href="{{ route('aitg.banco-instructores.index') }}" class="alert-link d-block mt-1">También puede acreditar documentos en el Banco de Talento</a>
                @endif
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="aitg-card aitg-card--primary mb-3">
                    <div class="aitg-card__body">
                        <p><strong>Objeto contractual:</strong><br>{{ $convocatoria->objeto_contractual ?: '—' }}</p>
                        <p><strong>Requisitos:</strong><br>{{ $convocatoria->requisitos ?: '—' }}</p>
                        <p class="mb-0"><strong>Descripción:</strong><br>{{ $convocatoria->descripcion ?: '—' }}</p>
                    </div>
                </div>
                @include('aitg.banco-instructores.partials.checklist-plan', ['plan' => $convocatoria->plan])
            </div>
            <div class="col-md-4">
                <div class="aitg-card aitg-card--info mb-3">
                    <div class="aitg-card__body">
                        <p><strong>Fechas publicación:</strong><br>{{ $convocatoria->fecha_inicio_publicacion?->format('d/m/Y') ?? '—' }} – {{ $convocatoria->fecha_fin_publicacion?->format('d/m/Y') ?? '—' }}</p>
                        <p><strong>Competencia:</strong> {{ $convocatoria->competencia->nombre ?? '—' }}</p>
                        <p><strong>Regional:</strong> {{ $convocatoria->regional->nombre ?? '—' }}</p>
                        <p class="mb-0"><strong>Centro de formación:</strong> {{ $convocatoria->centroFormacion->nombre ?? '—' }}</p>
                        <hr>
                        <p class="small text-muted mb-0">Solo puede postular a <strong>un perfil</strong> en el <strong>centro indicado</strong> por convocatoria, y a <strong>una convocatoria activa por regional</strong>.</p>
                    </div>
                </div>

                @if($postulacion?->esEnFormalizacion() && in_array($postulacion->estado, ['seleccionado', 'requiere_correccion'], true))
                    <a href="{{ route('aitg.convocatorias.publicas.formalizacion', $convocatoria) }}" class="btn btn-success btn-lg btn-block mb-2">
                        <i class="fas fa-file-signature"></i>
                        @if($postulacion->estado === 'requiere_correccion')
                            Corregir documentos de formalización
                        @else
                            Cargar documentos de formalización
                        @endif
                    </a>
                @elseif($postulacion?->esEnFormalizacion() && $postulacion->estado === 'pendiente_revision')
                    <p class="text-info small"><i class="fas fa-hourglass-half"></i> Sus documentos de formalización están en revisión.</p>
                @elseif($postulacion?->puedeEditar())
                    <a href="{{ route('aitg.convocatorias.publicas.postular', $convocatoria) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit"></i>
                        @if($postulacion->estado === 'requiere_correccion')
                            Corregir documentos
                        @elseif($postulacion->estado === 'preseleccionado')
                            Ver postulación (en evaluación)
                        @else
                            Continuar postulación
                        @endif
                    </a>
                @elseif($puedePostular)
                    <a href="{{ route('aitg.convocatorias.publicas.postular', $convocatoria) }}" class="btn btn-success btn-lg btn-block mb-2">
                        <i class="fas fa-paper-plane"></i> Postularme
                    </a>
                @elseif($convocatoria->puedePostular() && ! auth()->user()->can('SUBIR DOCUMENTO BANCO AITG'))
                    <p class="text-muted small">Puede consultar esta convocatoria. Para postularse necesita permisos de aspirante a instructor.</p>
                @elseif($convocatoria->soloLectura())
                    <p class="text-muted small">Esta convocatoria ya no acepta nuevas postulaciones.</p>
                @endif

                @if($postulacion?->puedeEliminar())
                    <form action="{{ route('aitg.convocatorias.publicas.postulacion.destroy', $convocatoria) }}" method="POST" onsubmit="return confirm('¿Eliminar su postulación? Podrá volver a postular si la convocatoria sigue abierta.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-block btn-sm">
                            <i class="fas fa-trash"></i> Eliminar mi postulación
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
