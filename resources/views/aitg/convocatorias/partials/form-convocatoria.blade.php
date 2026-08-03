@props(['convocatoria' => null, 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="aitg-card aitg-card--primary mb-4">
        <div class="aitg-card__header py-2"><h4 class="aitg-card__title mb-0">1. Información general</h4></div>
        <div class="aitg-card__body">
            <div class="row">
                <div class="col-md-8 form-group">
                    <label for="titulo">Título de la convocatoria <span class="text-danger">*</span></label>
                    <input type="text" id="titulo" name="titulo" class="form-control" required maxlength="255"
                        value="{{ old('titulo', $convocatoria?->titulo) }}"
                        placeholder="Ej.: Instructor – Gestión en procesos gastronómicos – 2026">
                </div>
                <div class="col-md-4 form-group">
                    <label for="estado">Estado manual <span class="text-danger">*</span></label>
                    @if($convocatoria && in_array($convocatoria->estado, ['cerrada', 'finalizada'], true))
                        <input type="text" id="estado" class="form-control" readonly value="{{ $convocatoria->estado_label }} (automático)">
                        <small class="text-muted">Cerrada y finalizada se asignan automáticamente.</small>
                    @else
                        <select id="estado" name="estado" class="form-control" required>
                            @foreach($estados as $val => $label)
                                <option value="{{ $val }}" @selected(old('estado', $convocatoria?->estado ?? 'borrador') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Borrador: prueba interna. Publicada: visible para aspirantes. Cerrada/finalizada son automáticas.</small>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="competencia_id">Competencia <span class="text-danger">*</span></label>
                    <select name="competencia_id" id="competencia_id" class="form-control" required
                        data-planes-url="{{ route('aitg.convocatorias.planes-por-competencia') }}">
                        <option value="">Seleccione...</option>
                        @foreach($competencias as $c)
                            <option value="{{ $c->id }}" @selected(old('competencia_id', $convocatoria?->competencia_id) == $c->id)>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="plan_contratacion_id">Plan de contratación <span class="text-danger">*</span></label>
                    <select name="plan_contratacion_id" id="plan_contratacion_id" class="form-control" required>
                        <option value="">Seleccione competencia primero...</option>
                        @foreach($planes as $p)
                            <option value="{{ $p->id }}" @selected(old('plan_contratacion_id', $convocatoria?->plan_contratacion_id) == $p->id)>
                                {{ $p->competencia->nombre ?? 'Plan' }} · {{ $p->periodo }} · {{ $p->modalidad_label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3">{{ old('descripcion', $convocatoria?->descripcion) }}</textarea>
            </div>
            <div class="form-group">
                <label for="objeto_contractual">Objeto contractual</label>
                <textarea id="objeto_contractual" name="objeto_contractual" class="form-control" rows="3">{{ old('objeto_contractual', $convocatoria?->objeto_contractual) }}</textarea>
            </div>
            <div class="form-group mb-0">
                <label for="requisitos">Requisitos adicionales</label>
                <textarea id="requisitos" name="requisitos" class="form-control" rows="3">{{ old('requisitos', $convocatoria?->requisitos) }}</textarea>
            </div>
        </div>
    </div>

    <div class="aitg-card aitg-card--info mb-4">
        <div class="aitg-card__header py-2"><h4 class="aitg-card__title mb-0">2. Fechas del proceso</h4></div>
        <div class="aitg-card__body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="fecha_inicio_publicacion">Inicio publicación <span class="text-danger js-req-publicada">*</span></label>
                    <input type="date" id="fecha_inicio_publicacion" name="fecha_inicio_publicacion" class="form-control" value="{{ old('fecha_inicio_publicacion', $convocatoria?->fecha_inicio_publicacion?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3 form-group">
                    <label for="fecha_fin_publicacion">Fin publicación <span class="text-danger js-req-publicada">*</span></label>
                    <input type="date" id="fecha_fin_publicacion" name="fecha_fin_publicacion" class="form-control" value="{{ old('fecha_fin_publicacion', $convocatoria?->fecha_fin_publicacion?->format('Y-m-d')) }}">
                    <small class="text-muted">Al vencer, la convocatoria pasa a <strong>Cerrada</strong> automáticamente.</small>
                </div>
                <div class="col-md-3 form-group">
                    <label for="fecha_inicio_contrato">Inicio contrato</label>
                    <input type="date" id="fecha_inicio_contrato" name="fecha_inicio_contrato" class="form-control" value="{{ old('fecha_inicio_contrato', $convocatoria?->fecha_inicio_contrato?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3 form-group">
                    <label for="fecha_fin_contrato">Fin contrato</label>
                    <input type="date" id="fecha_fin_contrato" name="fecha_fin_contrato" class="form-control" value="{{ old('fecha_fin_contrato', $convocatoria?->fecha_fin_contrato?->format('Y-m-d')) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="aitg-card aitg-card--warning mb-4">
        <div class="aitg-card__header py-2"><h4 class="aitg-card__title mb-0">3. Presupuesto</h4></div>
        <div class="aitg-card__body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="codigo_cdp">Código CDP</label>
                    <input type="text" id="codigo_cdp" name="codigo_cdp" class="form-control" value="{{ old('codigo_cdp', $convocatoria?->codigo_cdp) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="valor_total">Valor total convocatoria</label>
                    <input type="number" step="0.01" min="0" id="valor_total" name="valor_total" class="form-control" value="{{ old('valor_total', $convocatoria?->valor_total) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="valor_contrato_honorarios">Valor contrato / honorarios</label>
                    <input type="number" step="0.01" min="0" id="valor_contrato_honorarios" name="valor_contrato_honorarios" class="form-control" value="{{ old('valor_contrato_honorarios', $convocatoria?->valor_contrato_honorarios) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="aitg-card aitg-card--success mb-4">
        <div class="aitg-card__header py-2"><h4 class="aitg-card__title mb-0">4. Ubicación y clasificación</h4></div>
        <div class="aitg-card__body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="centro_formacion_id">Centro de formación</label>
                    <select id="centro_formacion_id" name="centro_formacion_id" class="form-control">
                        <option value="">—</option>
                        @foreach($centros as $c)
                            <option value="{{ $c->id }}" @selected(old('centro_formacion_id', $convocatoria?->centro_formacion_id) == $c->id)>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="regional_id">Regional</label>
                    <select id="regional_id" name="regional_id" class="form-control">
                        <option value="">—</option>
                        @foreach($regionales as $r)
                            <option value="{{ $r->id }}" @selected(old('regional_id', $convocatoria?->regional_id) == $r->id)>{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="text-right mb-4">
        <a href="{{ route('aitg.convocatorias.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar convocatoria</button>
    </div>
</form>
