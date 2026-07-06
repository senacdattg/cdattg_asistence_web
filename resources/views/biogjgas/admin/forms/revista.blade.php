@php $r = $registro ?? null; @endphp
<div class="row">
    <div class="col-md-8"><div class="form-group"><label>Título *</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $r->titulo ?? '') }}" required></div></div>
    <div class="col-md-4"><div class="form-group"><label>Año *</label><input type="number" name="anio" class="form-control" value="{{ old('anio', $r->anio ?? date('Y')) }}" required></div></div>
</div>
<div class="row">
    <div class="col-md-4"><div class="form-group"><label>Volumen</label><input type="number" name="volumen" class="form-control" value="{{ old('volumen', $r->volumen ?? '') }}"></div></div>
    <div class="col-md-4"><div class="form-group"><label>Número</label><input type="number" name="numero" class="form-control" value="{{ old('numero', $r->numero ?? '') }}"></div></div>
    <div class="col-md-4"><div class="form-group"><label>Slug URL</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $r->slug ?? '') }}"></div></div>
</div>
<div class="form-group"><label>Editorial</label><textarea name="editorial" class="form-control" rows="3">{{ old('editorial', $r->editorial ?? '') }}</textarea></div>
<div class="form-group"><label>ISSN</label><input type="text" name="issn" class="form-control" value="{{ old('issn', $r->issn ?? '') }}"></div>
<div class="form-group">
    <label>Artículos (título | autores | resumen, uno por línea)</label>
    <textarea name="articulos_texto" class="form-control" rows="5">@if($r && is_array($r->articulos))@foreach($r->articulos as $a){{ $a['titulo'] ?? '' }}|{{ $a['autores'] ?? '' }}|{{ $a['resumen'] ?? '' }}
@endforeach @else{{ old('articulos_texto') }}@endif</textarea>
</div>
<div class="row">
    <div class="col-md-6"><div class="form-group"><label>Fecha publicación</label><input type="date" name="fecha_publicacion" class="form-control" value="{{ old('fecha_publicacion', isset($r->fecha_publicacion) ? $r->fecha_publicacion->format('Y-m-d') : '') }}"></div></div>
    <div class="col-md-6"><div class="form-group"><label>Orden</label><input type="number" name="orden" class="form-control" value="{{ old('orden', $r->orden ?? 0) }}"></div></div>
</div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
