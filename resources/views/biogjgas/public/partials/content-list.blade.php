@extends('biogjgas.layout.public')

@section('title', $pageTitle . ' | BIOGJGAS Guaviare')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => $breadcrumb ?? [['label' => $pageTitle]]])

    <section class="mb-4">
        <h1 class="h3 font-weight-bold mb-3">{{ $pageTitle }}</h1>
        @isset($pageIntro)<p class="text-muted">{{ $pageIntro }}</p>@endisset
    </section>

    @if ($items->isEmpty())
        <div class="alert alert-info"><i class="fas fa-info-circle mr-1"></i> No hay contenido publicado por el momento.</div>
    @else
        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h2 class="h6 font-weight-bold">{{ $item->{$titleField} }}</h2>
                            @if (!empty($subtitleField) && $item->{$subtitleField})
                                <p class="text-muted small mb-2">{{ $item->{$subtitleField} }}</p>
                            @endif
                            @if (!empty($excerptField) && $item->{$excerptField})
                                <p class="small flex-grow-1">{{ \Illuminate\Support\Str::limit($item->{$excerptField}, 120) }}</p>
                            @endif
                            <a href="{{ route($showRoute, $routeParam ? $item->{$routeParam} : $item->id) }}" class="btn btn-outline-success btn-sm mt-2 align-self-start">Ver más</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
