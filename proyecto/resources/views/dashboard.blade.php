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
                    <span class="badge bg-{{ Auth::user()->isAdmin() ? 'primary' : 'secondary' }} fs-6 px-3 py-2">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ Auth::user()->role }}
                    </span>
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
                            @php
                                // Obtener tareas activas asignadas al usuario
                                $activeTasks = Auth::user()->assignedTasks()
                                    ->where('status', 'ACTIVE')
                                    ->count();
                            @endphp
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
                            @php
                                // Obtener proyectos activos donde el usuario tiene equipos asignados
                                $activeProjects = Auth::user()->teams()
                                    ->with(['projects' => function($query) {
                                        $query->where('status', 'ACTIVE');
                                    }])
                                    ->get()
                                    ->pluck('projects')
                                    ->flatten()
                                    ->unique('id')
                                    ->count();
                            @endphp
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
                            @php
                                // Obtener comentarios en tareas activas
                                $activeComments = \App\Models\Comment::whereHas('task', function($query) {
                                    $query->where('status', 'ACTIVE')
                                          ->whereHas('assignedUsers', function($userQuery) {
                                              $userQuery->where('user_id', Auth::id());
                                          });
                                })->count();
                            @endphp
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
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-task text-primary me-2"></i>
                                Mis Tareas en Curso
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                // Obtener tareas activas (sin límite) ordenadas por prioridad
                                $activeTasksList = Auth::user()->assignedTasks()
                                    ->with(['module.project', 'creator'])
                                    ->where('status', 'ACTIVE')
                                    ->orderByRaw("FIELD(priority, 'URGENT', 'HIGH', 'MEDIUM', 'LOW')")
                                    ->orderBy('created_at', 'desc')
                                    ->get();

                                // Obtener tareas pendientes (máximo 3) ordenadas por prioridad
                                $pendingTasksList = Auth::user()->assignedTasks()
                                    ->with(['module.project', 'creator'])
                                    ->where('status', 'PENDING')
                                    ->orderByRaw("FIELD(priority, 'URGENT', 'HIGH', 'MEDIUM', 'LOW')")
                                    ->orderBy('created_at', 'desc')
                                    ->take(3)
                                    ->get();

                                $allTasks = $activeTasksList->concat($pendingTasksList);
                            @endphp

                            @if($allTasks->count() > 0)
                                <div class="list-group list-group-flush">
                                    @if($activeTasksList->count() > 0)
                                        @foreach($activeTasksList as $task)
                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold d-flex align-items-center">
                                                        {{ $task->title }}
                                                        <span class="badge bg-success ms-2 small">ACTIVA</span>
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
                                    @endif

                                    @if($pendingTasksList->count() > 0)
                                        @if($activeTasksList->count() > 0)
                                            <div class="list-group-item bg-light">
                                                <small class="text-muted fw-bold">TAREAS PENDIENTES</small>
                                            </div>
                                        @endif
                                        @foreach($pendingTasksList as $task)
                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold d-flex align-items-center">
                                                        {{ $task->title }}
                                                        <span class="badge bg-secondary ms-2 small">PENDIENTE</span>
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
                                    @endif
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
                            @php
                                $totalTasks = Auth::user()->assignedTasks()->count();
                                $completedTasks = Auth::user()->assignedTasks()->where('status', 'DONE')->count();
                                $activeTasksCount = Auth::user()->assignedTasks()->where('status', 'ACTIVE')->count();
                                $pendingTasksCount = Auth::user()->assignedTasks()->where('status', 'PENDING')->count();
                            @endphp
                            
                            <div class="row text-center">
                                <div class="col-3 mb-3">
                                    <div class="fw-bold text-primary fs-4">{{ $totalTasks }}</div>
                                    <small class="text-muted">Total Tareas</small>
                                </div>
                                <div class="col-3 mb-3">
                                    <div class="fw-bold text-success fs-4">{{ $completedTasks }}</div>
                                    <small class="text-muted">Completadas</small>
                                </div>
                                <div class="col-3">
                                    <div class="fw-bold text-warning fs-4">{{ $activeTasksCount }}</div>
                                    <small class="text-muted">En Progreso</small>
                                </div>
                                <div class="col-3">
                                    <div class="fw-bold text-info fs-4">{{ $pendingTasksCount }}</div>
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
                            <h5 class="card-title mb-0">
                                <i class="bi bi-kanban text-primary me-2"></i>
                                Proyectos Activos
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                // Obtener proyectos activos del usuario
                                $userActiveProjects = Auth::user()->teams()
                                    ->with(['projects' => function($query) {
                                        $query->where('status', 'ACTIVE')
                                              ->with('creator');
                                    }])
                                    ->get()
                                    ->pluck('projects')
                                    ->flatten()
                                    ->unique('id')
                                    ->take(3);
                            @endphp

                            @if($userActiveProjects->count() > 0)
                                @foreach($userActiveProjects as $project)
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
                            <h5 class="card-title mb-0">
                                <i class="bi bi-chat-dots text-primary me-2"></i>
                                Comentarios en Curso
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                // Obtener comentarios en tareas activas (sin límite) ordenados por prioridad de la tarea
                                $activeCommentsData = \App\Models\Comment::with(['user', 'task'])
                                    ->whereHas('task', function($query) {
                                        $query->where('status', 'ACTIVE')
                                              ->whereHas('assignedUsers', function($userQuery) {
                                                  $userQuery->where('user_id', Auth::id());
                                              });
                                    })
                                    ->join('tasks', 'comments.task_id', '=', 'tasks.id')
                                    ->orderByRaw("FIELD(tasks.priority, 'URGENT', 'HIGH', 'MEDIUM', 'LOW')")
                                    ->orderBy('comments.created_at', 'desc')
                                    ->select('comments.*')
                                    ->get();

                                // Obtener comentarios en tareas pendientes (máximo 3) ordenados por prioridad de la tarea
                                $pendingCommentsData = \App\Models\Comment::with(['user', 'task'])
                                    ->whereHas('task', function($query) {
                                        $query->where('status', 'PENDING')
                                              ->whereHas('assignedUsers', function($userQuery) {
                                                  $userQuery->where('user_id', Auth::id());
                                              });
                                    })
                                    ->join('tasks', 'comments.task_id', '=', 'tasks.id')
                                    ->orderByRaw("FIELD(tasks.priority, 'URGENT', 'HIGH', 'MEDIUM', 'LOW')")
                                    ->orderBy('comments.created_at', 'desc')
                                    ->select('comments.*')
                                    ->take(3)
                                    ->get();

                                $allComments = $activeCommentsData->concat($pendingCommentsData);
                            @endphp

                            @if($allComments->count() > 0)
                                @if($activeCommentsData->count() > 0)
                                    @foreach($activeCommentsData as $comment)
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="feature-icon success me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                <i class="bi bi-chat"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold d-flex align-items-center">
                                                    {{ $comment->task->title }}
                                                    <span class="badge bg-success ms-2 small">ACTIVA</span>
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
                                @endif

                                @if($pendingCommentsData->count() > 0)
                                    @if($activeCommentsData->count() > 0)
                                        <div class="border-top pt-3 mb-3">
                                            <small class="text-muted fw-bold">COMENTARIOS PENDIENTES</small>
                                        </div>
                                    @endif
                                    @foreach($pendingCommentsData as $comment)
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="feature-icon secondary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                <i class="bi bi-chat"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold d-flex align-items-center">
                                                    {{ $comment->task->title }}
                                                    <span class="badge bg-secondary ms-2 small">PENDIENTE</span>
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
                                @endif
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-chat-square display-4 text-muted mb-2"></i>
                                    <p class="text-muted small mb-0">No hay comentarios en curso</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection