<form method="POST" action="{{ route('tema.store') }}">
    @csrf
    <div class="form-group p-3">
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="Nombre del tema">
        <button type="submit" class="btn btn-success mt-3 btn-sm">Crear</button>
    </div>
</form>
