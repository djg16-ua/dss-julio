@extends('layouts.app')

@section('title', 'Estadísticas del Sistema - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-info">
                        <i class="bi bi-graph-up me-3"></i>Estadísticas del Sistema
                    </h1>
                    <p class="lead text-muted">
                        Análisis completo y métricas de rendimiento de TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                    </a>
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Imprimir
                    </button>
                </div>
            </div>

            <!-- Métricas principales -->
            <div class="row g-4 mb-5">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card border-primary h-100">
                        <div class="card-body text-center p-3">
                            <div class="feature-icon primary mx-auto mb-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-people"></i>
                            </div>
                            <h4 class="fw-bold text-primary mb-1">{{ $stats['total_users'] ?? 0 }}</h4>
                            <small class="text-muted">Usuarios Totales</small>
                            <div class="mt-2">
                                <small class="text-success">
                                    +{{ $stats['users_this_month'] ?? 0 }} este mes
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card border-success h-100">
                        <div class="card-body text-center p-3">
                            <div class="feature-icon success mx-auto mb-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h4 class="fw-bold text-primary mb-1">{{ $stats['total_projects'] ?? 0 }}</h4>
                            <small class="text-muted">Proyectos</small>
                            <div class="mt-2">
                                <small class="text-info">
                                    {{ $stats['active_projects'] ?? 0 }} activos
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card border-warning h-100">
                        <div class="card-body text-center p-3">
                            <div class="feature-icon secondary mx-auto mb-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <h4 class="fw-bold text-primary mb-1">{{ $stats['total_teams'] ?? 0 }}</h4>
                            <small class="text-muted">Equipos</small>
                            <div class="mt-2">
                                <small class="text-warning">
                                    {{ $stats['avg_team_size'] ?? 0 }} miembros promedio
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card border-info h-100">
                        <div class="card-body text-center p-3">
                            <div class="feature-icon primary mx-auto mb-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-check-square"></i>
                            </div>
                            <h4 class="fw-bold text-primary mb-1">{{ $stats['total_tasks'] ?? 0 }}</h4>
                            <small class="text-muted">Tareas</small>
                            <div class="mt-2">
                                <small class="text-success">
                                    {{ $stats['completed_tasks'] ?? 0 }} completadas
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card border-secondary h-100">
                        <div class="card-body text-center p-3">
                            <div class="feature-icon secondary mx-auto mb-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-grid"></i>
                            </div>
                            <h4 class="fw-bold text-primary mb-1">{{ $stats['total_modules'] ?? 0 }}</h4>
                            <small class="text-muted">Módulos</small>
                            <div class="mt-2">
                                <small class="text-info">
                                    {{ $stats['avg_modules_per_project'] ?? 0 }} por proyecto
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card border-dark h-100">
                        <div class="card-body text-center p-3">
                            <div class="feature-icon secondary mx-auto mb-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-chat"></i>
                            </div>
                            <h4 class="fw-bold text-primary mb-1">{{ $stats['total_comments'] ?? 0 }}</h4>
                            <small class="text-muted">Comentarios</small>
                            <div class="mt-2">
                                <small class="text-success">
                                    {{ $stats['comments_this_week'] ?? 0 }} esta semana
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos y análisis -->
            <div class="row g-4 mb-5">
                <!-- Gráfico de usuarios por mes -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>Crecimiento de Usuarios
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="usersGrowthChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Distribución de estados de proyectos -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-pie-chart me-2"></i>Estados de Proyectos
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="projectStatusChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis detallado -->
            <div class="row g-4 mb-5">
                <!-- Top usuarios más activos -->
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-trophy me-2"></i>Usuarios Más Activos
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($topUsers) && $topUsers->count() > 0)
                            @foreach($topUsers as $index => $user)
                            <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                                <div class="feature-icon {{ $index == 0 ? 'secondary' : 'primary' }} me-3" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                    @if($index == 0)
                                    <i class="bi bi-trophy-fill"></i>
                                    @else
                                    <span class="fw-bold">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ Str::limit($user->email, 25) }}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-info small">{{ $user->projects_count ?? 0 }} proyectos</span>
                                        <span class="badge bg-success small">{{ $user->tasks_count ?? 0 }} tareas</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <p class="text-muted text-center">No hay datos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Distribución de roles -->
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-shield me-2"></i>Distribución de Roles
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Administradores</span>
                                    <span class="badge bg-danger">{{ $stats['admin_users'] ?? 0 }}</span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-danger" style="width: {{ $stats['total_users'] > 0 ? round(($stats['admin_users'] ?? 0) / $stats['total_users'] * 100, 1) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Usuarios Regulares</span>
                                    <span class="badge bg-primary">{{ ($stats['total_users'] ?? 0) - ($stats['admin_users'] ?? 0) }}</span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-primary" style="width: {{ $stats['total_users'] > 0 ? round((($stats['total_users'] ?? 0) - ($stats['admin_users'] ?? 0)) / $stats['total_users'] * 100, 1) : 0 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Verificados</span>
                                    <span class="badge bg-success">{{ $stats['verified_users'] ?? 0 }}</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $stats['total_users'] > 0 ? round(($stats['verified_users'] ?? 0) / $stats['total_users'] * 100, 1) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Métricas de rendimiento CORREGIDAS -->
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-speedometer2 me-2"></i>Métricas de Rendimiento
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body p-3 text-center">
                                            <h6 class="fw-bold text-success">{{ $stats['completion_rate'] ?? 0 }}%</h6>
                                            <small class="text-muted">Tasa de Completación de Tareas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body p-3 text-center">
                                            <h6 class="fw-bold text-info">{{ $stats['avg_project_duration'] ?? 0 }}</h6>
                                            <small class="text-muted">Días promedio proyecto</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body p-3 text-center">
                                            <h6 class="fw-bold text-warning">{{ $stats['avg_tasks_per_user'] ?? 0 }}</h6>
                                            <small class="text-muted">Tareas por usuario</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body p-3 text-center">
                                            <h6 class="fw-bold text-primary">{{ $stats['custom_teams_per_project'] ?? 0 }}</h6>
                                            <small class="text-muted">Equipos personalizados por proyecto</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis temporal -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-calendar3 me-2"></i>Actividad por Período
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="text-center">
                                        <h4 class="fw-bold text-success">{{ $stats['today_activity'] ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Actividad Hoy</p>
                                        <small class="text-muted">Tareas creadas/completadas</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="text-center">
                                        <h4 class="fw-bold text-info">{{ $stats['week_activity'] ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Esta Semana</p>
                                        <small class="text-muted">Nuevos proyectos/equipos</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="text-center">
                                        <h4 class="fw-bold text-warning">{{ $stats['month_activity'] ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Este Mes</p>
                                        <small class="text-muted">Nuevos usuarios</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="text-center">
                                        <h4 class="fw-bold text-danger">{{ $stats['year_activity'] ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Este Año</p>
                                        <small class="text-muted">Total proyectos creados</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis de equipos y colaboración -->
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people-fill me-2"></i>Análisis de Equipos
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-info">{{ $stats['total_teams'] ?? 0 }}</h5>
                                        <small class="text-muted">Total Equipos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-success">{{ $stats['general_teams'] ?? 0 }}</h5>
                                        <small class="text-muted">Equipos Generales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-warning">{{ ($stats['total_teams'] ?? 0) - ($stats['general_teams'] ?? 0) }}</h5>
                                        <small class="text-muted">Equipos Personalizados</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-primary">{{ $stats['active_members'] ?? 0 }}</h5>
                                        <small class="text-muted">Miembros Activos</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="small text-muted">
                                <div class="mb-2">
                                    <strong>Promedio de miembros por equipo:</strong> {{ $stats['avg_team_size'] ?? 0 }}
                                </div>
                                <div>
                                    <strong>Equipos personalizados por proyecto:</strong> {{ $stats['custom_teams_per_project'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Análisis de productividad -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up-arrow me-2"></i>Análisis de Productividad
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-success">{{ $stats['completed_tasks'] ?? 0 }}</h5>
                                        <small class="text-muted">Tareas Completadas</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-warning">{{ ($stats['total_tasks'] ?? 0) - ($stats['completed_tasks'] ?? 0) }}</h5>
                                        <small class="text-muted">Tareas Pendientes</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-info">{{ $stats['total_modules'] ?? 0 }}</h5>
                                        <small class="text-muted">Total Módulos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="fw-bold text-primary">{{ $stats['avg_modules_per_project'] ?? 0 }}</h5>
                                        <small class="text-muted">Módulos por Proyecto</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" style="width: {{ $stats['completion_rate'] ?? 0 }}%"></div>
                                </div>
                                <small class="text-muted">
                                    <strong>{{ $stats['completion_rate'] ?? 0 }}%</strong> de tareas completadas
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proyectos más activos -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-building me-2"></i>Proyectos Más Activos
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($biggestProjects) && $biggestProjects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Proyecto</th>
                                            <th>Creador</th>
                                            <th>Equipos</th>
                                            <th>Módulos</th>
                                            <th>Tareas</th>
                                            <th>Estado</th>
                                            <th>Progreso</th>
                                            <th>Creado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($biggestProjects as $project)
                                        @php
                                        $totalTasks = $project->tasks_count ?? 0;
                                        $completedTasks = 0;
                                        // Si tuvieras una relación para tareas completadas:
                                        // $completedTasks = $project->completed_tasks_count ?? 0;
                                        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ Str::limit($project->title, 30) }}</div>
                                                <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                            </td>
                                            <td>{{ $project->creator->name ?? 'N/A' }}</td>
                                            <td><span class="badge bg-warning">{{ $project->teams_count ?? 0 }}</span></td>
                                            <td><span class="badge bg-info">{{ $project->modules_count ?? 0 }}</span></td>
                                            <td><span class="badge bg-success">{{ $project->tasks_count ?? 0 }}</span></td>
                                            <td>
                                                @php
                                                $statusColors = [
                                                'ACTIVE' => 'success',
                                                'PENDING' => 'warning',
                                                'DONE' => 'primary',
                                                'PAUSED' => 'secondary',
                                                'CANCELLED' => 'danger'
                                                ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$project->status] ?? 'secondary' }}">
                                                    {{ $project->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $progress }}%</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $project->created_at->format('d/m/Y') }}</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted text-center">No hay proyectos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de crecimiento de usuarios
        const usersCtx = document.getElementById('usersGrowthChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: @json($userGrowthLabels ?? []),
                datasets: [{
                    label: 'Nuevos Usuarios',
                    data: @json($userGrowthData ?? []),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de estados de proyectos
        const projectsCtx = document.getElementById('projectStatusChart').getContext('2d');
        new Chart(projectsCtx, {
            type: 'doughnut',
            data: {
                labels: @json($projectStatusLabels ?? []),
                datasets: [{
                    data: @json($projectStatusData ?? []),
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(108, 117, 125, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>

@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Éxito</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif
@endsection