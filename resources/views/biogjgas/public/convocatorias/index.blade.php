@extends('biogjgas.layout.public')

@section('title', 'Convocatorias | BIOGJGAS')

@section('biogjgas_content')
    @php
        $pageTitle = 'Convocatorias de investigación';
        $pageIntro = 'Oportunidades abiertas y próximas del área de investigación.';
        $items = $convocatorias;
        $titleField = 'titulo';
        $subtitleField = 'tipo';
        $excerptField = 'descripcion';
        $showRoute = 'biogjgas.convocatorias.show';
        $routeParam = null;
        $breadcrumb = [['label' => 'Convocatorias']];
    @endphp
    @include('biogjgas.public.partials.content-list')
@endsection
