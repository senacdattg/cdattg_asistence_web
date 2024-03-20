{{-- <div class="card-body p-0"> --}}

<form method="POST" action="{{ route('parametro.store') }}">
    @csrf
    <div class="form-group p-3">
        <label for="name">Crear Parametro</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
        <input type="hidden" name="status" id="status" value="1">
        <button type="submit" class="btn btn-success mt-2">Crear</button>
    </div>
</form>
