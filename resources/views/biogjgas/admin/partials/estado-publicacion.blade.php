@php
    $estado = old('estado_publicacion', $registro->estado_publicacion ?? 'borrador');
@endphp
<div class="form-group">
    <label for="estado_publicacion">Estado de publicación <span class="text-danger">*</span></label>
    <select name="estado_publicacion" id="estado_publicacion" class="form-control @error('estado_publicacion') is-invalid @enderror" required>
        @foreach (['borrador', 'publicado', 'archivado'] as $opcion)
            <option value="{{ $opcion }}" @selected($estado === $opcion)>{{ ucfirst($opcion) }}</option>
        @endforeach
    </select>
    @error('estado_publicacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
