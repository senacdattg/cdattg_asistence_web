@extends('aitg.layouts.spa')

@section('title', 'Editar motivo de rechazo')

@section('aitg_header')<h1>Editar motivo de rechazo</h1>@endsection

@section('aitg_content')
<div class="container-fluid">
    @include('aitg.motivos-rechazo.partials.form', [
        'action' => route('aitg.motivos-rechazo.update', $motivo),
        'method' => 'PUT',
        'motivo' => $motivo,
    ])
</div>
@endsection
