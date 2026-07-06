@extends('biogjgas.layout.public')

@section('title', 'Actividades investigativas | BIOGJGAS')

@section('biogjgas_content')
    @php
        $pageTitle = 'Actividades investigativas';
        $pageIntro = 'Talleres, socializaciones, ferias y eventos del área.';
        $items = $actividades;
        $titleField = 'titulo';
        $subtitleField = 'tipo';
        $excerptField = 'descripcion';
        $showRoute = 'biogjgas.actividades.show';
        $routeParam = null;
        $breadcrumb = [['label' => 'Actividades']];
    @endphp
    @include('biogjgas.public.partials.content-list')
@endsection
