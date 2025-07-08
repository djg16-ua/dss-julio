@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container-fluid">
    <!-- Header del perfil -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <div class="profile-avatar me-3">
                <div class="avatar-circle">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>
            <div>
                <h1 class="h3 mb-0 text-gray-800">{{ $user->name }}</h1>
                <p class="text-muted mb-0">{{ $user->email }}</p>
                <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-primary' }} mt-1">
                    {{ $user->role }}
                </span>
                @if($user->email_verified_at)
                    <span class="badge bg-success mt-1">Verificado</span>
                @else
                    <span class="badge bg-warning mt-1">Sin verificar</span>
                @endif
            </div>
        </div>
        <div>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Editar Perfil
            </a>
        </div>
    </div>

    <!-- Estadísticas del perfil (2x2 grid) -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Proyectos Completados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['projects_completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tareas Realizadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['tasks_done'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Proyectos Creados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['projects_created'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Comentarios Realizados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['comments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido con tabs -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">
                        <i class="fas fa-project-diagram me-2"></i>Mis Proyectos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab">
                        <i class="fas fa-tasks me-2"></i>Mis Tareas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>Mis Equipos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>Actividad Reciente
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="profileTabsContent">
                <!-- Tab Mis Proyectos -->
                <div class="tab-pane fade show active" id="projects" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Proyectos ({{ $myProjects->count() }})</h6>
                        <a href="{{ route('project.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i>Ver más
                        </a>
                    </div>
                    @if($myProjects->count() > 0)
                        <div class="row">
                            @foreach($myProjects->take(6) as $project)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">
                                                    <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                                        {{ $project->title }}
                                                    </a>
                                                </h6>
                                                @switch($project->status)
                                                    @case('ACTIVE')
                                                        <span class="badge bg-success">Activo</span>
                                                        @break
                                                    @case('PENDING')
                                                        <span class="badge bg-warning">Pendiente</span>
                                                        @break
                                                    @case('COMPLETED')
                                                        <span class="badge bg-info">Completado</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $project->status }}</span>
                                                @endswitch
                                            </div>
                                            @if($project->description)
                                                <p class="card-text text-muted small">{{ Str::limit($project->description, 80) }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    @if($project->public)
                                                        <i class="fas fa-globe me-1"></i>Público
                                                    @else
                                                        <i class="fas fa-lock me-1"></i>Privado
                                                    @endif
                                                </small>
                                                <small class="text-muted">{{ $project->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No participas en ningún proyecto aún</p>
                            <a href="{{ route('projects.create') }}" class="btn btn-primary">Crear mi primer proyecto</a>
                        </div>
                    @endif
                </div>

                <!-- Tab Mis Tareas -->
                <div class="tab-pane fade" id="tasks" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Tareas Asignadas ({{ $myTasks->count() }})</h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i>Ver más
                        </a>
                    </div>
                    @if($myTasks->count() > 0)
                        @foreach($myTasks->take(5) as $task)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $task->title }}
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
                                    <p class="text-muted mb-1 small">
                                        <strong>Proyecto:</strong> {{ $task->module->project->title ?? 'Sin proyecto' }}
                                    </p>
                                    @if($task->end_date)
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($task->end_date)->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    @switch($task->status)
                                        @case('DONE')
                                            <span class="badge bg-success">Completada</span>
                                            @break
                                        @case('ACTIVE')
                                            <span class="badge bg-primary">Activa</span>
                                            @break
                                        @case('PENDING')
                                            <span class="badge bg-warning">Pendiente</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $task->status }}</span>
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No tienes tareas asignadas</p>
                        </div>
                    @endif
                </div>

                <!-- Tab Mis Equipos -->
                <div class="tab-pane fade" id="teams" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Equipos ({{ $myTeams->count() }})</h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i>Ver más
                        </a>
                    </div>
                    @if($myTeams->count() > 0)
                        <div class="row">
                            @foreach($myTeams->take(4) as $team)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $team->name }}</h6>
                                            @if($team->description)
                                                <p class="card-text text-muted small">{{ Str::limit($team->description, 60) }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $team->users->where('pivot.is_active', true)->count() }} miembros
                                                </small>
                                                <small class="text-muted">
                                                    Rol: <strong>{{ $team->pivot->role }}</strong>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No perteneces a ningún equipo</p>
                        </div>
                    @endif
                </div>

                <!-- Tab Actividad Reciente -->
                <div class="tab-pane fade" id="activity" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Actividad Reciente</h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i>Ver más
                        </a>
                    </div>
                    @if($recentActivity->count() > 0)
                        @foreach($recentActivity->take(8) as $activity)
                            <div class="d-flex align-items-start mb-3 p-3 border rounded">
                                <div class="activity-icon me-3">
                                    @if($activity['type'] === 'comment')
                                        <i class="fas fa-comment text-info"></i>
                                    @elseif($activity['type'] === 'task')
                                        <i class="fas fa-check text-success"></i>
                                    @elseif($activity['type'] === 'project')
                                        <i class="fas fa-project-diagram text-primary"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1">{{ $activity['description'] }}</p>
                                    <small class="text-muted">{{ $activity['time'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay actividad reciente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar {
    width: 80px;
    height: 80px;
}

.avatar-circle {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
}

.nav-tabs .nav-link.active {
    background-color: transparent;
    border-bottom: 2px solid #4e73df;
    color: #4e73df;
}

.activity-icon {
    width: 30px;
    text-align: center;
}

.card {
    border: 1px solid #e3e6f0;
    transition: transform 0.15s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}
</style>
@endsection