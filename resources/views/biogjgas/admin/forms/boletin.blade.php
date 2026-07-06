@php $r = $registro ?? null; @endphp
<div class="form-group"><label>Título *</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $r->titulo ?? '') }}" required></div>
<div class="row">
    <div class="col-md-4"><div class="form-group"><label>Número</label><input type="text" name="numero" class="form-control" value="{{ old('numero', $r->numero ?? '') }}"></div></div>
    <div class="col-md-4"><div class="form-group"><label>Fecha</label><input type="date" name="fecha" class="form-control" value="{{ old('fecha', isset($r->fecha) ? $r->fecha->format('Y-m-d') : '') }}"></div></div>
    <div class="col-md-4"><div class="form-group"><label>Temática</label><input type="text" name="tematica" class="form-control" value="{{ old('tematica', $r->tematica ?? '') }}"></div></div>
</div>
<div class="form-group"><label>Resumen</label><textarea name="resumen" class="form-control" rows="3">{{ old('resumen', $r->resumen ?? '') }}</textarea></div>
<div class="row">
    <div class="col-md-6"><div class="form-group"><label>Ruta PDF</label><input type="text" name="pdf_path" class="form-control" value="{{ old('pdf_path', $r->pdf_path ?? '') }}"></div></div>
    <div class="col-md-6"><div class="form-group"><label>Ruta portada</label><input type="text" name="portada_path" class="form-control" value="{{ old('portada_path', $r->portada_path ?? '') }}"></div></div>
</div>
<div class="form-group"><label>Orden</label><input type="number" name="orden" class="form-control" value="{{ old('orden', $r->orden ?? 0) }}"></div>
@include('biogjgas.admin.partials.estado-publicacion', ['registro' => $r])
