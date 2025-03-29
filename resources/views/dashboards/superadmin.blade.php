@extends('adminlte::page')
@push('css')
<link rel="stylesheet" href="{{ asset('admin-lte/dist/css/adminlte.min.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .dashboard-card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .card-title {
        font-weight: 500;
        color: #2d3748;
    }
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
</style>
@endpush

@section('title', 'Dashboard')
@section('content_header')
    <section class="content-header mt-0">
        <div class="container-fluid bg-light mt-0">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <h1 class="px-2">
                        Bienvenido, <span class="text-success">Super Administrador</span>
                    </h1>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-flex flex-column align-items-end">
                        <div class="d-flex align-items-center">
                            <ol class="breadcrumb mt-0 mb-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.dashboard.stats-cards')
    @include('components.dashboard.charts')
    @include('components.dashboard.info-lists')
    @include('components.dashboard.widgets')
    @include('components.dashboard.charts-scripts')

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $('.collapse').collapse();
    });
</script>
@endpush