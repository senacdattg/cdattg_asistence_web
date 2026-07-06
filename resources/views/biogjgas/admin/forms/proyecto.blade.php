@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Semillero *</label>
    <select name="semillero_id" class="form-control" required><option value="">Seleccione</option>
        @foreach($semilleros as $s)<option value="{{ $s->id }}" @selected(old('semillero_id', $r->semillero_id ?? '') == $s->id)>{{ $s->sigla }}</option>@endforeach
    </select>
</div>
<div class="form-group"><label>Título *</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $r->titulo ?? '') }}" required></div>
<div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $r->descripcion ?? '') }}</textarea></div>
<div class="row">
    <div class="col-md-4"><div class="form-group"><label>Estado ejecución *</label>
        <select name="estado_ejecucion" class="form-control" required>
            <option value="en_ejecucion" @selected(old('estado_ejecucion', $r->estado_ejecucion ?? '') === 'en_ejecucion')>En ejecución</option>
            <option value="finalizado" @selected(old('estado_ejecucion', $r->estado_ejecucion ?? '') === 'finalizado')>Finalizado</option>
        </select>
    </div></div>
    <div class="col-md-4"><div class="form-group"><label>Inicio</label><input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', isset($r->fecha_inicio) ? $r->fecha_inicio->format('Y-m-d') : '') }}"></div></div>
    <div class="col-md-4"><div class="form-group"><label>Fin</label><input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', isset($r->fecha_fin) ? $r->fecha_fin->format('Y-m-d') : '') }}"></div></div>
</div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
