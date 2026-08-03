@extends('aitg.layouts.spa')

@section('title', 'Banco de Talento - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Banco de Talento',
        'subtitle' => 'Acredite sus documentos de postulación por competencia (validación inicial).',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'Banco de Talento', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        @include('aitg.banco-instructores.partials.datos-basicos', ['persona' => $persona, 'editable' => false])

        @include('aitg.banco-instructores.partials.mis-postulaciones', ['misPostulaciones' => $misPostulaciones])

        <div class="aitg-card aitg-card--primary mt-4">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--primary"><i class="fas fa-search"></i></span>
                    <h3 class="aitg-card__title">Buscar competencia</h3>
                </div>
            </div>
            <div class="aitg-card__body">
                <p class="text-muted small mb-3">
                    La postulación al Banco de Talento es por <strong>competencia</strong>, no por plan de contratación.
                    El perfil/alternativa se elige más adelante, al postular a una convocatoria.
                </p>
                <form method="GET" class="row mb-4">
                    <div class="col-md-4 form-group">
                        <label>Competencia</label>
                        <input type="text" name="competencia" class="form-control" placeholder="Ej.: Gastronomía, Inglés..."
                            value="{{ request('competencia') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Regional (plan vigente)</label>
                        <select name="regional_id" class="form-control">
                            <option value="">Todas</option>
                            @foreach($regionales as $regional)
                                <option value="{{ $regional->id }}" @selected(request('regional_id') == $regional->id)>{{ $regional->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Modalidad (plan vigente)</label>
                        <select name="modalidad" class="form-control">
                            <option value="">Todas</option>
                            @foreach($modalidades as $value => $label)
                                <option value="{{ $value }}" @selected(request('modalidad') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </form>

                @forelse($competencias as $competencia)
                    @php $planRef = $competencia->aitgPlanes->first(); @endphp
                    <div class="aitg-banco-plan-result mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-1">{{ $competencia->nombre }}</h5>
                                @if($planRef)
                                    <small class="text-muted">
                                        Plan vigente de referencia · {{ $planRef->regional->nombre ?? '—' }} · {{ $planRef->modalidad_label }} · {{ $planRef->periodo }}
                                    </small>
                                @endif
                            </div>
                            <a href="{{ route('aitg.banco-instructores.postulacion', $competencia) }}" class="btn btn-success mt-2 mt-md-0">
                                <i class="fas fa-folder-open"></i> Cargar documentos de postulación
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Use el buscador para encontrar una competencia con plan de contratación activo.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
