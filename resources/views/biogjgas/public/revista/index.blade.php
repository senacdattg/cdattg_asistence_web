@extends('biogjgas.layout.public')

@section('title', 'Revista Rupícola | BIOGJGAS')

@section('biogjgas_content')
    @php
        $pageTitle = 'Revista Rupícola';
        $pageIntro = 'Ediciones y artículos de divulgación científica del área de investigación.';
        $items = $ediciones;
        $titleField = 'titulo';
        $subtitleField = 'anio';
        $excerptField = 'editorial';
        $showRoute = 'biogjgas.revista.show';
        $routeParam = 'slug';
        $breadcrumb = [['label' => 'Revista Rupícola']];
    @endphp
    @include('biogjgas.public.partials.content-list')
@endsection
