<form method="POST" action="{{ route('regional.store') }}">
    @csrf
    <div class="form-group p-3">
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required autofocus
            placeholder="Nombre de la Regional">
        <button type="submit" class="btn btn-success mt-3 btn-sm">Crear</button>
    </div>
</form>
