@extends('aitg.layouts.spa')

@section('title', 'Planes de Contratación - AITG')

@section('aitg_header')
    @include('aitg.planes-contratacion.partials.layout.page-header', [
        'title' => 'Planes de Contratación',
        'subtitle' => 'AITG - Anexo 2',
        'breadcrumb' => [
            ['label' => 'Inicio', 'url' => route('verificarLogin'), 'icon' => 'fa-home'],
            ['label' => 'AITG', 'icon' => 'fa-users-cog'],
            ['label' => 'Planes de Contratación', 'icon' => 'fa-file-contract', 'active' => true],
        ],
    ])
@endsection

@section('aitg_content')
<section class="content aitg-content mt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button></div>
        @endif

        <div class="aitg-card aitg-card--primary">
            <div class="aitg-card__header">
                <div class="aitg-card__title-wrap">
                    <span class="aitg-card__icon aitg-card__icon--primary"><i class="fas fa-list"></i></span>
                    <h3 class="aitg-card__title">Listado de planes</h3>
                </div>
                @can('CREAR PLAN CONTRATACION')
                    <a href="{{ route('aitg.planes-contratacion.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo plan
                    </a>
                @endcan
            </div>
            <div class="aitg-card__body">
                <form method="GET" class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por competencia o período..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(\App\Models\Aitg\PlanContratacion::ESTADOS as $value => $label)
                                <option value="{{ $value }}" @selected(request('estado') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><button type="submit" class="btn btn-secondary btn-block">Filtrar</button></div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Competencia</th>
                                <th>Modalidad</th>
                                <th>Regional</th>
                                <th>Período</th>
                                <th>Forma perfil</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($planes as $key => $plan)
                                <tr>
                                    <td>{{ $planes->firstItem() + $key }}</td>
                                    <td>{{ $plan->competencia->nombre ?? 'N/A' }}</td>
                                    <td>{{ $plan->modalidad_label }}</td>
                                    <td>{{ $plan->regional->nombre ?? 'N/A' }}</td>
                                    <td>{{ $plan->periodo }}</td>
                                    <td>{{ $plan->tipo_registro_perfil_label }}</td>
                                    <td>
                                        @php $badge = match($plan->estado) { 'activo' => 'success', 'cerrado' => 'secondary', default => 'warning' }; @endphp
                                        <span class="badge badge-{{ $badge }}">{{ $plan->estado_label }}</span>
                                    </td>
                                    <td class="text-center">
                                        @can('VER PLAN CONTRATACION')
                                            <a href="{{ route('aitg.planes-contratacion.show', $plan) }}" class="btn btn-sm btn-light" title="Ver"><i class="fas fa-eye text-primary"></i></a>
                                        @endcan
                                        @can('EDITAR PLAN CONTRATACION')
                                            <a href="{{ route('aitg.planes-contratacion.edit', $plan) }}" class="btn btn-sm btn-light" title="Editar"><i class="fas fa-pencil-alt text-info"></i></a>
                                        @endcan
                                        @can('ELIMINAR PLAN CONTRATACION')
                                            <form action="{{ route('aitg.planes-contratacion.destroy', $plan) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('¿Eliminar este plan?')">@csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light" title="Eliminar"><i class="fas fa-trash text-danger"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted">No hay planes registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $planes->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
