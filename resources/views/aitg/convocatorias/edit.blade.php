@extends('aitg.layouts.spa')

@section('title', 'Editar convocatoria - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Editar convocatoria',
        'subtitle' => $convocatoria->codigo,
        'breadcrumb' => [
            ['label' => 'Convocatorias', 'url' => route('aitg.convocatorias.index'), 'icon' => 'fa-bullhorn'],
            ['label' => 'Editar', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @include('aitg.convocatorias.partials.form-convocatoria', [
            'convocatoria' => $convocatoria,
            'action' => route('aitg.convocatorias.update', $convocatoria),
            'method' => 'PUT',
        ])
    </div>
</section>
@endsection
