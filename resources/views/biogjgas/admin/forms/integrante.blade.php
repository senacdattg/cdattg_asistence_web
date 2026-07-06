@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Semillero *</label>
    <select name="semillero_id" class="form-control" required><option value="">Seleccione</option>
        @foreach($semilleros as $s)<option value="{{ $s->id }}" @selected(old('semillero_id', $r->semillero_id ?? '') == $s->id)>{{ $s->sigla }}</option>@endforeach
    </select>
</div>
<div class="form-group"><label>Nombre *</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre', $r->nombre ?? '') }}" required></div>
<div class="row">
    <div class="col-md-6"><div class="form-group"><label>Rol</label><input type="text" name="rol" class="form-control" value="{{ old('rol', $r->rol ?? '') }}"></div></div>
    <div class="col-md-6"><div class="form-group"><label>Programa</label><input type="text" name="programa" class="form-control" value="{{ old('programa', $r->programa ?? '') }}"></div></div>
</div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
