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

<div class="container mt-4">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card dashboard-card text-white h-100" style="background-color: #4fc3f7;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Aprendices activos</h6>
                            <h3 class="mb-0 fw-bold">238</h3>
                            <small class="text-white-50">Hoy</small>
                        </div>
                        <div class="badge rounded-pill bg-white px-3 py-2">
                            <p class="text-danger m-0 fw-medium">-21.5%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white" style="background-color: #673ab7;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Instructores</h6>
                            <h4 class="mb-0">42</h4>
                            <small>Hoy</small>
                        </div>
                        <div class="badge rounded-pill bg-white">
                            <p class="text-danger m-0">-21.5%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white" style="background-color: #26a69a;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Programas activos</h6>
                            <h4 class="mb-0">10</h4>
                            <small>Hoy</small>
                        </div>
                        <div class="badge rounded-pill bg-white">
                            <p class="text-danger m-0">-21.5%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white" style="background-color: #ffa726;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Ambientes disponibles</h6>
                            <h4 class="mb-0">15</h4>
                            <small>Hoy</small>
                        </div>
                        <div class="badge rounded-pill bg-white">
                            <p class="text-danger m-0" style="font-size: 0.9rem;">-21.5%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mantener esta sección con las tres gráficas -->
<div class="container-fluid px-4">
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Asistencia Semanal</h3>
                    <i class="fas fa-chart-line text-muted"></i>
                </div>
                <div class="card-body p-4">
                    <canvas id="asistenciaChartNew" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Uso de Ambientes por Jornada</h3>
                    <i class="fas fa-chart-bar text-muted"></i>
                </div>
                <div class="card-body p-4">
                    <canvas id="ambientesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header py-3">
                    <h3 class="card-title mb-0">Distribución por Programa</h3>
                </div>
                <div class="card-body p-4">
                    <canvas id="distribucionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nueva sección de listas breves -->
<div class="container-fluid px-4 mt-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Últimos Reportes de Mantenimiento</h3>
                    <span class="badge bg-info">3 nuevos</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Ambiente 301</h6>
                                <small class="text-muted">Hoy</small>
                            </div>
                            <small>Mantenimiento de equipos completado</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Ambiente 205</h6>
                                <small class="text-muted">Ayer</small>
                            </div>
                            <small>Actualización de software</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Ambiente 102</h6>
                                <small class="text-muted">2 días</small>
                            </div>
                            <small>Revisión de red completada</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Próximas Clases</h3>
                    <span class="badge bg-success">Hoy/Mañana</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">ADSO - 2556456</h6>
                                <small class="text-success">14:00</small>
                            </div>
                            <small>Ambiente 301 - John Doe</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Multimedia - 2557123</h6>
                                <small class="text-success">16:00</small>
                            </div>
                            <small>Ambiente 205 - Jane Smith</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">ADSO - 2558789</h6>
                                <small class="text-warning">Mañana 08:00</small>
                            </div>
                            <small>Ambiente 102 - Mike Johnson</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Alertas Importantes</h3>
                    <span class="badge bg-danger">2 nuevas</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-danger">Mantenimiento Urgente</h6>
                                <small class="text-danger">Ahora</small>
                            </div>
                            <small>Ambiente 304 - Falla en red</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-warning">Actualización Pendiente</h6>
                                <small class="text-muted">1h</small>
                            </div>
                            <small>Sistema de registro requiere actualización</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-info">Recordatorio</h6>
                                <small class="text-muted">3h</small>
                            </div>
                            <small>Reunión de coordinación - 15:00</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- After the existing lists section -->
<div class="container-fluid px-4 mt-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#calendarWidget" style="cursor: pointer;">
                    <h3 class="card-title mb-0">Calendario de Eventos</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="collapse show" id="calendarWidget">
                    <div class="card-body">
                        <div class="small-calendar">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Diciembre 2023</h5>
                                <div>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-left"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Lu</th><th>Ma</th><th>Mi</th><th>Ju</th><th>Vi</th><th>Sa</th><th>Do</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-muted">27</td>
                                            <td class="text-muted">28</td>
                                            <td class="text-muted">29</td>
                                            <td class="text-muted">30</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td class="bg-info text-white">5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td class="bg-success text-white">8</td>
                                            <td>9</td>
                                            <td>10</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#messagesWidget" style="cursor: pointer;">
                    <h3 class="card-title mb-0">Mensajes Internos</h3>
                    <span class="badge bg-primary">3 nuevos</span>
                </div>
                <div class="collapse show" id="messagesWidget">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Coordinación Académica</h6>
                                    <small class="text-primary">Nuevo</small>
                                </div>
                                <small>Reunión de seguimiento programada...</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Sistema</h6>
                                    <small class="text-primary">Nuevo</small>
                                </div>
                                <small>Actualización del sistema completada...</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Mantenimiento</h6>
                                    <small>Ayer</small>
                                </div>
                                <small>Reporte de mantenimiento semanal...</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#activityWidget" style="cursor: pointer;">
                    <h3 class="card-title mb-0">Registro de Actividad</h3>
                    <i class="fas fa-history"></i>
                </div>
                <div class="collapse show" id="activityWidget">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Inicio de sesión</h6>
                                    <small>Hace 5 min</small>
                                </div>
                                <small class="text-muted">Usuario: Juan Pérez</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Actualización de registro</h6>
                                    <small>Hace 15 min</small>
                                </div>
                                <small class="text-muted">Ficha: 2556456</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Nueva asistencia registrada</h6>
                                    <small>Hace 30 min</small>
                                </div>
                                <small class="text-muted">Ambiente: 301</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this script at the end of your file -->
<script>
    // Initialize all collapse elements
    $(document).ready(function() {
        $('.collapse').collapse();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Asistencia Semanal
    const asistenciaChart = new Chart(document.getElementById('asistenciaChartNew'), {
        type: 'line',
        data: {
            labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
            datasets: [{
                label: 'Asistencias',
                data: [220, 230, 210, 225, 215],
                borderColor: '#4fc3f7',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(79, 195, 247, 0.1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Gráfico de Uso de Ambientes
    const ambientesChart = new Chart(document.getElementById('ambientesChart'), {
        type: 'bar',
        data: {
            labels: ['Mañana', 'Tarde', 'Noche'],
            datasets: [{
                label: 'Ambientes Ocupados',
                data: [12, 8, 5],
                backgroundColor: '#26a69a'
            }, {
                label: 'Ambientes Disponibles',
                data: [3, 7, 10],
                backgroundColor: '#ffa726'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Ambientes'
                    }
                },
                x: {
                    stacked: true
                }
            }
        }
    });

    // Gráfico de Distribución por Programa
    const distribucionChart = new Chart(document.getElementById('distribucionChart'), {
        type: 'doughnut',
        data: {
            labels: ['ADSO', 'Multimedia', 'Contabilidad', 'Otros'],
            datasets: [{
                data: [45, 25, 20, 10],
                backgroundColor: [
                    '#4fc3f7',
                    '#673ab7',
                    '#26a69a',
                    '#ffa726'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection