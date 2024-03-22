<form action="{{ route('entradaSalida.updateSalida') }}" method="post">
    @csrf
    <label for="aprendiz">Leer QR Aprendiz</label>
    <input type="text" name="aprendiz">
    <button type="submit" class="btn btn-danger btn-sm">Leer</button>
</form>
<h1>hola mundo</h1>
