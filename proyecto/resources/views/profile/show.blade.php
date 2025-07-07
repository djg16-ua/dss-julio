@extends('layouts.app')

@section('title', 'Mi Perfil - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header del perfil -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-person-circle me-3"></i>Mi Perfil
                    </h1>
                    <p class="lead text-muted">
                        Bienvenido de vuelta, <strong>{{ Auth::user()->name }}</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>Editar Perfil
                    </a>
                </div>
            </div>

            <!-- Información básica del usuario -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <div class="bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                    <i class="bi bi-person-fill text-white" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-primary">{{ Auth::user()->name }}</h4>
                            <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                            
                            <!-- Estado de verificación -->
                            @if(Auth::user()->email_verified_at)
                            <span class="badge bg-success mb-2">
                                <i class="bi bi-check-circle me-1"></i>Email Verificado
                            </span>
                            @else
                            <span class="badge bg-warning mb-2">
                                <i class="bi bi-exclamation-circle me-1"></i>Email Pendiente
                            </span>
                            @endif
                            
                            <!-- Rol -->
                            <div class="mb-3">
                                <span class="badge bg-{{ Auth::user()->role === 'ADMIN' ? 'primary' : 'secondary' }} fs-6">
                                    <i class="bi bi-shield-check me-1"></i>
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                            
                            <p class="text-muted small">
                                <i class="bi bi-calendar me-1"></i>
                                Miembro desde {{ Auth::user()->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row g-3">
                        <!-- Estadísticas del usuario - 2x2 Grid -->
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon success mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $userStats['projects_completed'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Proyectos Completados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon secondary mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-check-square"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $userStats['tasks_done'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Tareas Realizadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon primary mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-kanban"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $userStats['projects_created'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Proyectos Creados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon primary mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-chat-dots"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $userStats['comments'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Comentarios Realizados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs de información -->
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="projects-tab" data-bs-toggle="pill" data-bs-target="#projects" type="button" role="tab">
                                <i class="bi bi-kanban me-2"></i>Proyectos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tasks-tab" data-bs-toggle="pill" data-bs-target="#tasks" type="button" role="tab">
                                <i class="bi bi-check-square me-2"></i>Tareas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="teams-tab" data-bs-toggle="pill" data-bs-target="#teams" type="button" role="tab">
                                <i class="bi bi-people me-2"></i>Equipos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity" type="button" role="tab">
                                <i class="bi bi-clock-history me-2"></i>Actividad Reciente
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">
                        <!-- Mis Proyectos -->
                        <div class="tab-pane fade show active" id="projects" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-kanban text-primary me-2"></i>
                                            Mis Proyectos
                                        </h5>
                                        <a href="#" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Ver más
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($userProjects) && $userProjects->count() > 0)
                                        <div class="row g-3">
                                            @foreach($userProjects as $project)
                                            <div class="col-lg-6">
                                                <div class="card border">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-bold mb-0">{{ $project->title }}</h6>
                                                            <span class="badge bg-{{ $project->status === 'ACTIVE' ? 'success' : ($project->status === 'PENDING' ? 'warning' : ($project->status === 'COMPLETED' ? 'primary' : 'secondary')) }}">
                                                                {{ $project->status }}
                                                            </span>
                                                        </div>
                                                        <p class="text-muted small mb-2">{{ Str::limit($project->description, 100) }}</p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar me-1"></i>
                                                                {{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Sin fecha' }}
                                                            </small>
                                                            <span class="badge bg-{{ $project->public ? 'info' : 'secondary' }}">
                                                                <i class="bi bi-{{ $project->public ? 'globe' : 'lock' }} me-1"></i>
                                                                {{ $project->public ? 'Público' : 'Privado' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-folder-plus display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted">No has creado ningún proyecto aún</h6>
                                            <p class="text-muted mb-3">Crea tu primer proyecto para comenzar</p>
                                            <button class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Crear Proyecto
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Mis Tareas -->
                        <div class="tab-pane fade" id="tasks" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-check-square text-primary me-2"></i>
                                            Mis Tareas
                                        </h5>
                                        <a href="#" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Ver más
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($userTasks) && $userTasks->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($userTasks as $task)
                                            <div class="list-group-item border-0 px-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="me-3">
                                                        <i class="bi bi-{{ $task->status === 'DONE' ? 'check-circle-fill text-success' : ($task->status === 'ACTIVE' ? 'play-circle-fill text-primary' : 'circle text-muted') }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="fw-bold mb-1">{{ $task->title }}</h6>
                                                                <p class="text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                                                                <div class="d-flex gap-2 align-items-center">
                                                                    <span class="badge bg-{{ $task->priority === 'URGENT' ? 'danger' : ($task->priority === 'HIGH' ? 'warning' : ($task->priority === 'MEDIUM' ? 'info' : 'secondary')) }}">
                                                                        {{ $task->priority }}
                                                                    </span>
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-folder me-1"></i>
                                                                        {{ $task->module->name ?? 'Sin módulo' }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="badge bg-{{ $task->status === 'DONE' ? 'success' : ($task->status === 'ACTIVE' ? 'primary' : 'secondary') }}">
                                                                    {{ $task->status }}
                                                                </span>
                                                                @if($task->end_date)
                                                                <small class="text-muted d-block mt-1">
                                                                    <i class="bi bi-calendar me-1"></i>
                                                                    {{ $task->end_date->format('d/m/Y') }}
                                                                </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-check2-all display-4 text-success mb-3"></i>
                                            <h6 class="text-muted">No tienes tareas asignadas</h6>
                                            <p class="text-muted mb-0">¡Perfecto! Mantén este ritmo</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Mis Equipos -->
                        <div class="tab-pane fade" id="teams" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-people text-primary me-2"></i>
                                            Mis Equipos
                                        </h5>
                                        <a href="#" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Ver más
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($userTeams) && $userTeams->count() > 0)
                                        <div class="row g-3">
                                            @foreach($userTeams as $teamData)
                                            <div class="col-lg-6">
                                                <div class="card border">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-start">
                                                            <div class="feature-icon secondary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                                <i class="bi bi-people-fill"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="fw-bold mb-1">{{ $teamData->team->name }}</h6>
                                                                <p class="text-muted small mb-2">{{ $teamData->team->description }}</p>
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span class="badge bg-primary">{{ $teamData->role }}</span>
                                                                    <small class="text-muted">Desde {{ $teamData->joined_at->format('M Y') }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-people display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted">No perteneces a ningún equipo aún</h6>
                                            <p class="text-muted mb-3">Únete a un equipo para colaborar en proyectos</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actividad Reciente -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-clock-history text-primary me-2"></i>
                                            Actividad Reciente
                                        </h5>
                                        <a href="#" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Ver más
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($recentActivity) && $recentActivity->count() > 0)
                                        <div class="timeline">
                                            @foreach($recentActivity as $activity)
                                            <div class="d-flex mb-3">
                                                <div class="feature-icon primary me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                    <i class="bi bi-{{ $activity['icon'] }}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{ $activity['title'] }}</h6>
                                                    <p class="text-muted small mb-1">{{ $activity['description'] }}</p>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $activity['time'] }}
                                                    </small>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-clock-history display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted">No hay actividad reciente</h6>
                                            <p class="text-muted mb-0">Tu actividad aparecerá aquí</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection