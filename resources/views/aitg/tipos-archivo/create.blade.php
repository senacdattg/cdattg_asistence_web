@extends('aitg.layouts.spa')

@section('title', 'Crear tipo de archivo AITG')

@section('aitg_header')<h1>Nuevo tipo de archivo</h1>@endsection

@section('aitg_content')
<div class="container-fluid">
    @include('aitg.tipos-archivo.partials.form', [
        'action' => route('aitg.tipos-archivo.store'),
        'method' => 'POST',
        'tipo' => null,
    ])
</div>
@endsection
