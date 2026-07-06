@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Misión</label><textarea name="mision" class="form-control" rows="2">{{ old('mision', $r->mision ?? '') }}</textarea></div>
<div class="form-group"><label>Visión</label><textarea name="vision" class="form-control" rows="2">{{ old('vision', $r->vision ?? '') }}</textarea></div>
<div class="form-group"><label>Objetivo general</label><textarea name="objetivo_general" class="form-control" rows="2">{{ old('objetivo_general', $r->objetivo_general ?? '') }}</textarea></div>
<div class="form-group"><label>Historia</label><textarea name="historia" class="form-control" rows="5">{{ old('historia', $r->historia ?? '') }}</textarea></div>
<div class="form-group"><label>URL video institucional</label><input type="url" name="video_url" class="form-control" value="{{ old('video_url', $r->video_url ?? '') }}"></div>
<div class="form-group"><label>Ruta PDF políticas</label><input type="text" name="politicas_pdf" class="form-control" value="{{ old('politicas_pdf', $r->politicas_pdf ?? '') }}"></div>
<div class="form-group">
    <label>Equipo directivo (nombre | cargo | contacto, uno por línea)</label>
    <textarea name="equipo_texto" class="form-control" rows="4">@if($r && is_array($r->equipo))@foreach($r->equipo as $m){{ $m['nombre'] ?? '' }}|{{ $m['cargo'] ?? '' }}|{{ $m['contacto'] ?? '' }}
@endforeach @else{{ old('equipo_texto') }}@endif</textarea>
</div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
