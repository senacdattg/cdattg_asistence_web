<form action="{{ route('entradaSalida.store') }}" method="post">
    @csrf
    <label for="aprendiz">Leer QR Aprendiz</label>
    <input type="text" name="aprendiz">
    <input type="hidden" name="ficha_caracterizacion_id" value="{{ $ficha }}">

    <button type="submit" class="btn btn-success btn-sm">Leer</button>
</form>
