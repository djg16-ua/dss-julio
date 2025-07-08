@extends('layouts.app')

@section('title', $project->title . ' - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header del proyecto -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('project.index') }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary mb-0">
                                <i class="bi bi-kanban me-3"></i>{{ $project->title }}
                            </h1>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @switch($project->status)
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
                                <span class="badge bg-light text-dark fs-6">{{ $project->status }}</span>
                        @endswitch

                        @if($project->public)
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-globe me-1"></i>Público
                            </span>
                        @else
                            <span class="badge bg-dark fs-6">
                                <i class="bi bi-lock me-1"></i>Privado
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
                        <a href="{{ route('team.index', $project) }}" class="btn btn-info">
                            <i class="bi bi-people me-2"></i>Ver Equipos
                        </a>
                        <a href="{{ route('project.edit', $project) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                        @if($project->created_by === auth()->id())
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="bi bi-trash me-2"></i>Eliminar
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del proyecto -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="feature-icon primary mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="bi bi-kanban" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary">Información del Proyecto</h4>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-person me-2"></i>Creador
                                </h6>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title rounded-circle bg-primary text-white">
                                            {{ strtoupper(substr($project->creator->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $project->creator->name }}</strong>
                                        @if($project->creator->id === auth()->id())
                                            <small class="text-primary">(Tú)</small>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $project->creator->email }}</small>
                                    </div>
                                </div>
                            </div>

                            @if($project->start_date || $project->end_date)
                                <div class="mb-3">
                                    <h6 class="fw-bold text-primary">
                                        <i class="bi bi-calendar me-2"></i>Fechas
                                    </h6>
                                    @if($project->start_date)
                                        <div class="small mb-1">
                                            <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                    @if($project->end_date)
                                        <div class="small mb-1">
                                            <strong>Fin:</strong> {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-clock-history me-2"></i>Historial
                                </h6>
                                <div class="small">
                                    <div class="mb-1">
                                        <strong>Creado:</strong> {{ $project->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div>
                                        <strong>Actualizado:</strong> {{ $project->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row g-3 mb-4">
                        <!-- Estadísticas del proyecto - 2x2 Grid -->
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon success mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-collection"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['total_modules'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Módulos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon secondary mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-check2-square"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['total_tasks'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Tareas Totales</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon primary mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['completed_tasks'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Tareas Completadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <div class="feature-icon primary mx-auto mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['team_members'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Miembros del Proyecto</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del proyecto -->
                    @if($project->description)
                        <div class="card feature-card">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-card-text me-2"></i>Descripción
                                </h5>
                                <p class="text-muted mb-0" style="line-height: 1.6;">{{ $project->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gestión rápida de miembros del proyecto -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-people-fill text-primary me-2"></i>
                                    Miembros del Proyecto ({{ $projectStats['team_members'] ?? 0 }})
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                    <i class="bi bi-person-plus me-1"></i>Añadir Miembro
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $generalTeam = $project->getGeneralTeam();
                                $projectMembers = $generalTeam ? $generalTeam->users()->where('team_user.is_active', true)->get() : collect();
                            @endphp
                            
                            @if($projectMembers->count() > 0)
                                <div class="row g-3">
                                    @foreach($projectMembers as $member)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <div class="avatar-title rounded-circle bg-primary text-white">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $member->name }}</h6>
                                                        <small class="text-muted">{{ $member->email }}</small>
                                                        <br>
                                                        <span class="badge bg-{{ $member->pivot->role === 'LEAD' ? 'warning' : 'info' }}">
                                                            {{ $member->pivot->role }}
                                                        </span>
                                                        @if($member->id === auth()->id())
                                                            <span class="badge bg-success">Tú</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($member->id !== $project->created_by && $member->id !== auth()->id())
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="removeMember({{ $member->id }}, '{{ $member->name }}')"
                                                            title="Remover del proyecto">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-people display-4 text-muted mb-3"></i>
                                    <h6 class="text-muted">No hay miembros en el proyecto</h6>
                                    <p class="text-muted mb-3">Añade miembros para empezar a colaborar</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-4" id="projectTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="teams-tab" data-bs-toggle="pill" data-bs-target="#teams" type="button" role="tab">
                                <i class="bi bi-people me-2"></i>Equipos Personalizados
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="modules-tab" data-bs-toggle="pill" data-bs-target="#modules" type="button" role="tab">
                                <i class="bi bi-collection me-2"></i>Módulos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tasks-tab" data-bs-toggle="pill" data-bs-target="#tasks" type="button" role="tab">
                                <i class="bi bi-check2-square me-2"></i>Tareas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity" type="button" role="tab">
                                <i class="bi bi-clock-history me-2"></i>Actividad
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="projectTabContent">
                        <!-- Equipos personalizados (no el general) -->
                        <div class="tab-pane fade show active" id="teams" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-diagram-3 text-primary me-2"></i>
                                            Equipos Personalizados ({{ $project->teams->count() }})
                                        </h5>
                                        <a href="{{ route('team.create', $project) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus-circle me-1"></i>Crear Equipo
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($project->teams->count() > 0)
                                        <div class="row g-3">
                                            @foreach($project->teams as $team)
                                            <div class="col-lg-6">
                                                <div class="card border h-100">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-start">
                                                            <div class="feature-icon secondary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                                <i class="bi bi-people-fill"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <h6 class="fw-bold mb-0">{{ $team->name }}</h6>
                                                                    <a href="{{ route('team.show', [$project, $team]) }}" class="btn btn-outline-primary btn-sm">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                </div>
                                                                @if($team->description)
                                                                    <p class="text-muted small mb-2">{{ Str::limit($team->description, 80) }}</p>
                                                                @endif
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-person-check me-1"></i>
                                                                        {{ $team->users->where('pivot.is_active', true)->count() }} miembros
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-calendar-plus me-1"></i>
                                                                        {{ $team->created_at->format('d/m/Y') }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('team.index', $project) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-list me-2"></i>Ver Todos los Equipos
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-diagram-3 display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted">No hay equipos personalizados</h6>
                                            <p class="text-muted mb-3">Crea equipos específicos para organizar mejor el trabajo</p>
                                            <a href="{{ route('team.create', $project) }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Crear Primer Equipo
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Módulos del proyecto -->
                        <div class="tab-pane fade" id="modules" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-collection text-primary me-2"></i>
                                        Módulos del Proyecto ({{ $project->modules->count() }})
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($project->modules->count() > 0)
                                        <div class="row g-3">
                                            @foreach($project->modules as $module)
                                            <div class="col-lg-6">
                                                <div class="card border">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-bold mb-0">{{ $module->name }}</h6>
                                                            <div class="d-flex flex-column align-items-end gap-1">
                                                                @switch($module->status)
                                                                    @case('ACTIVE')
                                                                        <span class="badge bg-success">Activo</span>
                                                                        @break
                                                                    @case('PENDING')
                                                                        <span class="badge bg-warning">Pendiente</span>
                                                                        @break
                                                                    @case('DONE')
                                                                        <span class="badge bg-info">Completado</span>
                                                                        @break
                                                                    @default
                                                                        <span class="badge bg-secondary">{{ $module->status }}</span>
                                                                @endswitch
                                                                
                                                                @switch($module->priority)
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
                                                            </div>
                                                        </div>
                                                        @if($module->description)
                                                            <p class="text-muted small mb-2">{{ Str::limit($module->description, 100) }}</p>
                                                        @endif
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="bi bi-list-task me-1"></i>
                                                                {{ $module->tasks->count() }} tareas
                                                            </small>
                                                            <small class="text-muted">
                                                                @if($module->is_core)
                                                                    <i class="bi bi-star-fill text-warning me-1"></i>Módulo core
                                                                @else
                                                                    <i class="bi bi-circle me-1"></i>Módulo estándar
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-collection display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted">No hay módulos definidos</h6>
                                            <p class="text-muted mb-0">Este proyecto aún no tiene módulos creados</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tareas del proyecto -->
                        <div class="tab-pane fade" id="tasks" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-check2-square text-primary me-2"></i>
                                        Tareas Recientes ({{ $projectStats['total_tasks'] ?? 0 }} total)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $allTasks = $project->modules->flatMap(function($module) {
                                            return $module->tasks;
                                        })->sortByDesc('created_at');
                                    @endphp
                                    
                                    @if($allTasks->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($allTasks->take(10) as $task)
                                            <div class="list-group-item border-0 px-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="me-3">
                                                        <i class="bi bi-{{ $task->status === 'DONE' ? 'check-circle-fill text-success' : ($task->status === 'ACTIVE' ? 'play-circle-fill text-primary' : 'circle text-muted') }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="fw-bold mb-1">{{ $task->title }}</h6>
                                                                @if($task->description)
                                                                    <p class="text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                                                                @endif
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
                                                                    {{ \Carbon\Carbon::parse($task->end_date)->format('d/m/Y') }}
                                                                </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            
                                            @if($allTasks->count() > 10)
                                                <div class="text-center mt-3">
                                                    <small class="text-muted">Mostrando las 10 tareas más recientes de {{ $allTasks->count() }} total</small>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-check2-all display-4 text-success mb-3"></i>
                                            <h6 class="text-muted">No hay tareas creadas</h6>
                                            <p class="text-muted mb-0">Este proyecto aún no tiene tareas asignadas</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actividad del proyecto -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
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
                                            <div class="feature-icon primary me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                <i class="bi bi-plus-circle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1">Proyecto creado</h6>
                                                <p class="text-muted small mb-1">{{ $project->creator->name }} creó el proyecto</p>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $project->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        @if($project->updated_at != $project->created_at)
                                            <div class="d-flex mb-3">
                                                <div class="feature-icon secondary me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                    <i class="bi bi-pencil"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">Proyecto actualizado</h6>
                                                    <p class="text-muted small mb-1">Se realizaron cambios en el proyecto</p>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $project->updated_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($project->teams->count() > 0)
                                            <div class="d-flex mb-3">
                                                <div class="feature-icon success me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">Equipos personalizados creados</h6>
                                                    <p class="text-muted small mb-1">{{ $project->teams->count() }} equipo(s) personalizado(s) en el proyecto</p>
                                                    <small class="text-muted">
                                                        <i class="bi bi-diagram-3 me-1"></i>
                                                        Equipos: {{ $project->teams->pluck('name')->join(', ') }}
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
    </div>
</div>

<!-- Modal para añadir miembro -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Añadir Miembro al Proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMemberForm">
                    @csrf
                    <div class="mb-3">
                        <label for="member-search" class="form-label">Buscar Usuario</label>
                        <input type="text" class="form-control" id="member-search" placeholder="Escribe nombre o email...">
                        <div class="form-text">Escribe al menos 2 caracteres para buscar</div>
                    </div>
                    
                    <div id="search-results" class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        <!-- Resultados de búsqueda aparecerán aquí -->
                    </div>
                    
                    <div class="mb-3" id="selected-user" style="display: none;">
                        <label for="user-role" class="form-label">Rol en el proyecto</label>
                        <select class="form-select" id="user-role">
                            <option value="DEVELOPER">Desarrollador</option>
                            <option value="SENIOR_DEV">Desarrollador Senior</option>
                            <option value="LEAD">Líder</option>
                            <option value="DESIGNER">Diseñador</option>
                            <option value="TESTER">Tester</option>
                            <option value="ANALYST">Analista</option>
                            <option value="OBSERVER">Observador</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAddMember" disabled>Añadir al Proyecto</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar proyecto -->
@if($project->created_by === auth()->id())
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
                    <strong>¿Estás seguro de que deseas eliminar el proyecto "{{ $project->title }}"?</strong>
                </div>
                <p class="mb-3">Esta acción no se puede deshacer y se eliminarán:</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todos los equipos del proyecto</li>
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todos los módulos del proyecto</li>
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todas las tareas asociadas</li>
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todos los comentarios</li>
                </ul>
                <p class="text-muted">Solo tú, como creador del proyecto, puedes realizar esta acción.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('project.destroy', $project) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Eliminar Proyecto
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
let selectedUserId = null;

// Funciones para gestión de miembros
function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function removeMember(userId, userName) {
    if (confirm(`¿Estás seguro de que quieres remover a ${userName} del proyecto?`)) {
        fetch(`{{ route('project.remove-member', [$project, '']) }}/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Recargar para mostrar cambios
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al remover el miembro');
        });
    }
}

// Búsqueda de usuarios para añadir
let searchTimeout;
document.getElementById('member-search').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    clearTimeout(searchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('search-results').innerHTML = '';
        document.getElementById('selected-user').style.display = 'none';
        document.getElementById('confirmAddMember').disabled = true;
        selectedUserId = null;
        return;
    }
    
    searchTimeout = setTimeout(() => {
        searchUsers(searchTerm);
    }, 300);
});

function searchUsers(term) {
    document.getElementById('search-results').innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <span class="ms-2">Buscando usuarios...</span>
        </div>
    `;
    
    fetch(`/project-search-users?term=${encodeURIComponent(term)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(users => {
        displaySearchResults(users);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('search-results').innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>Error al buscar usuarios
            </div>
        `;
    });
}

function displaySearchResults(users) {
    const container = document.getElementById('search-results');
    
    if (users.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-person-x me-2"></i>No se encontraron usuarios
            </div>
        `;
        return;
    }
    
    container.innerHTML = users.map(user => `
        <div class="border rounded p-2 mb-2 user-result" data-user-id="${user.id}" style="cursor: pointer;">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                    <div class="avatar-title rounded-circle bg-primary text-white">
                        ${user.name.charAt(0).toUpperCase()}
                    </div>
                </div>
                <div>
                    <strong>${user.name}</strong><br>
                    <small class="text-muted">${user.email}</small>
                </div>
            </div>
        </div>
    `).join('');
    
    // Añadir event listeners
    document.querySelectorAll('.user-result').forEach(element => {
        element.addEventListener('click', function() {
            selectUser(this.dataset.userId, users.find(u => u.id == this.dataset.userId));
        });
    });
}

function selectUser(userId, user) {
    selectedUserId = userId;
    
    // Resaltar usuario seleccionado
    document.querySelectorAll('.user-result').forEach(el => el.classList.remove('border-primary'));
    document.querySelector(`[data-user-id="${userId}"]`).classList.add('border-primary');
    
    // Mostrar selector de rol
    document.getElementById('selected-user').style.display = 'block';
    document.getElementById('confirmAddMember').disabled = false;
}

// Confirmar añadir miembro
document.getElementById('confirmAddMember').addEventListener('click', function() {
    if (!selectedUserId) return;
    
    const role = document.getElementById('user-role').value;
    
    fetch(`{{ route('project.add-member', $project) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user_id: selectedUserId,
            role: role
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addMemberModal')).hide();
            location.reload(); // Recargar para mostrar cambios
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al añadir el miembro');
    });
});

// Limpiar modal al cerrarse
document.getElementById('addMemberModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('member-search').value = '';
    document.getElementById('search-results').innerHTML = '';
    document.getElementById('selected-user').style.display = 'none';
    document.getElementById('confirmAddMember').disabled = true;
    selectedUserId = null;
});
</script>
@endpush
@endsection