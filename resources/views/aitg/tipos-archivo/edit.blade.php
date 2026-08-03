@extends('aitg.layouts.spa')

@section('title', 'Editar tipo de archivo AITG')

@section('aitg_header')<h1>Editar tipo de archivo</h1>@endsection

@section('aitg_content')
<div class="container-fluid">
    @include('aitg.tipos-archivo.partials.form', [
        'action' => route('aitg.tipos-archivo.update', $tipo),
        'method' => 'PUT',
        'tipo' => $tipo,
    ])
</div>
@endsection
