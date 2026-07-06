@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Título *</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $r->titulo ?? '') }}" required></div>
<div class="row">
    <div class="col-md-3"><div class="form-group"><label>Tipo</label><input type="text" name="tipo" class="form-control" value="{{ old('tipo', $r->tipo ?? '') }}"></div></div>
    <div class="col-md-3"><div class="form-group"><label>Fecha</label><input type="date" name="fecha" class="form-control" value="{{ old('fecha', isset($r->fecha) ? $r->fecha->format('Y-m-d') : '') }}"></div></div>
    <div class="col-md-3"><div class="form-group"><label>Modalidad</label><input type="text" name="modalidad" class="form-control" value="{{ old('modalidad', $r->modalidad ?? '') }}"></div></div>
    <div class="col-md-3"><div class="form-group"><label>Estado actividad *</label>
        <select name="estado_actividad" class="form-control" required>
            @foreach (['programada','realizada','cancelada'] as $e)<option value="{{ $e }}" @selected(old('estado_actividad', $r->estado_actividad ?? 'programada') === $e)>{{ ucfirst($e) }}</option>@endforeach
        </select>
    </div></div>
</div>
<div class="form-group"><label>Lugar</label><input type="text" name="lugar" class="form-control" value="{{ old('lugar', $r->lugar ?? '') }}"></div>
<div class="form-group"><label>Semillero</label>
    <select name="semillero_id" class="form-control"><option value="">— Ninguno —</option>
        @foreach($semilleros as $s)<option value="{{ $s->id }}" @selected(old('semillero_id', $r->semillero_id ?? '') == $s->id)>{{ $s->sigla }}</option>@endforeach
    </select>
</div>
<div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $r->descripcion ?? '') }}</textarea></div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
