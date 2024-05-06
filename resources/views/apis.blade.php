<form action="{{ route('api.fichaCaracterizacion.index') }}" method="get">
    @csrf
    <input type="hidden" value="1" name="id">
    <button type="submit">fichas instructor</button>
</form>
