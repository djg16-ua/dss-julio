@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header personalizado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                ¡Bienvenido, {{ Auth::user()->name }}!
            </h1>
            <p class="text-muted">
                Conectado como 
                <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-primary' }}">
                    {{ Auth::user()->role }}
                </span>
            </p>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-cog fa-sm text-white-50"></i> Panel de Administración
            </a>
        @endif
    </div>

    <!-- Cards de estadísticas (3 cards) -->
    <div class="row mb-4">
        <!-- Tareas Activas -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tareas Activas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $activeTasks }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proyectos Activos -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Proyectos Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $activeProjects }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comentarios Activos -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Comentarios Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $activeComments }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="row">
        <!-- Sección principal (col-lg-8) -->
        <div class="col-lg-8">
            <!-- Tareas en Curso -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tareas en Curso</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-1"></i>Ver más
                    </a>
                </div>
                <div class="card-body">
                    @if($tasksInProgress->count() > 0)
                        @foreach($tasksInProgress as $task)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <strong>{{ $task->title }}</strong>
                                        @switch($task->priority)
                                            @case('URGENT')
                                                <span class="badge bg-danger ms-2">Urgente</span>
                                                @break
                                            @case('HIGH')
                                                <span class="badge bg-warning ms-2">Alta</span>
                                                @break
                                            @case('MEDIUM')
                                                <span class="badge bg-info ms-2">Media</span>
                                                @break
                                            @case('LOW')
                                                <span class="badge bg-secondary ms-2">Baja</span>
                                                @break
                                        @endswitch
                                    </h6>
                                    <p class="text-muted mb-1">
                                        <strong>Proyecto:</strong> {{ $task->module->project->title ?? 'Sin proyecto' }}
                                        | <strong>Módulo:</strong> {{ $task->module->name ?? 'Sin módulo' }}
                                    </p>
                                    @if($task->description)
                                        <p class="mb-1 text-sm">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                    @if($task->end_date)
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Fecha límite: {{ \Carbon\Carbon::parse($task->end_date)->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    @if($task->status === 'ACTIVE')
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No tienes tareas en curso</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resumen Personal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen Personal</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-primary">{{ $taskSummary['total'] }}</h4>
                                <small class="text-muted">Total Tareas</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-success">{{ $taskSummary['completed'] }}</h4>
                                <small class="text-muted">Completadas</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-info">{{ $taskSummary['in_progress'] }}</h4>
                                <small class="text-muted">En Progreso</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <h4 class="font-weight-bold text-warning">{{ $taskSummary['pending'] }}</h4>
                            <small class="text-muted">Pendientes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral (col-lg-4) -->
        <div class="col-lg-4">
            <!-- Proyectos Activos -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Proyectos Activos</h6>
                    <a href="{{ route('project.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-1"></i>Ver más
                    </a>
                </div>
                <div class="card-body">
                    @if($activeProjectsList->count() > 0)
                        @foreach($activeProjectsList as $project)
                            <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                            {{ $project->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $project->creator->name }}
                                        @if($project->creator->id === auth()->id())
                                            (Tú)
                                        @endif
                                    </small>
                                    @if($project->public)
                                        <span class="badge bg-primary ms-1">Público</span>
                                    @else
                                        <span class="badge bg-dark ms-1">Privado</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-project-diagram fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay proyectos activos</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comentarios en Curso -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Comentarios en Curso</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-1"></i>Ver más
                    </a>
                </div>
                <div class="card-body">
                    @if($commentsInProgress->count() > 0)
                        @foreach($commentsInProgress as $comment)
                            <div class="d-flex align-items-start mb-3 p-2 border rounded">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-sm">
                                        <strong>{{ $comment->task->title }}</strong>
                                        @switch($comment->task->priority)
                                            @case('URGENT')
                                                <span class="badge bg-danger">Urgente</span>
                                                @break
                                            @case('HIGH')
                                                <span class="badge bg-warning">Alta</span>
                                                @break
                                            @case('MEDIUM')
                                                <span class="badge bg-info">Media</span>
                                                @break
                                            @case('LOW')
                                                <span class="badge bg-secondary">Baja</span>
                                                @break
                                        @endswitch
                                    </h6>
                                    <p class="mb-1 text-sm">{{ Str::limit($comment->content, 80) }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay comentarios recientes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-right {
    border-right: 1px solid #e3e6f0;
}

.text-sm {
    font-size: 0.875rem;
}

.card {
    border: 1px solid #e3e6f0;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection