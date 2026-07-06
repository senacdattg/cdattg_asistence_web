@extends('biogjgas.layout.public')

@section('title', 'Boletines | BIOGJGAS')

@section('biogjgas_content')
    @php
        $pageTitle = 'Boletines';
        $pageIntro = 'Boletines divulgativos del área de investigación.';
        $items = $boletines;
        $titleField = 'titulo';
        $subtitleField = 'tematica';
        $excerptField = 'resumen';
        $showRoute = 'biogjgas.boletines.show';
        $routeParam = null;
        $breadcrumb = [['label' => 'Boletines']];
    @endphp
    @include('biogjgas.public.partials.content-list')
@endsection
