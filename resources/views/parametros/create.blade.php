<form method="POST" action="{{ route('parametro.store') }}">
    @csrf
    <div class="form-group p-3">
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="Nombre del parámetro">
        <button type="submit" class="btn btn-success mt-3 btn-sm">Crear</button>
    </div>
</form>
