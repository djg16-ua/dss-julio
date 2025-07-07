@extends('layouts.app')

@section('title', 'Panel de Administración - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header del Admin -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-danger">
                        <i class="bi bi-shield-lock me-3"></i>Panel de Administración
                    </h1>
                    <p class="lead text-muted">
                        Control total sobre TaskFlow - Gestiona usuarios, proyectos y equipos
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="bi bi-person-badge me-1"></i>
                        ADMINISTRADOR
                    </span>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-primary">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_users'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Usuarios Totales</p>
                            <small class="text-success">
                                <i class="bi bi-shield-check me-1"></i>
                                {{ $stats['admin_users'] ?? 0 }} Admins
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-success">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_projects'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Proyectos</p>
                            <small class="text-success">
                                <i class="bi bi-play-circle me-1"></i>
                                {{ $stats['active_projects'] ?? 0 }} Activos
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-warning">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-check-square"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_tasks'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Tareas</p>
                            <small class="text-success">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ $stats['completed_tasks'] ?? 0 }} Completadas
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-info">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_teams'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Equipos</p>
                            <small class="text-info">
                                <i class="bi bi-people me-1"></i>
                                En funcionamiento
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas de Administración -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightning-fill me-2"></i>
                                Acciones de Administración
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100 btn-lg">
                                        <i class="bi bi-people me-2"></i>
                                        Gestionar Usuarios
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('admin.projects') }}" class="btn btn-outline-success w-100 btn-lg">
                                        <i class="bi bi-kanban me-2"></i>
                                        Gestionar Proyectos
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('admin.teams') }}" class="btn btn-outline-warning w-100 btn-lg">
                                        <i class="bi bi-diagram-3 me-2"></i>
                                        Gestionar Equipos
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('admin.statistics') }}" class="btn btn-outline-info w-100 btn-lg">
                                        <i class="bi bi-graph-up me-2"></i>
                                        Estadísticas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de contenido principal -->
            <div class="row g-4">
                <!-- Usuarios Recientes -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-plus text-primary me-2"></i>
                                Usuarios Recientes
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentUsers as $user)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="feature-icon primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $user->role === 'ADMIN' ? 'danger' : 'secondary' }}">
                                                {{ $user->role }}
                                            </span>
                                            <small class="text-muted d-block">
                                                {{ $user->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                                    Ver todos los usuarios
                                </a>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-person-plus display-4 text-muted mb-3"></i>
                                <h6 class="text-muted">No hay usuarios recientes</h6>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Proyectos Recientes -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-folder-plus text-success me-2"></i>
                                Proyectos Recientes
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($recentProjects) && $recentProjects->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentProjects as $project)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $project->title }}</h6>
                                            <p class="text-muted small mb-2">{{ Str::limit($project->description, 80) }}</p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <span class="badge bg-{{ $project->status === 'ACTIVE' ? 'success' : ($project->status === 'PENDING' ? 'warning' : 'secondary') }}">
                                                    {{ $project->status }}
                                                </span>
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $project->creator->name ?? 'Usuario eliminado' }}
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $project->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.projects') }}" class="btn btn-outline-success">
                                    Ver todos los proyectos
                                </a>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-folder-plus display-4 text-muted mb-3"></i>
                                <h6 class="text-muted">No hay proyectos recientes</h6>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas por Estado -->
            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-pie-chart text-info me-2"></i>
                                Tareas por Estado
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(isset($tasksByStatus) && $tasksByStatus->count() > 0)
                            @foreach($tasksByStatus as $status => $count)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $status === 'DONE' ? 'success' : ($status === 'ACTIVE' ? 'primary' : 'secondary') }}">
                                    {{ $status }}
                                </span>
                                <strong>{{ $count }}</strong>
                            </div>
                            @endforeach
                            @else
                            <p class="text-muted text-center">No hay datos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-bar-chart text-warning me-2"></i>
                                Proyectos por Estado
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(isset($projectsByStatus) && $projectsByStatus->count() > 0)
                            @foreach($projectsByStatus as $status => $count)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $status === 'ACTIVE' ? 'success' : ($status === 'PENDING' ? 'warning' : 'secondary') }}">
                                    {{ $status }}
                                </span>
                                <strong>{{ $count }}</strong>
                            </div>
                            @endforeach
                            @else
                            <p class="text-muted text-center">No hay datos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Volver al Dashboard -->
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard Principal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection