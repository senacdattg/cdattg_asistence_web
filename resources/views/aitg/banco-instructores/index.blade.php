@extends('aitg.layouts.spa')

@section('title', 'Banco de Instructores - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Banco de Instructores',
        'subtitle' => 'Conviértete en Instructor SENA · Carga tu hoja de vida y documento de identidad',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Banco de Instructores', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button></div>
        @endif

        <div class="aitg-card aitg-card--primary mb-4">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--primary"><i class="fas fa-id-card"></i></span>
                    <h3 class="aitg-card__title">Estado de su solicitud</h3>
                </div>
            </div>
            <div class="aitg-card__body">
                <div class="row">
                    <div class="col-md-4"><strong>Estado:</strong>
                        <span class="badge badge-{{ match($solicitud->estado) {
                            'aprobado' => 'success',
                            'rechazado', 'requiere_correccion' => 'warning',
                            'pendiente_revision' => 'info',
                            default => 'secondary'
                        } }}">{{ $solicitud->estado_label }}</span>
                    </div>
                    <div class="col-md-4"><strong>Enviada:</strong> {{ $solicitud->fecha_envio?->format('d/m/Y H:i') ?? '—' }}</div>
                    <div class="col-md-4"><strong>Resolución:</strong> {{ $solicitud->fecha_resolucion?->format('d/m/Y H:i') ?? '—' }}</div>
                </div>
                @if($solicitud->estado === 'aprobado')
                    <div class="alert alert-success mt-3 mb-0">
                        <i class="fas fa-check-circle"></i> ¡Felicitaciones! Sus documentos fueron validados. Ahora tiene el rol de <strong>Aspirante a Instructor</strong>.
                    </div>
                @endif
            </div>
        </div>

        <div class="aitg-card aitg-card--success">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--success"><i class="fas fa-folder-open"></i></span>
                    <h3 class="aitg-card__title">Mis documentos</h3>
                </div>
            </div>
            <div class="aitg-card__body">
                @forelse($tiposArchivo as $tipo)
                    @php
                        $documento = $solicitud->documentos->firstWhere('tipo_archivo_id', $tipo->id);
                    @endphp
                    @include('aitg.banco-instructores.partials.documento-item', [
                        'tipo' => $tipo,
                        'documento' => $documento,
                        'puedeEditar' => $solicitud->puedeEditarDocumentos(),
                    ])
                @empty
                    <p class="text-muted mb-0">No hay tipos de archivo configurados. Contacte al administrador.</p>
                @endforelse

                @if($solicitud->puedeEditarDocumentos() && $puedeEnviar)
                    <form action="{{ route('aitg.banco-instructores.enviar-revision') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Enviar solicitud a revisión
                        </button>
                    </form>
                @elseif($solicitud->puedeEditarDocumentos())
                    <p class="text-muted mt-3 mb-0"><small>Cargue todos los documentos obligatorios para enviar a revisión.</small></p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
