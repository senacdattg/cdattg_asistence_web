<form method="POST" action="{{ route('centros.store') }}">
    @csrf
    <div class="form-group p-3">
        <input type="text" name="centro" class="form-control" value="{{ old('centro') }}" required autofocus placeholder="Nombre del Centro de Formación">
        <button type="submit" class="btn btn-success mt-3 btn-sm">Crear</button>
    </div>
</form>
