@php
    $semillero = $semillero ?? null;
    $objetivosTexto = old('objetivos_texto', isset($semillero) && is_array($semillero->objetivos)
        ? implode("\n", $semillero->objetivos)
        : '');
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="sigla">Sigla <span class="text-danger">*</span></label>
            <input type="text" name="sigla" id="sigla" class="form-control @error('sigla') is-invalid @enderror"
                value="{{ old('sigla', $semillero->sigla ?? '') }}" maxlength="20" required>
            @error('sigla')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label for="nombre">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                value="{{ old('nombre', $semillero->nombre ?? '') }}" maxlength="200" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="slug">Slug (URL)</label>
            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $semillero->slug ?? '') }}" placeholder="Se genera desde la sigla si se deja vacío">
            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="icono">Ícono FontAwesome</label>
            <input type="text" name="icono" id="icono" class="form-control @error('icono') is-invalid @enderror"
                value="{{ old('icono', $semillero->icono ?? 'fa-flask') }}" placeholder="fa-flask">
            @error('icono')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="color_identidad">Color identidad</label>
            <input type="color" name="color_identidad" id="color_identidad"
                class="form-control @error('color_identidad') is-invalid @enderror"
                value="{{ old('color_identidad', $semillero->color_identidad ?? '#0f9d58') }}">
            @error('color_identidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="resumen">Resumen</label>
            <textarea name="resumen" id="resumen" rows="2"
                class="form-control @error('resumen') is-invalid @enderror">{{ old('resumen', $semillero->resumen ?? '') }}</textarea>
            @error('resumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="orden">Orden</label>
            <input type="number" name="orden" id="orden" min="0"
                class="form-control @error('orden') is-invalid @enderror"
                value="{{ old('orden', $semillero->orden ?? 0) }}">
            @error('orden')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="estado_publicacion">Estado <span class="text-danger">*</span></label>
            <select name="estado_publicacion" id="estado_publicacion"
                class="form-control @error('estado_publicacion') is-invalid @enderror" required>
                @foreach (['borrador', 'publicado', 'archivado'] as $estado)
                    <option value="{{ $estado }}"
                        @selected(old('estado_publicacion', $semillero->estado_publicacion ?? 'borrador') === $estado)>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
            @error('estado_publicacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="descripcion">Descripción</label>
    <textarea name="descripcion" id="descripcion" rows="4"
        class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $semillero->descripcion ?? '') }}</textarea>
    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="mision">Misión</label>
            <textarea name="mision" id="mision" rows="3"
                class="form-control @error('mision') is-invalid @enderror">{{ old('mision', $semillero->mision ?? '') }}</textarea>
            @error('mision')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="vision">Visión</label>
            <textarea name="vision" id="vision" rows="3"
                class="form-control @error('vision') is-invalid @enderror">{{ old('vision', $semillero->vision ?? '') }}</textarea>
            @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="objetivos_texto">Objetivos (uno por línea)</label>
    <textarea name="objetivos_texto" id="objetivos_texto" rows="4"
        class="form-control @error('objetivos_texto') is-invalid @enderror">{{ $objetivosTexto }}</textarea>
    @error('objetivos_texto')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="instructor_lider">Instructor líder</label>
            <input type="text" name="instructor_lider" id="instructor_lider"
                class="form-control @error('instructor_lider') is-invalid @enderror"
                value="{{ old('instructor_lider', $semillero->instructor_lider ?? '') }}">
            @error('instructor_lider')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="correo_contacto">Correo de contacto</label>
            <input type="email" name="correo_contacto" id="correo_contacto"
                class="form-control @error('correo_contacto') is-invalid @enderror"
                value="{{ old('correo_contacto', $semillero->correo_contacto ?? '') }}">
            @error('correo_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
