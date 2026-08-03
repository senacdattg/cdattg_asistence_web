@extends('aitg.layouts.spa')

@section('title', 'Formalización - ' . $convocatoria->titulo)

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Formalización y firma de contrato',
        'subtitle' => $convocatoria->titulo,
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Convocatorias instructores', 'url' => route('aitg.convocatorias.publicas.index'), 'icon' => 'fa-bullhorn'],
            ['label' => 'Formalización', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>@endif

        <div class="alert alert-success">
            <i class="fas fa-trophy"></i>
            <strong>¡Fue seleccionado como instructor!</strong>
            Complete los documentos de <strong>formalización y firma de contrato</strong> indicados a continuación.
            Estos son distintos de los documentos de postulación (validación inicial) que cargó en el banco o en la convocatoria.
        </div>

        <div class="mb-3">
            <a href="{{ route('aitg.convocatorias.publicas.show', $convocatoria) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver al detalle</a>
            <span class="badge badge-success ml-2">{{ $postulacion->estado_label }}</span>
            <span class="badge badge-light border ml-1">{{ $postulacion->faseDocumentalLabel() }}</span>
        </div>

        @if($postulacion->observaciones_validador)
            <div class="alert alert-warning">
                <strong><i class="fas fa-comment-dots"></i> Observaciones del validador:</strong>
                <p class="mb-0 mt-1">{{ $postulacion->observaciones_validador }}</p>
            </div>
        @endif

        @include('aitg.banco-instructores.partials.datos-basicos', ['persona' => $persona])

        @if($postulacion->perfilPlan)
            <div class="aitg-card aitg-card--info mb-3">
                <div class="aitg-card__body py-2">
                    <strong>Perfil seleccionado:</strong> {{ $postulacion->perfilPlan->descripcion_criterio }}
                </div>
            </div>
        @endif

        @if($postulacion->estado === 'pendiente_revision')
            <div class="alert alert-info">
                <i class="fas fa-hourglass-half"></i> Sus documentos de formalización están en revisión. Recibirá notificación si debe corregir alguno.
            </div>
        @elseif(empty($secciones))
            <div class="alert alert-warning">
                No hay tipos de archivo de formalización configurados. Contacte al administrador del módulo AITG.
            </div>
        @else
            @include('aitg.banco-instructores.partials.seccion-documentos', [
                'secciones' => $secciones,
                'plan' => $plan,
                'postulacion' => $postulacion,
                'rutaDocumento' => route('aitg.convocatorias.publicas.documentos.store', $convocatoria),
                'rutaReutilizar' => route('aitg.convocatorias.publicas.reutilizar', $convocatoria),
                'rutaEliminar' => route('aitg.convocatorias.publicas.documentos.destroy', ['convocatoria' => $convocatoria, 'postulacionArchivo' => '__ID__']),
            ])

            @if($postulacion->puedeEditar() && $puedeEnviar)
                <form action="{{ route('aitg.convocatorias.publicas.formalizacion.enviar', $convocatoria) }}" method="POST" class="text-right mb-4">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Enviar documentos de formalización a revisión
                    </button>
                </form>
            @elseif($postulacion->puedeEditar())
                <p class="text-muted text-center"><small>Complete todos los documentos obligatorios de formalización para enviar.</small></p>
            @endif
        @endif
    </div>
</section>
@endsection
