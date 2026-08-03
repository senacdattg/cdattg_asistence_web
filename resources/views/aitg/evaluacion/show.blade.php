@extends('aitg.layouts.spa')

@section('title', 'Evaluar postulación - AITG')

@section('aitg_header')
    @php
        $persona = $evaluacion->postulacion->user->persona;
        $nombre = trim(($persona->primer_nombre ?? '') . ' ' . ($persona->primer_apellido ?? '')) ?: $evaluacion->postulacion->user->email;
    @endphp
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Evaluación documental — ' . $nombre,
        'subtitle' => $evaluacion->postulacion->convocatoria->titulo ?? '',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Evaluación', 'url' => route('aitg.evaluacion.index')],
            ['label' => 'Postulaciones', 'url' => route('aitg.evaluacion.postulaciones', $evaluacion->postulacion->convocatoria_id)],
            ['label' => 'Evaluar', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mb-3">
            <div class="col-md-8">
                <p class="mb-1"><strong>Documento:</strong> {{ $persona->numero_documento ?? '—' }}</p>
                <p class="mb-1"><strong>Perfil:</strong> {{ $evaluacion->postulacion->perfilPlan->descripcion_criterio ?? '—' }}</p>
                <p class="mb-0"><strong>Estado:</strong> <span class="badge badge-info">{{ $evaluacion->estado_label }}</span></p>
            </div>
            <div class="col-md-4 text-md-right">
                <div class="card bg-light">
                    <div class="card-body py-2">
                        <small class="text-muted d-block">Puntaje calculado automáticamente</small>
                        <strong>% Checklist:</strong> {{ number_format($evaluacion->puntaje_checklist, 2) }}<br>
                        <strong>Bonus adicionales:</strong> +{{ number_format($evaluacion->puntaje_adicionales, 2) }}<br>
                        <strong class="text-primary">Total ranking:</strong> {{ number_format($evaluacion->puntaje_total, 2) }}
                    </div>
                </div>
            </div>
        </div>

        @if($evaluacion->finalizada())
            <div class="alert alert-info">Esta evaluación ya fue finalizada el {{ $evaluacion->fecha_finalizacion?->format('d/m/Y H:i') }}.</div>
        @endif

        <form method="POST" action="{{ route('aitg.evaluacion.guardar', $evaluacion) }}">
            @csrf

            <div class="aitg-card aitg-card--primary mb-3">
                <div class="aitg-card__header"><h3 class="h6 mb-0">Checklist documental del plan</h3></div>
                <div class="aitg-card__body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Criterio</th>
                                    <th>Peso (%)</th>
                                    <th>Documentos cargados</th>
                                    <th>Resultado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluacion->postulacion->checklistItems->sortBy('orden') as $item)
                                    @php
                                        $archivo = $item->postulacionArchivo?->archivoTalento;
                                        $totalChk = $evaluacion->postulacion->checklistItems->count();
                                        $pesoItem = $totalChk > 0 ? round(100 / $totalChk, 2) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $item->nombre }}</strong>
                                            <br><small class="text-muted">{{ $item->descripcion_criterio }}</small>
                                            @if($item->es_obligatorio)
                                                <br><span class="badge badge-warning">Obligatorio</span>
                                            @endif
                                            <br><span class="badge badge-secondary">{{ $item->estado_label }}</span>
                                        </td>
                                        <td>{{ number_format($pesoItem, 2) }}%</td>
                                        <td>
                                            @if($archivo)
                                                <small>{{ $archivo->nombre_original }}</small><br>
                                                <a href="{{ route('aitg.banco-instructores.archivos.ver', $archivo) }}" target="_blank" class="btn btn-xs btn-outline-primary">Ver</a>
                                                <a href="{{ route('aitg.banco-instructores.archivos.download', $archivo) }}" class="btn btn-xs btn-outline-secondary">Descargar</a>
                                            @else
                                                <span class="text-muted">Sin evidencia</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($evaluacion->puedeEvaluar())
                                                <select name="checklist[{{ $item->id }}][cumple]" class="form-control form-control-sm">
                                                    <option value="">—</option>
                                                    <option value="1" @selected($item->cumple === true)>Cumple</option>
                                                    <option value="0" @selected($item->cumple === false)>No cumple</option>
                                                </select>
                                            @else
                                                @if($item->cumple === true)
                                                    <span class="text-success"><i class="fas fa-check"></i> Cumple</span>
                                                @elseif($item->cumple === false)
                                                    <span class="text-danger"><i class="fas fa-times"></i> No cumple</span>
                                                @else
                                                    —
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($evaluacion->puedeEvaluar())
                                                <textarea name="checklist[{{ $item->id }}][observaciones]" rows="2" class="form-control form-control-sm">{{ old('checklist.'.$item->id.'.observaciones', $item->observaciones) }}</textarea>
                                            @else
                                                {{ $item->observaciones ?: '—' }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No hay criterios de checklist en esta postulación.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="aitg-card aitg-card--primary mb-3">
                <div class="aitg-card__header"><h3 class="h6 mb-0">Puntos adicionales</h3></div>
                <div class="aitg-card__body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Punto adicional</th>
                                    <th>Puntaje</th>
                                    <th>Evidencia</th>
                                    <th>Resultado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluacion->postulacion->puntoItems->sortBy('orden') as $item)
                                    @php $archivo = $item->postulacionArchivo?->archivoTalento; @endphp
                                    <tr>
                                        <td>{{ $item->descripcion }}</td>
                                        <td>+{{ number_format($item->puntaje_adicional, 2) }}</td>
                                        <td>
                                            @if($archivo)
                                                <a href="{{ route('aitg.banco-instructores.archivos.ver', $archivo) }}" target="_blank" class="btn btn-xs btn-outline-primary">Ver</a>
                                                <a href="{{ route('aitg.banco-instructores.archivos.download', $archivo) }}" class="btn btn-xs btn-outline-secondary">Descargar</a>
                                            @else
                                                <span class="text-muted">Sin evidencia</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($evaluacion->puedeEvaluar() && $item->tieneDocumento())
                                                <select name="puntos[{{ $item->id }}][cumple]" class="form-control form-control-sm">
                                                    <option value="">—</option>
                                                    <option value="1" @selected($item->cumple === true)>Cumple</option>
                                                    <option value="0" @selected($item->cumple === false)>No cumple</option>
                                                </select>
                                            @elseif(! $item->tieneDocumento())
                                                <span class="text-muted">N/A</span>
                                            @else
                                                @if($item->cumple === true)
                                                    <span class="text-success"><i class="fas fa-check"></i> Cumple (+{{ number_format($item->puntaje_adicional, 2) }})</span>
                                                @elseif($item->cumple === false)
                                                    <span class="text-danger"><i class="fas fa-times"></i> No cumple</span>
                                                @else
                                                    —
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($evaluacion->puedeEvaluar() && $item->tieneDocumento())
                                                <textarea name="puntos[{{ $item->id }}][observaciones]" rows="2" class="form-control form-control-sm">{{ old('puntos.'.$item->id.'.observaciones', $item->observaciones) }}</textarea>
                                            @else
                                                {{ $item->observaciones ?: '—' }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Este plan no tiene puntos adicionales configurados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="aitg-card aitg-card--primary mb-3">
                <div class="aitg-card__body">
                    <div class="form-group">
                        <label for="observaciones_evaluacion">Observaciones generales de la evaluación</label>
                        @if($evaluacion->puedeEvaluar())
                            <textarea id="observaciones_evaluacion" name="observaciones" rows="3" class="form-control">{{ old('observaciones', $evaluacion->observaciones) }}</textarea>
                        @else
                            <p id="observaciones_evaluacion" class="mb-0">{{ $evaluacion->observaciones ?: '—' }}</p>
                        @endif
                    </div>

                    @if($evaluacion->puedeEvaluar())
                        <div class="form-group mb-0">
                            <label for="resultado_evaluacion">Resultado final (opcional — rechazo explícito)</label>
                            <select id="resultado_evaluacion" name="resultado" class="form-control col-md-4">
                                <option value="">Determinar por criterios obligatorios</option>
                                <option value="aprobado">Aprobar evaluación</option>
                                <option value="rechazado">Rechazar evaluación</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            @if($evaluacion->puedeEvaluar())
                <div class="d-flex flex-wrap">
                    <button type="submit" class="btn btn-secondary mr-2">
                        <i class="fas fa-save"></i> Guardar avance
                    </button>
                    <button type="submit" class="btn btn-success mr-2"
                        formaction="{{ route('aitg.evaluacion.finalizar', $evaluacion) }}"
                        onclick="return confirm('¿Finalizar la evaluación? Esta acción actualizará el estado del aspirante.');">
                        <i class="fas fa-check-circle"></i> Finalizar evaluación
                    </button>
                </div>
            @endif
        </form>

        <div class="mt-3">
            <a href="{{ route('aitg.evaluacion.postulaciones', $evaluacion->postulacion->convocatoria_id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>
</section>
@endsection
