@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Título *</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $r->titulo ?? '') }}" required></div>
<div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $r->descripcion ?? '') }}</textarea></div>
<div class="row">
    <div class="col-md-6"><div class="form-group"><label>URL audio</label><input type="url" name="audio_url" class="form-control" value="{{ old('audio_url', $r->audio_url ?? '') }}"></div></div>
    <div class="col-md-3"><div class="form-group"><label>Duración</label><input type="text" name="duracion" class="form-control" value="{{ old('duracion', $r->duracion ?? '') }}"></div></div>
    <div class="col-md-3"><div class="form-group"><label>Fecha</label><input type="date" name="fecha" class="form-control" value="{{ old('fecha', isset($r->fecha) ? $r->fecha->format('Y-m-d') : '') }}"></div></div>
</div>
<div class="form-group"><label>Invitados</label><input type="text" name="invitados" class="form-control" value="{{ old('invitados', $r->invitados ?? '') }}"></div>
<div class="form-group"><label>Orden</label><input type="number" name="orden" class="form-control" value="{{ old('orden', $r->orden ?? 0) }}"></div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
