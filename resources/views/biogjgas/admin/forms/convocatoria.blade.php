@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Título *</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $r->titulo ?? '') }}" required></div>
<div class="row">
    <div class="col-md-4"><div class="form-group"><label>Tipo</label><input type="text" name="tipo" class="form-control" value="{{ old('tipo', $r->tipo ?? '') }}"></div></div>
    <div class="col-md-4"><div class="form-group"><label>Estado convocatoria *</label>
        <select name="estado_convocatoria" class="form-control" required>
            @foreach (['abierta','cerrada','proximamente'] as $e)<option value="{{ $e }}" @selected(old('estado_convocatoria', $r->estado_convocatoria ?? 'proximamente') === $e)>{{ ucfirst($e) }}</option>@endforeach
        </select>
    </div></div>
    <div class="col-md-4"><div class="form-group"><label>Semillero</label>
        <select name="semillero_id" class="form-control"><option value="">— Ninguno —</option>
            @foreach($semilleros as $s)<option value="{{ $s->id }}" @selected(old('semillero_id', $r->semillero_id ?? '') == $s->id)>{{ $s->sigla }} - {{ $s->nombre }}</option>@endforeach
        </select>
    </div></div>
</div>
<div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $r->descripcion ?? '') }}</textarea></div>
<div class="form-group"><label>Requisitos</label><textarea name="requisitos" class="form-control" rows="3">{{ old('requisitos', $r->requisitos ?? '') }}</textarea></div>
<div class="row">
    <div class="col-md-6"><div class="form-group"><label>Apertura</label><input type="date" name="fecha_apertura" class="form-control" value="{{ old('fecha_apertura', isset($r->fecha_apertura) ? $r->fecha_apertura->format('Y-m-d') : '') }}"></div></div>
    <div class="col-md-6"><div class="form-group"><label>Cierre</label><input type="date" name="fecha_cierre" class="form-control" value="{{ old('fecha_cierre', isset($r->fecha_cierre) ? $r->fecha_cierre->format('Y-m-d') : '') }}"></div></div>
</div>
<div class="form-group"><label>Enlace externo</label><input type="url" name="enlace_externo" class="form-control" value="{{ old('enlace_externo', $r->enlace_externo ?? '') }}"></div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
