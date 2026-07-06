@extends('biogjgas.layout.public')

@section('title', 'Podcast | BIOGJGAS')

@section('biogjgas_content')
    @php
        $pageTitle = 'Podcast';
        $pageIntro = 'Episodios de divulgación científica.';
        $items = $episodios;
        $titleField = 'titulo';
        $subtitleField = 'invitados';
        $excerptField = 'descripcion';
        $showRoute = 'biogjgas.podcast.show';
        $routeParam = null;
        $breadcrumb = [['label' => 'Podcast']];
    @endphp
    @include('biogjgas.public.partials.content-list')
@endsection
