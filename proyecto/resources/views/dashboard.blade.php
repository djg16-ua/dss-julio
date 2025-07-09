@extends('layouts.app')

@section('title', 'Dashboard - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header de bienvenida -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-speedometer2 me-3"></i>Dashboard
                    </h1>
                    <p class="lead text-muted">
                        Bienvenido de vuelta, <strong>{{ Auth::user()->name }}</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('profile.show') }}" class="btn bg-{{ Auth::user()->isAdmin() ? 'primary' : 'secondary' }} text-white fs-6 px-3 py-2 text-decoration-none">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ Auth::user()->role }}
                    </a>
                </div>
            </div>

            <!-- Cards de estadísticas -->
            <div class="row g-4 mb-5">
                <!-- Tareas Activas -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $activeTasks }}</h3>
                            <p class="text-muted mb-0">Tareas Activas</p>
                        </div>
                    </div>
                </div>

                <!-- Proyectos Activos -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-folder"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $activeProjects }}</h3>
                            <p class="text-muted mb-0">Proyectos Activos</p>
                        </div>
                    </div>
                </div>

                <!-- Comentarios Activos -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-chat-dots"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $activeComments }}</h3>
                            <p class="text-muted mb-0">Comentarios Activos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección principal -->
            <div class="row g-4">
                <!-- Tareas en curso -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-list-task text-primary me-2"></i>
                                    Tareas en Curso
                                </h5>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver más
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($tasksInProgress->count() > 0)
                                <div class="list-group list-group-flush">
                                    @php $activeTasksShown = false; $pendingTasksShown = false; @endphp
                                    
                                    @foreach($tasksInProgress as $task)
                                        @if($task->status === 'ACTIVE' && !$activeTasksShown)
                                            @php $activeTasksShown = true; @endphp
                                        @elseif($task->status === 'PENDING' && !$pendingTasksShown)
                                            @if($activeTasksShown)
                                                <div class="list-group-item bg-light">
                                                    <small class="text-muted fw-bold">TAREAS PENDIENTES</small>
                                                </div>
                                            @endif
                                            @php $pendingTasksShown = true; @endphp
                                        @endif
                                        
                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold d-flex align-items-center">
                                                    {{ $task->title }}
                                                    <span class="badge bg-{{ $task->status === 'ACTIVE' ? 'success' : 'secondary' }} ms-2 small">
                                                        {{ $task->status === 'ACTIVE' ? 'ACTIVA' : 'PENDIENTE' }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    <i class="bi bi-folder me-1"></i>{{ $task->module->project->title }}
                                                    <span class="mx-2">•</span>
                                                    <i class="bi bi-person me-1"></i>{{ $task->creator->name }}
                                                </small>
                                                @if($task->end_date)
                                                    <div class="small text-muted mt-1">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Vence: {{ $task->end_date->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                                @if($task->description)
                                                    <div class="small text-muted mt-1">
                                                        {{ Str::limit($task->description, 80) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $task->priority === 'URGENT' ? 'danger' : ($task->priority === 'HIGH' ? 'warning' : ($task->priority === 'MEDIUM' ? 'info' : 'secondary')) }} rounded-pill mb-1">
                                                    {{ $task->priority }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-check-circle display-1 text-success mb-3"></i>
                                    <h6 class="text-muted">¡No tienes tareas en curso!</h6>
                                    <p class="text-muted mb-3">Todas tus tareas están completas</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Resumen Personal debajo de tareas -->
                    <div class="card mt-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up text-primary me-2"></i>
                                Resumen Personal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-3 mb-3">
                                    <div class="fw-bold text-primary fs-4">{{ $taskSummary['total'] }}</div>
                                    <small class="text-muted">Total Tareas</small>
                                </div>
                                <div class="col-3 mb-3">
                                    <div class="fw-bold text-success fs-4">{{ $taskSummary['completed'] }}</div>
                                    <small class="text-muted">Completadas</small>
                                </div>
                                <div class="col-3">
                                    <div class="fw-bold text-warning fs-4">{{ $taskSummary['in_progress'] }}</div>
                                    <small class="text-muted">En Progreso</small>
                                </div>
                                <div class="col-3">
                                    <div class="fw-bold text-info fs-4">{{ $taskSummary['pending'] }}</div>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-lg-4">
                    <!-- Proyectos activos -->
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-kanban text-primary me-2"></i>
                                    Proyectos Activos
                                </h5>
                                <a href="{{ route('project.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver más
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($activeProjectsList->count() > 0)
                                @foreach($activeProjectsList as $project)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="feature-icon primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="bi bi-folder"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $project->title }}</h6>
                                            <small class="text-muted">Por {{ $project->creator->name }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-folder-plus display-4 text-muted mb-2"></i>
                                    <p class="text-muted small mb-0">No hay proyectos activos</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Comentarios en Curso -->
                    <div class="card mt-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-chat-dots text-primary me-2"></i>
                                    Comentarios en Curso
                                </h5>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver más
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($commentsInProgress->count() > 0)
                                @php $activeCommentsShown = false; $pendingCommentsShown = false; @endphp
                                
                                @foreach($commentsInProgress as $comment)
                                    @if($comment->task->status === 'ACTIVE' && !$activeCommentsShown)
                                        @php $activeCommentsShown = true; @endphp
                                    @elseif($comment->task->status === 'PENDING' && !$pendingCommentsShown)
                                        @if($activeCommentsShown)
                                            <div class="border-top pt-3 mb-3">
                                                <small class="text-muted fw-bold">COMENTARIOS PENDIENTES</small>
                                            </div>
                                        @endif
                                        @php $pendingCommentsShown = true; @endphp
                                    @endif
                                    
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="feature-icon {{ $comment->task->status === 'ACTIVE' ? 'success' : 'secondary' }} me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="bi bi-chat"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold d-flex align-items-center">
                                                {{ $comment->task->title }}
                                                <span class="badge bg-{{ $comment->task->status === 'ACTIVE' ? 'success' : 'secondary' }} ms-2 small">
                                                    {{ $comment->task->status === 'ACTIVE' ? 'ACTIVA' : 'PENDIENTE' }}
                                                </span>
                                            </h6>
                                            <p class="mb-1 small text-muted">
                                                {{ Str::limit($comment->content, 60) }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>{{ $comment->user->name }}
                                                <span class="mx-1">•</span>
                                                {{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-chat-square display-4 text-muted mb-2"></i>
                                    <p class="text-muted small mb-0">No hay comentarios en curso</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection