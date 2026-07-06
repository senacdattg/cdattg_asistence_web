@php
    $banner = $banner ?? null;
@endphp

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="titulo">Título <span class="text-danger">*</span></label>
            <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror"
                value="{{ old('titulo', $banner->titulo ?? '') }}" maxlength="200" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="orden">Orden</label>
            <input type="number" name="orden" id="orden" min="0"
                class="form-control @error('orden') is-invalid @enderror"
                value="{{ old('orden', $banner->orden ?? 0) }}">
            @error('orden')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="subtitulo">Subtítulo</label>
    <input type="text" name="subtitulo" id="subtitulo" class="form-control @error('subtitulo') is-invalid @enderror"
        value="{{ old('subtitulo', $banner->subtitulo ?? '') }}" maxlength="300">
    @error('subtitulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="enlace">Enlace (opcional)</label>
    <input type="url" name="enlace" id="enlace" class="form-control @error('enlace') is-invalid @enderror"
        value="{{ old('enlace', $banner->enlace ?? '') }}" placeholder="https://">
    @error('enlace')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="vigente_desde">Vigente desde</label>
            <input type="date" name="vigente_desde" id="vigente_desde"
                class="form-control @error('vigente_desde') is-invalid @enderror"
                value="{{ old('vigente_desde', isset($banner->vigente_desde) ? $banner->vigente_desde->format('Y-m-d') : '') }}">
            @error('vigente_desde')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="vigente_hasta">Vigente hasta</label>
            <input type="date" name="vigente_hasta" id="vigente_hasta"
                class="form-control @error('vigente_hasta') is-invalid @enderror"
                value="{{ old('vigente_hasta', isset($banner->vigente_hasta) ? $banner->vigente_hasta->format('Y-m-d') : '') }}">
            @error('vigente_hasta')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="estado_publicacion">Estado <span class="text-danger">*</span></label>
            <select name="estado_publicacion" id="estado_publicacion"
                class="form-control @error('estado_publicacion') is-invalid @enderror" required>
                @foreach (['borrador', 'publicado', 'archivado'] as $estado)
                    <option value="{{ $estado }}"
                        @selected(old('estado_publicacion', $banner->estado_publicacion ?? 'borrador') === $estado)>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
            @error('estado_publicacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
