@extends('layouts.app')

@section('title', $module->name . ' - ' . $project->title . ' - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header del módulo -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('module.index', $project) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary mb-0">
                                <i class="bi bi-collection me-3"></i>{{ $module->name }}
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-kanban me-1"></i>
                                <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                    {{ $project->title }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @switch($module->status)
                            @case('ACTIVE')
                                <span class="badge bg-success fs-6">✅ Activo</span>
                                @break
                            @case('PENDING')
                                <span class="badge bg-warning fs-6">⏳ Pendiente</span>
                                @break
                            @case('DONE')
                                <span class="badge bg-info fs-6">🎉 Completado</span>
                                @break
                            @case('PAUSED')
                                <span class="badge bg-secondary fs-6">⏸️ Pausado</span>
                                @break
                            @case('CANCELLED')
                                <span class="badge bg-danger fs-6">❌ Cancelado</span>
                                @break
                            @default
                                <span class="badge bg-light text-dark fs-6">{{ $module->status }}</span>
                        @endswitch

                        @switch($module->priority)
                            @case('URGENT')
                                <span class="badge bg-danger fs-6">🚨 Urgente</span>
                                @break
                            @case('HIGH')
                                <span class="badge bg-warning fs-6">⚡ Alta</span>
                                @break
                            @case('MEDIUM')
                                <span class="badge bg-info fs-6">📋 Media</span>
                                @break
                            @case('LOW')
                                <span class="badge bg-secondary fs-6">📝 Baja</span>
                                @break
                        @endswitch

                        @if($module->is_core)
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-star-fill me-1"></i>Módulo Core
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
                        <a href="{{ route('module.edit', [$project, $module]) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                        @php
                            $currentUser = auth()->user();
                            $isProjectCreator = $project->created_by === $currentUser->id;
                            $isAdmin = $currentUser->role === 'ADMIN';
                        @endphp
                        @if($isProjectCreator || $isAdmin)
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="bi bi-trash me-2"></i>Eliminar
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del módulo -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(78, 115, 223, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-collection text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary">Información del Módulo</h4>
                            </div>
                            
                            <!-- Estado y Prioridad -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-flag me-2"></i>Estado y Prioridad
                                </h6>
                                <div class="d-flex flex-column gap-2">
                                    @switch($module->status)
                                        @case('ACTIVE')
                                            <span class="badge bg-success">✅ Activo</span>
                                            @break
                                        @case('PENDING')
                                            <span class="badge bg-warning">⏳ Pendiente</span>
                                            @break
                                        @case('DONE')
                                            <span class="badge bg-info">🎉 Completado</span>
                                            @break
                                        @case('PAUSED')
                                            <span class="badge bg-secondary">⏸️ Pausado</span>
                                            @break
                                        @case('CANCELLED')
                                            <span class="badge bg-danger">❌ Cancelado</span>
                                            @break
                                    @endswitch
                                    
                                    @switch($module->priority)
                                        @case('URGENT')
                                            <span class="badge bg-danger">🚨 Urgente</span>
                                            @break
                                        @case('HIGH')
                                            <span class="badge bg-warning">⚡ Alta</span>
                                            @break
                                        @case('MEDIUM')
                                            <span class="badge bg-info">📋 Media</span>
                                            @break
                                        @case('LOW')
                                            <span class="badge bg-secondary">📝 Baja</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>

                            <!-- Tipo de módulo -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-gear me-2"></i>Tipo de Módulo
                                </h6>
                                @if($module->is_core)
                                    <span class="badge bg-primary">
                                        <i class="bi bi-star-fill me-1"></i>Módulo Core
                                    </span>
                                    <small class="text-muted d-block mt-1">
                                        Componente esencial del proyecto
                                    </small>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-circle me-1"></i>Módulo Estándar
                                    </span>
                                    <small class="text-muted d-block mt-1">
                                        Componente opcional del proyecto
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Estadísticas del módulo - 2 cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-check2-square text-success mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $moduleStats['total_tasks'] }}</h5>
                                    <p class="text-muted mb-0 small">Tareas Totales</p>
                                    <div class="mt-2">
                                        <small class="text-success me-2">
                                            <i class="bi bi-check-circle-fill"></i> {{ $moduleStats['completed_tasks'] }} completadas
                                        </small>
                                        <small class="text-warning">
                                            <i class="bi bi-clock"></i> {{ $moduleStats['pending_tasks'] }} pendientes
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-people text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $moduleStats['assigned_teams'] }}</h5>
                                    <p class="text-muted mb-0 small">Equipos Asignados</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del módulo -->
                    @if($module->description)
                        <div class="card feature-card" style="max-height: 140px; overflow: hidden;">
                            <div class="card-body py-2 px-3">
                                <h6 class="fw-bold text-primary mb-1">
                                    <i class="bi bi-card-text me-2"></i>Descripción
                                </h6>
                                <div class="description-content">
                                    <p class="text-muted mb-0" style="line-height: 1.4;">
                                        {{ Str::limit($module->description, 324) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gestión de tareas del módulo -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-check2-square text-primary me-2"></i>
                                    Tareas del Módulo (<span id="tasks-count">{{ $module->tasks->count() }}</span>)
                                </h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('task.index', $project) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-list-ul me-1"></i>Ver Todo
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                                        <i class="bi bi-plus-circle me-1"></i>Crear Tarea
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="tasks-container">
                                @php
                                    $moduleTasks = $module->tasks()->with('assignedUsers')->get();
                                    
                                    // Ordenación por estado y prioridad
                                    $statusOrder = ['ACTIVE' => 1, 'PENDING' => 2, 'DONE' => 3];
                                    $priorityOrder = ['URGENT' => 1, 'HIGH' => 2, 'MEDIUM' => 3, 'LOW' => 4];
                                    
                                    $sortedTasks = $moduleTasks->sort(function($a, $b) use ($statusOrder, $priorityOrder) {
                                        $statusA = $statusOrder[$a->status] ?? 4;
                                        $statusB = $statusOrder[$b->status] ?? 4;
                                        
                                        if ($statusA !== $statusB) {
                                            return $statusA <=> $statusB;
                                        }
                                        
                                        $priorityA = $priorityOrder[$a->priority] ?? 5;
                                        $priorityB = $priorityOrder[$b->priority] ?? 5;
                                        
                                        return $priorityA <=> $priorityB;
                                    });
                                @endphp
                                
                                @if($sortedTasks->count() > 0)
                                    <div class="row g-3">
                                        @foreach($sortedTasks as $task)
                                            <div class="col-12" data-task-id="{{ $task->id }}">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded clickable-task" 
                                                     onclick="window.location.href='{{ route('task.show', [$project, $task]) }}'"
                                                     style="cursor: pointer;">
                                                    <div class="d-flex align-items-center flex-grow-1">
                                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                            @switch($task->status)
                                                                @case('DONE')
                                                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                                                    @break
                                                                @case('ACTIVE')
                                                                    <i class="bi bi-play-circle-fill text-primary" style="font-size: 1.5rem;"></i>
                                                                    @break
                                                                @case('PENDING')
                                                                    <i class="bi bi-clock-fill text-warning" style="font-size: 1.5rem;"></i>
                                                                    @break
                                                                @default
                                                                    <i class="bi bi-circle text-secondary" style="font-size: 1.5rem;"></i>
                                                            @endswitch
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                                <h6 class="mb-0 fw-bold">{{ $task->title }}</h6>
                                                                <div class="d-flex gap-2">
                                                                    @switch($task->status)
                                                                        @case('ACTIVE')
                                                                            <span class="badge bg-success">✅ Activa</span>
                                                                            @break
                                                                        @case('PENDING')
                                                                            <span class="badge bg-warning">⏳ Pendiente</span>
                                                                            @break
                                                                        @case('DONE')
                                                                            <span class="badge bg-info">🎉 Completada</span>
                                                                            @break
                                                                    @endswitch
                                                                    
                                                                    @switch($task->priority)
                                                                        @case('URGENT')
                                                                            <span class="badge bg-danger">🚨 Urgente</span>
                                                                            @break
                                                                        @case('HIGH')
                                                                            <span class="badge bg-warning">⚡ Alta</span>
                                                                            @break
                                                                        @case('MEDIUM')
                                                                            <span class="badge bg-info">📋 Media</span>
                                                                            @break
                                                                        @case('LOW')
                                                                            <span class="badge bg-secondary">📝 Baja</span>
                                                                            @break
                                                                    @endswitch
                                                                </div>
                                                            </div>
                                                            
                                                            @if($task->description)
                                                                <p class="text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                                                            @endif
                                                            
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                                                                        @foreach($task->assignedUsers as $assigned)
                                                                            <div class="avatar-circle me-2" style="...">
                                                                                {{ strtoupper(substr($assigned->name, 0, 1)) }}
                                                                            </div>
                                                                            <small class="text-muted me-3">
                                                                                Asignada a {{ $assigned->name }}
                                                                                @if($assigned->id === auth()->id())
                                                                                    <span class="text-primary">(Tú)</span>
                                                                                @endif
                                                                            </small>
                                                                        @endforeach
                                                                    @else
                                                                        <small class="text-muted">
                                                                            <i class="bi bi-person-dash me-1"></i>Sin asignar
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                                <small class="text-muted">
                                                                    <i class="bi bi-calendar-plus me-1"></i>
                                                                    {{ $task->created_at->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $canDeleteTask = $isProjectCreator || $isAdmin || $task->created_by === $currentUser->id;
                                                    @endphp
                                                    @if($canDeleteTask)
                                                        <button type="button" class="btn btn-outline-danger btn-sm ms-2" 
                                                                onclick="event.stopPropagation(); removeTask({{ $task->id }}, '{{ $task->title }}')"
                                                                title="Eliminar tarea">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4" id="no-tasks-message">
                                        <i class="bi bi-check2-square display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay tareas en el módulo</h6>
                                        <p class="text-muted mb-3">Crea tareas para organizar el trabajo del módulo</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de equipos asignados -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    Equipos Asignados (<span id="teams-count">{{ $module->teams->count() }}</span>)
                                </h5>
                                @php
                                    $canManageTeams = $isProjectCreator || $isAdmin;
                                @endphp
                                @if($canManageTeams)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignTeamModal">
                                        <i class="bi bi-people-fill me-1"></i>Asignar Equipo
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="teams-container">
                                @if($module->teams->count() > 0)
                                    <div class="row g-3">
                                        @foreach($module->teams as $team)
                                            <div class="col-lg-4 col-md-6" data-team-id="{{ $team->id }}">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded clickable-card" 
                                                     onclick="window.location.href='{{ route('team.show', [$project, $team]) }}'"
                                                     style="cursor: pointer;">
                                                    <div class="d-flex align-items-center flex-grow-1">
                                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi bi-people-fill text-secondary" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">{{ $team->name }}</h6>
                                                            @if($team->description)
                                                                <small class="text-muted">{{ Str::limit($team->description, 50) }}</small>
                                                                <br>
                                                            @endif
                                                            <small class="text-muted">
                                                                <i class="bi bi-person-check me-1"></i>
                                                                {{ $team->users->where('pivot.is_active', true)->count() }} miembros
                                                            </small>
                                                            @if($team->is_general)
                                                                <span class="badge bg-warning ms-2">General</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($canManageTeams && !$team->is_general)
                                                        <button type="button" class="btn btn-outline-danger btn-sm ms-2" 
                                                                onclick="event.stopPropagation(); removeTeam({{ $team->id }}, '{{ $team->name }}')"
                                                                title="Desasignar equipo">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4" id="no-teams-message">
                                        <i class="bi bi-people display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay equipos asignados</h6>
                                        <p class="text-muted mb-3">Asigna equipos para organizar el trabajo del módulo</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actividad reciente -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                Actividad Reciente
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="d-flex mb-3">
                                    <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-plus-circle text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Módulo creado</h6>
                                        <p class="text-muted small mb-1">Se creó el módulo en el proyecto</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $module->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if($module->updated_at != $module->created_at)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-pencil text-secondary" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Módulo actualizado</h6>
                                            <p class="text-muted small mb-1">Se realizaron cambios en el módulo</p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $module->updated_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($module->tasks->count() > 0)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-check2-square text-success" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Tareas creadas</h6>
                                            <p class="text-muted small mb-1">{{ $module->tasks->count() }} tarea(s) en el módulo</p>
                                            <small class="text-muted">
                                                <i class="bi bi-check2-square me-1"></i>
                                                {{ $moduleStats['completed_tasks'] }} completadas, {{ $moduleStats['active_tasks'] }} activas, {{ $moduleStats['pending_tasks'] }} pendientes
                                            </small>
                                        </div>
                                    </div>
                                @endif

                                @if($module->teams->count() > 0)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-people text-info" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Equipos asignados</h6>
                                            <p class="text-muted small mb-1">{{ $module->teams->count() }} equipo(s) asignado(s) al módulo</p>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>
                                                Equipos: {{ $module->teams->pluck('name')->join(', ') }}
                                            </small>
                                        </div>
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
<!-- Modal para crear tarea -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Crear Nueva Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createTaskForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="task-title" class="form-label">Título de la tarea <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="task-title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="task-priority" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select" id="task-priority" required>
                                    <option value="MEDIUM" selected>📋 Media</option>
                                    <option value="URGENT">🚨 Urgente</option>
                                    <option value="HIGH">⚡ Alta</option>
                                    <option value="LOW">📝 Baja</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="task-description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="task-description" rows="4" placeholder="Describe los detalles de la tarea..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="task-assigned-to" class="form-label">Asignar a</label>
                        <select class="form-select" id="task-assigned-to" multiple size="5">
                            <option value="">Cargando miembros...</option>
                        </select>
                        <div id="members-loading" class="form-text text-muted" style="display: none;">
                            <i class="bi bi-arrow-repeat me-1"></i>Cargando miembros de los equipos asignados...
                        </div>
                        <div id="no-members-available" class="form-text text-warning" style="display: none;">
                            <i class="bi bi-info-circle me-1"></i>
                            No hay miembros en los equipos asignados al módulo
                        </div>
                        <div class="form-text">Mantén Ctrl presionado para seleccionar múltiples miembros</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmCreateTask">
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Crear Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar equipo -->
@if($canManageTeams)
<div class="modal fade" id="assignTeamModal" tabindex="-1" aria-labelledby="assignTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTeamModalLabel">Asignar Equipo al Módulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignTeamForm">
                    @csrf
                    <div class="mb-3">
                        <label for="team-select" class="form-label">Seleccionar Equipo</label>
                        <select class="form-select" id="team-select">
                            <option value="">Cargando equipos...</option>
                        </select>
                        <div id="teams-loading" class="form-text text-muted" style="display: none;">
                            <i class="bi bi-arrow-repeat me-1"></i>Cargando equipos disponibles...
                        </div>
                        <div id="no-teams-available" class="form-text text-warning" style="display: none;">
                            <i class="bi bi-info-circle me-1"></i>
                            No hay equipos disponibles para asignar
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAssignTeam" disabled>
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Asignar Equipo
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal de confirmación para eliminar módulo -->
@if($isProjectCreator || $isAdmin)
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>¿Estás seguro de que deseas eliminar el módulo "{{ $module->name }}"?</strong>
                </div>
                <p class="mb-3">Esta acción no se puede deshacer y se eliminarán:</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todas las tareas del módulo</li>
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todas las asignaciones de equipos</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('module.destroy', [$project, $module]) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Eliminar Módulo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Variables de permisos desde PHP
const canManageTeams = @json($canManageTeams ?? false);
const isProjectCreator = @json($isProjectCreator ?? false);
const isAdmin = @json($isAdmin ?? false);

// Debug: verificar permisos
console.log('Permisos:', {canManageTeams, isProjectCreator, isAdmin});

// Funciones para gestión de módulo
function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function removeTask(taskId, taskTitle) {
    console.log('Intentando eliminar tarea:', taskId, taskTitle);
    
    if (confirm(`¿Estás seguro de que quieres eliminar la tarea "${taskTitle}"?`)) {
        const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
        const button = taskCard.querySelector('.btn-outline-danger');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        button.disabled = true;
        
        // URL CORREGIDA
        const url = `{{ route('module.remove-task', [$project, $module, ':taskId']) }}`.replace(':taskId', taskId);
        console.log('URL de eliminación tarea:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta eliminación tarea:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos tarea:', data);
            if (data.success) {
                taskCard.style.transition = 'all 0.3s ease';
                taskCard.style.opacity = '0';
                taskCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    taskCard.remove();
                    updateTasksCount();
                    checkIfNoTasks();
                }, 300);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error completo tarea:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            alert('Error al eliminar la tarea: ' + error.message);
        });
    }
}

function removeTeam(teamId, teamName) {
    console.log('Intentando desasignar equipo:', teamId, teamName);
    
    if (confirm(`¿Estás seguro de que quieres desasignar el equipo "${teamName}" del módulo?`)) {
        const teamCard = document.querySelector(`[data-team-id="${teamId}"]`);
        const button = teamCard.querySelector('.btn-outline-danger');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        button.disabled = true;
        
        // URL CORREGIDA
        const url = `{{ route('module.remove-team', [$project, $module, ':teamId']) }}`.replace(':teamId', teamId);
        console.log('URL de desasignación equipo:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta desasignación equipo:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos equipo:', data);
            if (data.success) {
                teamCard.style.transition = 'all 0.3s ease';
                teamCard.style.opacity = '0';
                teamCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    teamCard.remove();
                    updateTeamsCount();
                    checkIfNoTeams();
                }, 300);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error completo equipo:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            alert('Error al desasignar el equipo: ' + error.message);
        });
    }
}

function updateTasksCount() {
    const taskCards = document.querySelectorAll('[data-task-id]');
    const count = taskCards.length;
    const countElement = document.getElementById('tasks-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function updateTeamsCount() {
    const teamCards = document.querySelectorAll('[data-team-id]');
    const count = teamCards.length;
    const countElement = document.getElementById('teams-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function checkIfNoTasks() {
    const taskCards = document.querySelectorAll('[data-task-id]');
    const container = document.getElementById('tasks-container');
    
    if (taskCards.length === 0 && container) {
        container.innerHTML = `
            <div class="text-center py-4" id="no-tasks-message">
                <i class="bi bi-check2-square display-4 text-muted mb-3"></i>
                <h6 class="text-muted">No hay tareas en el módulo</h6>
                <p class="text-muted mb-3">Crea tareas para organizar el trabajo del módulo</p>
            </div>
        `;
    }
}

function checkIfNoTeams() {
    const teamCards = document.querySelectorAll('[data-team-id]');
    const container = document.getElementById('teams-container');
    
    if (teamCards.length === 0 && container) {
        container.innerHTML = `
            <div class="text-center py-4" id="no-teams-message">
                <i class="bi bi-people display-4 text-muted mb-3"></i>
                <h6 class="text-muted">No hay equipos asignados</h6>
                <p class="text-muted mb-3">Asigna equipos para organizar el trabajo del módulo</p>
            </div>
        `;
    }
}

// Cargar equipos disponibles cuando se abre el modal
const assignTeamModal = document.getElementById('assignTeamModal');
if (assignTeamModal) {
    assignTeamModal.addEventListener('show.bs.modal', function() {
        loadAvailableTeams();
    });
}

function loadAvailableTeams() {
    const teamSelect = document.getElementById('team-select');
    const loadingText = document.getElementById('teams-loading');
    const noTeamsText = document.getElementById('no-teams-available');
    const confirmButton = document.getElementById('confirmAssignTeam');
    
    if (!teamSelect) return;
    
    // Mostrar estado de carga
    teamSelect.innerHTML = '<option value="">Cargando equipos...</option>';
    teamSelect.disabled = true;
    if (loadingText) loadingText.style.display = 'block';
    if (noTeamsText) noTeamsText.style.display = 'none';
    if (confirmButton) confirmButton.disabled = true;
    
    // Obtener equipos disponibles
    fetch(`{{ route('module.available-teams', [$project, $module]) }}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(teams => {
            // Limpiar select
            teamSelect.innerHTML = '<option value="">Selecciona un equipo...</option>';
            
            // Filtrar equipos generales
            const nonGeneralTeams = teams.filter(team => !team.is_general);
            
            if (nonGeneralTeams.length > 0) {
                nonGeneralTeams.forEach(team => {
                    const option = document.createElement('option');
                    option.value = team.id;
                    option.textContent = `${team.name} - ${team.members_count} miembros`;
                    if (team.description) {
                        option.textContent += ` - ${team.description.substring(0, 60)}`;
                    }
                    teamSelect.appendChild(option);
                });
                
                if (noTeamsText) noTeamsText.style.display = 'none';
            } else {
                if (noTeamsText) noTeamsText.style.display = 'block';
            }
            
            teamSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error cargando equipos:', error);
            teamSelect.innerHTML = '<option value="">Error al cargar equipos</option>';
            teamSelect.disabled = false;
        })
        .finally(() => {
            if (loadingText) loadingText.style.display = 'none';
        });
}

// Cargar miembros disponibles cuando se abre el modal de crear tarea
const createTaskModal = document.getElementById('createTaskModal');
if (createTaskModal) {
    createTaskModal.addEventListener('show.bs.modal', function() {
        loadModuleTeamMembers();
    });
}

function loadModuleTeamMembers() {
    const membersSelect = document.getElementById('task-assigned-to');
    const loadingText = document.getElementById('members-loading');
    const noMembersText = document.getElementById('no-members-available');
    
    if (!membersSelect) return;
    
    // Mostrar estado de carga
    membersSelect.innerHTML = '<option value="">Cargando miembros...</option>';
    membersSelect.disabled = true;
    if (loadingText) loadingText.style.display = 'block';
    if (noMembersText) noMembersText.style.display = 'none';
    
    // Obtener miembros de los equipos del módulo
    fetch(`{{ route('module.team-members', [$project, $module]) }}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(members => {
            // Limpiar select
            membersSelect.innerHTML = '';
            
            if (members.length > 0) {
                members.forEach(member => {
                    const option = document.createElement('option');
                    option.value = member.id;
                    option.textContent = member.name;
                    if (member.is_current_user) {
                        option.textContent += ' (Tú)';
                    }
                    if (member.team_names) {
                        option.textContent += ` - ${member.team_names}`;
                    }
                    membersSelect.appendChild(option);
                });
                
                if (noMembersText) noMembersText.style.display = 'none';
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay miembros disponibles';
                option.disabled = true;
                membersSelect.appendChild(option);
                
                if (noMembersText) noMembersText.style.display = 'block';
            }
            
            membersSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error cargando miembros:', error);
            membersSelect.innerHTML = '<option value="" disabled>Error al cargar miembros</option>';
            membersSelect.disabled = false;
        })
        .finally(() => {
            if (loadingText) loadingText.style.display = 'none';
        });
}

// Confirmar crear tarea
const confirmCreateTaskButton = document.getElementById('confirmCreateTask');
if (confirmCreateTaskButton) {
    confirmCreateTaskButton.addEventListener('click', function() {
        const titleInput = document.getElementById('task-title');
        const descriptionInput = document.getElementById('task-description');
        const prioritySelect = document.getElementById('task-priority');
        const assignedToSelect = document.getElementById('task-assigned-to');
        
        const title = titleInput ? titleInput.value.trim() : '';
        const description = descriptionInput ? descriptionInput.value.trim() : '';
        const priority = prioritySelect ? prioritySelect.value : 'MEDIUM';
        const assignedTo = assignedToSelect ? Array.from(assignedToSelect.selectedOptions).map(option => option.value).filter(value => value !== '') : [];
        
        if (!title) {
            alert('Por favor ingresa un título para la tarea');
            return;
        }
        
        console.log('Creando tarea:', {title, description, priority, assignedTo});
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('module.create-task', [$project, $module]) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                description: description,
                priority: priority,
                assigned_to: assignedTo
            })
        })
        .then(response => {
            console.log('Respuesta crear tarea:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos crear tarea:', data);
            if (data.success) {
                const modal = document.getElementById('createTaskModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                location.reload(); // Recargar para mostrar la nueva tarea
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error crear tarea:', error);
            alert('Error al crear la tarea: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Confirmar asignar equipo
const confirmAssignTeamButton = document.getElementById('confirmAssignTeam');
if (confirmAssignTeamButton) {
    confirmAssignTeamButton.addEventListener('click', function() {
        const teamSelect = document.getElementById('team-select');
        const teamId = teamSelect ? teamSelect.value : null;
        
        if (!teamId) {
            alert('Por favor selecciona un equipo');
            return;
        }
        
        console.log('Asignando equipo:', teamId);
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('module.assign-team', [$project, $module]) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                team_id: teamId
            })
        })
        .then(response => {
            console.log('Respuesta asignar equipo:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos asignar equipo:', data);
            if (data.success) {
                const modal = document.getElementById('assignTeamModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                location.reload(); // Recargar para mostrar el nuevo equipo
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error asignar equipo:', error);
            alert('Error al asignar el equipo: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Limpiar modales al cerrarse
if (createTaskModal) {
    createTaskModal.addEventListener('hidden.bs.modal', function() {
        const titleInput = document.getElementById('task-title');
        const descriptionInput = document.getElementById('task-description');
        const prioritySelect = document.getElementById('task-priority');
        const assignedToSelect = document.getElementById('task-assigned-to');
        
        if (titleInput) titleInput.value = '';
        if (descriptionInput) descriptionInput.value = '';
        if (prioritySelect) prioritySelect.value = 'MEDIUM';
        if (assignedToSelect) {
            assignedToSelect.innerHTML = '';
            assignedToSelect.disabled = false;
        }
    });
}

if (assignTeamModal) {
    assignTeamModal.addEventListener('hidden.bs.modal', function() {
        const teamSelect = document.getElementById('team-select');
        const confirmButton = document.getElementById('confirmAssignTeam');
        
        if (teamSelect) {
            teamSelect.value = '';
            teamSelect.disabled = false;
        }
        if (confirmButton) confirmButton.disabled = true;
    });
}

// Habilitar/deshabilitar botón de asignar equipo
const teamSelect = document.getElementById('team-select');
if (teamSelect) {
    teamSelect.addEventListener('change', function() {
        const button = document.getElementById('confirmAssignTeam');
        if (button) {
            button.disabled = !this.value || this.disabled;
        }
    });
}

// Efectos hover para cards clickables
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando módulo...');
    
    const style = document.createElement('style');
    style.textContent = `
        .clickable-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.375rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .clickable-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .clickable-task:hover {
            background-color: #f8f9fc !important;
            transform: translateX(3px);
        }
        
        .clickable-task {
            transition: all 0.2s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #4e73df;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    flex-shrink: 0;
}

.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-title {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.avatar-sm .avatar-title {
    width: 30px;
    height: 30px;
    font-size: 12px;
}

.feature-icon {
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    min-width: 50px;
    min-height: 50px;
}

.feature-icon.primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.feature-icon.secondary {
    background: linear-gradient(45deg, #858796, #60616f);
}

.feature-icon.success {
    background: linear-gradient(45deg, #1cc88a, #17a673);
}

.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.feature-card {
    transition: all 0.3s;
}

.feature-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
}
</style>
@endsection