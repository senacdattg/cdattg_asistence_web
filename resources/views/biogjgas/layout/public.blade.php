@extends('complementarios.layout.master')

@section('title', $title ?? 'Investigación | BIOGJGAS Guaviare')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/biogjgas/public.css') }}">
@endpush

@section('content')
    <div class="container-fluid mt-3 px-2 px-md-4 biogjgas-public" style="background-color: #f1f5f9; min-height: 100vh;">
        @yield('biogjgas_content')
    </div>
@endsection
