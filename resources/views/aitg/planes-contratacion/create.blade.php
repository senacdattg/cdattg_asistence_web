@extends('aitg.layouts.spa')

@section('title', 'Crear Plan de Contratación - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Crear Plan de Contratación',
        'subtitle' => 'AITG - Anexo 2 · Formulario dinámico',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Planes', 'url' => route('aitg.planes-contratacion.index'), 'icon' => 'fa-file-contract'],
            ['label' => 'Crear plan', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        @include('aitg.planes-contratacion.partials.form-completo', [
            'formAction' => route('aitg.planes-contratacion.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Crear plan',
            'regionales' => $regionales,
            'nivelesFormacion' => $nivelesFormacion,
            'competencias' => $competencias,
            'aitgFormConfig' => $aitgFormConfig,
        ])
    </div>
</section>
@endsection
