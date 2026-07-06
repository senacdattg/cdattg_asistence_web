@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Semillero *</label>
    <select name="semillero_id" class="form-control" required><option value="">Seleccione</option>
        @foreach($semilleros as $s)<option value="{{ $s->id }}" @selected(old('semillero_id', $r->semillero_id ?? '') == $s->id)>{{ $s->sigla }} - {{ $s->nombre }}</option>@endforeach
    </select>
</div>
<div class="form-group"><label>Nombre *</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre', $r->nombre ?? '') }}" required></div>
<div class="form-group"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $r->descripcion ?? '') }}</textarea></div>
<div class="form-group"><label>Orden</label><input type="number" name="orden" class="form-control" value="{{ old('orden', $r->orden ?? 0) }}"></div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
