{{-- <div class="card-body p-0"> --}}

<form method="POST" action="{{ route('crearParametro') }}">
    @csrf
    <div class="form-group p-3">
        <label for="parametro">Crear Parámetro:</label>
        <input type="text" name="name" id="parametro" class="form-control" required>
        <button type="submit" class="btn btn-success mt-2">Crear</button>
    </div>
</form>
