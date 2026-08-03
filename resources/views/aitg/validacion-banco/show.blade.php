@extends('aitg.layouts.spa')

@section('title', 'Revisar postulación - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Revisar postulación #' . $postulacion->id,
        'subtitle' => ($postulacion->plan->competencia->nombre ?? 'Plan') . ' · ' . (trim(($postulacion->user->persona->primer_nombre ?? '') . ' ' . ($postulacion->user->persona->primer_apellido ?? '')) ?: $postulacion->user->email),
        'breadcrumb' => [
            ['label' => 'Validación', 'url' => route('aitg.validacion-banco.index'), 'icon' => 'fa-check-double'],
            ['label' => 'Detalle', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="mb-3 d-flex flex-wrap align-items-center">
            <a href="{{ route('aitg.validacion-banco.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
            <span class="badge badge-info ml-2">{{ $postulacion->estado_label }}</span>
            <span class="badge badge-light border ml-1">{{ $postulacion->faseDocumentalLabel() }}</span>
        </div>

        <div class="aitg-card aitg-card--primary mb-3">
            <div class="aitg-card__body py-3">
                @if($postulacion->requierePerfil() && $postulacion->esConvocatoria())
                    <div class="alert alert-danger mb-2">
                        <i class="fas fa-exclamation-triangle"></i> El aspirante <strong>no ha seleccionado perfil/alternativa</strong>. Devuelva la postulación indicando que debe escoger una opción.
                    </div>
                @elseif($postulacion->perfilPlan)
                    <p class="mb-1"><strong>Perfil seleccionado:</strong> {{ $postulacion->perfilPlan?->descripcion_criterio }}</p>
                @elseif($postulacion->esBancoTalento())
                    <p class="mb-1"><strong>Competencia:</strong> {{ $postulacion->nombreCompetencia() }}</p>
                @endif
                <p class="mb-1">
                    <span class="badge badge-{{ $postulacion->esBancoTalento() ? 'secondary' : 'primary' }}">
                        @if($postulacion->faseDocumental() === 'post_seleccion')
                            Formalización — instructor seleccionado
                        @elseif($postulacion->esBancoTalento())
                            Banco de Talento — acreditación
                        @else
                            Postulación a convocatoria
                        @endif
                    </span>
                </p>
                <p class="mb-0 text-muted"><small>Enviada: {{ $postulacion->fecha_envio?->format('d/m/Y H:i') ?? '—' }}</small></p>
            </div>
        </div>

        @can('VALIDAR DOCUMENTO BANCO AITG')
            @if(in_array($postulacion->estado, ['pendiente_revision']))
                <div class="aitg-card aitg-card--warning mb-3">
                    <div class="aitg-card__body">
                        <form action="{{ route('aitg.validacion-banco.devolver', $postulacion) }}" method="POST" class="mb-3">
                            @csrf
                            <label for="observaciones_devolver"><strong>Devolver postulación al aspirante</strong></label>
                            <textarea id="observaciones_devolver" name="observaciones" class="form-control mb-2" rows="2" required placeholder="Indique qué debe corregir el aspirante..."></textarea>
                            <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-undo"></i> Devolver para corrección</button>
                        </form>
                        @if(! $postulacion->requierePerfil() && $postulacion->esBancoTalento())
                            {{-- El banco solo acredita documentos; no avanza a formalización --}}
                        @endif
                    </div>
                </div>
            @endif
        @endcan

        @php
            $validacionService = app(\App\Services\Aitg\Banco\AitgBancoValidacionService::class);
            $itemsService = app(\App\Services\Aitg\Postulacion\AitgPostulacionItemsService::class);
            $idsPendientes = $archivosPendientes->pluck('id')->all();
            $puedeValidarLote = $postulacion->estado === 'pendiente_revision' && $archivosPendientes->isNotEmpty();
        @endphp

        @if($puedeValidarLote)
            @can('VALIDAR DOCUMENTO BANCO AITG')
                <form action="{{ route('aitg.validacion-banco.validar-lote', $postulacion) }}" method="POST" id="form-validacion-lote">
                    @csrf
            @endcan
        @endif

        @if($postulacion->estado === 'pendiente_revision' && $archivosFase->isEmpty())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                Esta postulación está en revisión pero <strong>no tiene documentos del checklist cargados</strong>.
                Devuélvala al aspirante para que suba los PDFs por cada criterio documental.
            </div>
        @endif

        @forelse($archivosFase as $archivoPostulacion)
            @php
                $archivo = $archivoPostulacion->archivoTalento;
                $titulo = $itemsService->etiquetaArchivo($archivoPostulacion);
                $faseDoc = $archivoPostulacion->tipoArchivo?->fase_carga ?? 'inicial';
                $esPendiente = in_array($archivoPostulacion->id, $idsPendientes, true);
                $corregido = $validacionService->fueCorregidoTrasRechazo($archivoPostulacion);
                $motivoActual = $validacionService->motivoRechazoActual($archivoPostulacion);
            @endphp
            <div class="aitg-card aitg-card--success mb-3 {{ $corregido ? 'border border-warning' : '' }}">
                <div class="aitg-card__header py-2">
                    <h4 class="aitg-card__title mb-0">
                        {{ $titulo }}
                        <small class="text-muted">({{ \App\Models\Aitg\Banco\TipoArchivo::FASES_CARGA[$faseDoc] ?? $faseDoc }})</small>
                        <span class="badge badge-{{ match($archivoPostulacion->estado) {
                            'aprobado' => 'success',
                            'rechazado' => 'danger',
                            'en_revision' => 'info',
                            default => 'secondary'
                        } }} ml-2">{{ ucfirst(str_replace('_', ' ', $archivoPostulacion->estado)) }}</span>
                        @if($corregido)
                            <span class="badge badge-warning ml-1"><i class="fas fa-sync"></i> Corregido por aspirante</span>
                        @endif
                    </h4>
                </div>
                <div class="aitg-card__body">
                    @if($archivo)
                        <p><strong>Archivo:</strong> {{ $archivo->nombre_original }}
                            <small class="text-muted">· actualizado {{ $archivo->updated_at->format('d/m/Y H:i') }}</small>
                        </p>
                        <div class="mb-3">
                            <a href="{{ route('aitg.banco-instructores.archivos.ver', $archivo) }}" class="btn btn-sm btn-info" target="_blank">
                                <i class="fas fa-eye"></i> Ver documento
                            </a>
                            <a href="{{ route('aitg.banco-instructores.archivos.download', $archivo) }}" class="btn btn-sm btn-outline-primary ml-1">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Archivo no disponible.</p>
                    @endif

                    @if($motivoActual && $archivoPostulacion->estado === 'rechazado')
                        <div class="alert alert-danger py-2 small mb-3">
                            <strong>Último motivo de rechazo:</strong> {{ $motivoActual }}
                        </div>
                    @endif

                    @can('VALIDAR DOCUMENTO BANCO AITG')
                        @if($puedeValidarLote && $esPendiente)
                            <div class="aitg-validacion-fila border-top pt-3 mt-2" data-archivo-id="{{ $archivoPostulacion->id }}">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="validacion_resultado_{{ $archivoPostulacion->id }}">Decisión <span class="text-danger">*</span></label>
                                        <select id="validacion_resultado_{{ $archivoPostulacion->id }}" name="validaciones[{{ $archivoPostulacion->id }}][resultado]" class="form-control aitg-toggle-rechazo" required>
                                            <option value="">Seleccione...</option>
                                            <option value="aprobado">Aprobar</option>
                                            <option value="rechazado">Rechazar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group aitg-campo-motivo" style="display:none">
                                        <label for="validacion_motivo_{{ $archivoPostulacion->id }}">Motivo de rechazo <span class="text-danger">*</span></label>
                                        <select id="validacion_motivo_{{ $archivoPostulacion->id }}" name="validaciones[{{ $archivoPostulacion->id }}][motivo_rechazo_id]" class="form-control aitg-motivo-select">
                                            <option value="">Seleccione motivo...</option>
                                            @foreach($motivosRechazo as $motivo)
                                                <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <label for="validacion_descripcion_{{ $archivoPostulacion->id }}">Descripción (opcional)</label>
                                        <textarea id="validacion_descripcion_{{ $archivoPostulacion->id }}" name="validaciones[{{ $archivoPostulacion->id }}][descripcion]" rows="2" class="form-control" placeholder="Indique detalles adicionales..."></textarea>
                                    </div>
                                </div>
                            </div>
                        @elseif($archivoPostulacion->estado === 'aprobado')
                            <p class="text-success small mb-0"><i class="fas fa-check-circle"></i> Documento ya validado como aprobado.</p>
                        @endif
                    @endcan

                    @if($archivoPostulacion->validaciones->isNotEmpty())
                        <div class="mt-3">
                            <strong>Historial de validación:</strong>
                            <ul class="mb-0 mt-1 small">
                                @foreach($archivoPostulacion->validaciones->sortByDesc('fecha_validacion') as $v)
                                    <li>
                                        {{ $v->fecha_validacion->format('d/m/Y H:i') }} —
                                        <strong>{{ ucfirst($v->resultado) }}</strong>
                                        @if($v->motivoRechazo) ({{ $v->motivoRechazo->nombre }}) @endif
                                        @if($v->descripcion) — {{ $v->descripcion }} @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">No hay documentos en la fase actual para validar.</p>
        @endforelse

        @if($puedeValidarLote)
            @can('VALIDAR DOCUMENTO BANCO AITG')
                    <div class="text-right mb-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-registrar-validaciones">
                            <i class="fas fa-gavel"></i> Registrar todas las validaciones
                        </button>
                        <p class="text-muted small mt-2 mb-0">Complete la decisión (y motivo si rechaza) en <strong>todos</strong> los documentos pendientes antes de registrar.</p>
                    </div>
                </form>
            @endcan
        @endif
    </div>
</section>
@endsection
