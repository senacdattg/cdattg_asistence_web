@extends('aitg.layouts.spa')

@section('title', 'Crear motivo de rechazo')

@section('aitg_header')<h1>Nuevo motivo de rechazo</h1>@endsection

@section('aitg_content')
<div class="container-fluid">
    @include('aitg.motivos-rechazo.partials.form', [
        'action' => route('aitg.motivos-rechazo.store'),
        'method' => 'POST',
        'motivo' => null,
    ])
</div>
@endsection
