@extends('aitg.layouts.spa')

@section('title', 'Nueva convocatoria - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Nueva convocatoria',
        'subtitle' => 'Crear proceso de contratación',
        'breadcrumb' => [
            ['label' => 'Convocatorias', 'url' => route('aitg.convocatorias.index'), 'icon' => 'fa-bullhorn'],
            ['label' => 'Nueva', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @include('aitg.convocatorias.partials.form-convocatoria', ['action' => route('aitg.convocatorias.store')])
    </div>
</section>
@endsection
