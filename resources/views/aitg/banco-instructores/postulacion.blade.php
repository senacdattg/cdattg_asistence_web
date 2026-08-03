@extends('aitg.layouts.spa')

@section('title', $competencia->nombre . ' - Banco de Talento')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Banco de Talento',
        'subtitle' => 'Competencia: ' . $competencia->nombre,
        'breadcrumb' => [
            ['label' => 'Banco de Talento', 'url' => route('aitg.banco-instructores.index'), 'icon' => 'fa-rocket'],
            ['label' => 'Documentos de postulación', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>@endif

        <div class="mb-3">
            <a href="{{ route('aitg.banco-instructores.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver al buscador</a>
            <span class="badge badge-{{ match($postulacion->estado) {
                'aprobado' => 'success', 'rechazado', 'requiere_correccion' => 'warning', 'pendiente_revision' => 'info', 'preseleccionado' => 'primary', default => 'secondary'
            } }} ml-2">{{ $postulacion->estado_label }}</span>
            <span class="badge badge-light border ml-1">{{ $postulacion->faseDocumentalLabel() }}</span>
        </div>

        @if($postulacion->observaciones_validador)
            <div class="alert alert-warning">
                <strong><i class="fas fa-comment-dots"></i> Observaciones del validador:</strong>
                <p class="mb-0 mt-1">{{ $postulacion->observaciones_validador }}</p>
            </div>
        @endif

        @if($postulacion->estado === 'aprobado')
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <strong>Habilitado en Banco de Talento.</strong> Sus documentos fueron acreditados para la competencia <strong>{{ $competencia->nombre }}</strong>.
                Ya puede postularse en convocatorias abiertas (allí elegirá perfil, checklist y puntos del plan).
            </div>
        @endif

        @if($postulacion->estado === 'pendiente_revision')
            <div class="alert alert-info">
                <i class="fas fa-hourglass-half"></i>
                Su postulación está <strong>en revisión</strong>. Puede retirarla con el botón al final si necesita corregir documentos antes de la respuesta del validador.
            </div>
        @endif

        @include('aitg.banco-instructores.partials.datos-basicos', ['persona' => $persona])

        <div class="aitg-card aitg-card--success mb-4">
            <div class="aitg-card__body py-3">
                <strong>Competencia:</strong> {{ $competencia->nombre }}
                <p class="text-muted small mb-0 mt-2">
                    En el banco solo cargará los <strong>documentos de postulación</strong> (HV, RUT, antecedentes, etc.).
                    No debe elegir perfil aquí: eso corresponde al momento de postular a una convocatoria específica.
                </p>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Suba cada PDF en su fila. Puede reemplazar o eliminar un documento mientras la postulación esté en borrador o en subsanación.
        </div>

        @if($postulacion->estado === 'requiere_correccion' || ! $postulacion->puedeEditar())
            @include('aitg.banco-instructores.partials.resumen-documentos-postulacion', ['postulacion' => $postulacion])
        @endif

        @if(empty($secciones))
            <div class="alert alert-warning">
                No hay tipos de documento configurados para la fase de postulación. Revise el catálogo en <em>Tipos de archivo</em>.
            </div>
        @else
            @include('aitg.banco-instructores.partials.seccion-documentos', [
                'secciones' => $secciones,
                'competencia' => $competencia,
                'postulacion' => $postulacion,
            ])

            @if($postulacion->puedeEditar() && $puedeEnviar)
                <form action="{{ route('aitg.banco-instructores.enviar-revision', $competencia) }}" method="POST" class="text-right mb-4">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Enviar documentos a revisión del Banco de Talento
                    </button>
                </form>
            @elseif($postulacion->puedeEditar())
                <p class="text-muted text-center"><small>Complete todos los documentos obligatorios de postulación para enviar a revisión.</small></p>
            @endif
        @endif

        @include('aitg.banco-instructores.partials.cancelar-postulacion', [
            'postulacion' => $postulacion,
            'rutaCancelar' => route('aitg.banco-instructores.postulacion.destroy', $competencia),
        ])
    </div>
</section>
@endsection
