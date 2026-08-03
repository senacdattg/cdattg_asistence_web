@extends('aitg.layouts.spa')

@section('title', 'Documento - Banco de Talento')

@section('css')
    <x-vite-stylesheet paths="resources/css/aitg/planes-contratacion/app.css" />
    <style>
        .aitg-pdf-viewer { width: 100%; min-height: 75vh; border: 1px solid #dee2e6; border-radius: 4px; }
    </style>
@endsection

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Visualizar documento',
        'subtitle' => $archivo->nombre_original,
        'breadcrumb' => [
            ['label' => 'Banco de Talento', 'url' => route('aitg.banco-instructores.index'), 'icon' => 'fa-rocket'],
            ['label' => 'Documento', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-arrow-left"></i> Volver</button>
            <a href="{{ route('aitg.banco-instructores.archivos.download', $archivo) }}" class="btn btn-outline-primary btn-sm ml-2">
                <i class="fas fa-download"></i> Descargar
            </a>
        </div>

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__body p-0">
                <iframe class="aitg-pdf-viewer" src="{{ route('aitg.banco-instructores.archivos.stream', $archivo) }}#toolbar=1"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection
