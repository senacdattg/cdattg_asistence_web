<form action="{{ route('entradaSalida.store') }}" method="post">
    @csrf
    <label for="aprendiz">Leer QR Aprendiz</label>
    <input type="text" name="aprendiz">

    <button type="submit" class="btn btn-success btn-sm">Leer</button>
</form>
