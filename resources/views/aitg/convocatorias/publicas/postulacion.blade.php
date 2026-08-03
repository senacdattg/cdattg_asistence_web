@extends('aitg.layouts.spa')

@section('title', 'Postular - ' . $convocatoria->titulo)

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Postulación a convocatoria',
        'subtitle' => $convocatoria->titulo,
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Convocatorias instructores', 'url' => route('aitg.convocatorias.publicas.index'), 'icon' => 'fa-bullhorn'],
            ['label' => 'Postular', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>@endif

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            @if($requiereDocumentosBase ?? false)
                Complete los <strong>documentos de postulación</strong> del catálogo (HV, RUT, antecedentes…), el documento del perfil si aplica, y el <strong>checklist evaluable</strong> del plan.
                Los puntos adicionales son opcionales y suman bonus fijo si el comité marca cumple.
            @elseif($bancoHabilitado ?? false)
                Su Banco de Talento está acreditado (documentos base ya validados). Complete el <strong>checklist evaluable</strong> del plan y, si desea, los puntos adicionales opcionales.
            @else
                Complete el checklist documental del plan. Cada ítem obligatorio tiene el mismo peso: <strong>100 ÷ N</strong> puntos porcentuales en evaluación.
            @endif
        </div>

        <div class="mb-3">
            <a href="{{ route('aitg.convocatorias.publicas.show', $convocatoria) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver al detalle</a>
            <span class="badge badge-{{ match($postulacion->estado) {
                'aprobado' => 'success', 'rechazado', 'requiere_correccion' => 'warning', 'pendiente_revision' => 'info', 'preseleccionado' => 'primary', default => 'secondary'
            } }} ml-2">{{ $postulacion->estado_label }}</span>
        </div>

        @if($postulacion->observaciones_validador)
            <div class="alert alert-warning">
                <strong><i class="fas fa-comment-dots"></i> Observaciones del validador (motivo de la devolución):</strong>
                <p class="mb-0 mt-1">{{ $postulacion->observaciones_validador }}</p>
            </div>
        @endif

        @include('aitg.banco-instructores.partials.datos-basicos', ['persona' => $persona])

        @include('aitg.banco-instructores.partials.perfiles-plan', [
            'plan' => $plan,
            'postulacion' => $postulacion,
            'etiquetaBloque' => $etiquetaBloque,
            'rutaPerfil' => route('aitg.convocatorias.publicas.perfil', $convocatoria),
        ])

        @if($postulacion->requierePerfil() && $postulacion->puedeEditar())
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Seleccione el perfil (alternativa) al que aplica.</div>
        @endif

        @if($postulacion->perfil_plan_id && ($postulacion->estado === 'requiere_correccion' || ! $postulacion->puedeEditar()))
            @include('aitg.banco-instructores.partials.resumen-documentos-postulacion', ['postulacion' => $postulacion])
        @endif

        @if($postulacion->perfil_plan_id)
            @if(empty($secciones))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Checklist no configurado.</strong>
                    El plan de contratación de esta convocatoria no tiene criterios en el checklist documental.
                    Un administrador debe editar el plan en <em>Planes de Contratación</em> y agregar los criterios (nombre, descripción y puntaje) antes de continuar.
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
                <form action="{{ route('aitg.convocatorias.publicas.enviar', $convocatoria) }}" method="POST" class="text-right mb-4">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-paper-plane"></i> Enviar postulación</button>
                </form>
            @elseif($postulacion->puedeEditar())
                <p class="text-muted text-center"><small>Complete todos los documentos obligatorios para enviar.</small></p>
            @endif

            @include('aitg.banco-instructores.partials.cancelar-postulacion', [
                'postulacion' => $postulacion,
                'rutaCancelar' => route('aitg.convocatorias.publicas.postulacion.destroy', $convocatoria),
            ])
            @endif
        @endif
    </div>
</section>
@endsection
