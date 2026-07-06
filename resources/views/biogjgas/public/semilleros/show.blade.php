@extends('biogjgas.layout.public')

@section('title', $semillero->sigla . ' | BIOGJGAS Guaviare')

@section('biogjgas_content')
    @include('biogjgas.public.partials.breadcrumb', ['items' => [
        ['label' => 'Semilleros', 'url' => route('biogjgas.semilleros.index')],
        ['label' => $semillero->sigla],
    ]])

    <section class="biogjgas-semillero-hero rounded-lg shadow mb-4"
             style="--semillero-color: {{ $semillero->color_identidad }};">
        <div class="container py-4 py-md-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="biogjgas-semillero-hero__icon mb-3">
                        <i class="fas {{ $semillero->icono }}"></i>
                    </div>
                    <span class="badge badge-light mb-2">{{ $semillero->sigla }}</span>
                    <h1 class="h2 font-weight-bold mb-3">{{ $semillero->nombre }}</h1>
                    @if ($semillero->resumen)
                        <p class="lead mb-0">{{ $semillero->resumen }}</p>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h6 text-uppercase text-muted">Instructor líder</h2>
                            <p class="mb-2 font-weight-bold">{{ $semillero->instructor_lider ?? 'Por asignar' }}</p>
                            @if ($semillero->correo_contacto)
                                <p class="mb-0 small">
                                    <i class="fas fa-envelope mr-1"></i>
                                    <a href="mailto:{{ $semillero->correo_contacto }}">{{ $semillero->correo_contacto }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="semilleroTabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-general">General</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-objetivos">Objetivos</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-lineas">Líneas</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-integrantes">Integrantes</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-proyectos">Proyectos</a></li>
                    </ul>
                    <div class="tab-content pt-4">
                        <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                            @if ($semillero->descripcion)<p>{{ $semillero->descripcion }}</p>@endif
                            @if ($semillero->mision)<h3 class="h6 font-weight-bold">Misión</h3><p>{{ $semillero->mision }}</p>@endif
                            @if ($semillero->vision)<h3 class="h6 font-weight-bold">Visión</h3><p>{{ $semillero->vision }}</p>@endif
                        </div>
                        <div class="tab-pane fade" id="tab-objetivos" role="tabpanel">
                            @if (!empty($semillero->objetivos))
                                <ul class="mb-0">@foreach ($semillero->objetivos as $objetivo)<li>{{ $objetivo }}</li>@endforeach</ul>
                            @else<p class="text-muted mb-0">Objetivos en actualización.</p>@endif
                        </div>
                        <div class="tab-pane fade" id="tab-lineas" role="tabpanel">
                            @forelse ($semillero->lineas as $linea)
                                <div class="mb-3"><strong>{{ $linea->nombre }}</strong>@if($linea->descripcion)<p class="small mb-0">{{ $linea->descripcion }}</p>@endif</div>
                            @empty<p class="text-muted mb-0">Sin líneas publicadas.</p>@endforelse
                        </div>
                        <div class="tab-pane fade" id="tab-integrantes" role="tabpanel">
                            @forelse ($semillero->integrantes as $integrante)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span><strong>{{ $integrante->nombre }}</strong> @if($integrante->rol)<small class="text-muted">· {{ $integrante->rol }}</small>@endif</span>
                                    @if($integrante->programa)<small>{{ $integrante->programa }}</small>@endif
                                </div>
                            @empty<p class="text-muted mb-0">Sin integrantes publicados.</p>@endforelse
                        </div>
                        <div class="tab-pane fade" id="tab-proyectos" role="tabpanel">
                            @forelse ($semillero->proyectos as $proyecto)
                                <div class="mb-3">
                                    <strong>{{ $proyecto->titulo }}</strong>
                                    <span class="badge badge-{{ $proyecto->estado_ejecucion === 'en_ejecucion' ? 'success' : 'secondary' }} ml-1">{{ str_replace('_', ' ', $proyecto->estado_ejecucion) }}</span>
                                    @if($proyecto->descripcion)<p class="small mb-0">{{ $proyecto->descripcion }}</p>@endif
                                </div>
                            @empty<p class="text-muted mb-0">Sin proyectos publicados.</p>@endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0 font-weight-bold">Otros semilleros</h2>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($semilleros->where('id', '!=', $semillero->id) as $otro)
                        <a href="{{ route('biogjgas.semilleros.show', $otro) }}"
                           class="list-group-item list-group-item-action d-flex align-items-center">
                            <span class="biogjgas-semillero-mini-icon mr-3"
                                  style="--semillero-color: {{ $otro->color_identidad }};">
                                <i class="fas {{ $otro->icono }}"></i>
                            </span>
                            <span>
                                <strong>{{ $otro->sigla }}</strong><br>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($otro->nombre, 40) }}</small>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
