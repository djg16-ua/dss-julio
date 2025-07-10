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
                                <div class="mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(78, 115, 223, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-kanban text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary">Información del Proyecto</h4>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-person me-2"></i>Creador
                                </h6>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ strtoupper(substr($project->creator->name, 0, 1)) }}
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
                                    <i class="bi bi-collection text-success mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['total_modules'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Módulos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-check2-square text-secondary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['total_tasks'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Tareas Totales</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-check-circle-fill text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['completed_tasks'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Tareas Completadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-people text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $projectStats['team_members'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 small">Miembros del Proyecto</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del proyecto -->
                    @if($project->description)
                        <div class="card feature-card" style="max-height: 140px; overflow: hidden;">
                            <div class="card-body py-2 px-3">
                                <h6 class="fw-bold text-primary mb-1">
                                    <i class="bi bi-card-text me-2"></i>Descripción
                                </h6>
                                <div class="description-content">
                                    <p class="text-muted mb-0" style="line-height: 1.4;">
                                        {{ Str::limit($project->description, 324) }}
                                    </p>
                                </div>
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
                                    Miembros del Proyecto (<span id="members-count">{{ $projectStats['team_members'] ?? 0 }}</span>)
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                    <i class="bi bi-person-plus me-1"></i>Añadir Miembro
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="members-container">
                                @php
                                    $generalTeam = $project->getGeneralTeam();
                                    $projectMembers = $generalTeam ? $generalTeam->users()->where('team_user.is_active', true)->get() : collect();
                                    
                                    // Orden jerárquico de roles
                                    $roleOrder = ['LEAD', 'SENIOR_DEV', 'DEVELOPER', 'JUNIOR_DEV', 'DESIGNER', 'TESTER', 'ANALYST', 'OBSERVER'];
                                    $sortedMembers = $projectMembers->sortBy(function($member) use ($roleOrder) {
                                        return array_search($member->pivot->role, $roleOrder);
                                    });
                                @endphp
                                
                                @if($sortedMembers->count() > 0)
                                    <div class="row g-3">
                                        @foreach($sortedMembers as $member)
                                            <div class="col-lg-4 col-md-6" data-member-id="{{ $member->id }}">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-3">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
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
                                    <div class="text-center py-4" id="no-members-message">
                                        <i class="bi bi-people display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay miembros en el proyecto</h6>
                                        <p class="text-muted mb-3">Añade miembros para empezar a colaborar</p>
                                    </div>
                                @endif
                            </div>
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
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('team.index', $project) }}" class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-list me-1"></i>Ver Todo
                                            </a>
                                            <a href="{{ route('team.create', $project) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-circle me-1"></i>Crear Equipo
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($project->teams->count() > 0)
                                        <div class="row g-3" id="teams-container">
                                            @foreach($project->teams->take(4) as $team)
                                            <div class="col-lg-6 team-item">
                                                <div class="card border h-100 clickable-card" 
                                                    onclick="window.location.href='{{ route('team.show', [$project, $team]) }}'"
                                                    style="cursor: pointer;">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-start">
                                                            <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="bi bi-people-fill text-secondary" style="font-size: 1.5rem;"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="fw-bold mb-0">{{ $team->name }}</h6>
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
                                        
                                        @if($project->teams->count() > 4)
                                            <div class="text-center mt-3">
                                                <button type="button" class="btn btn-link" id="show-more-teams">
                                                    Ver más <i class="bi bi-chevron-down"></i>
                                                </button>
                                                <div id="hidden-teams" style="display: none;">
                                                    <div class="row g-3 mt-2">
                                                        @foreach($project->teams->skip(4) as $team)
                                                        <div class="col-lg-6 team-item">
                                                            <div class="card border h-100 clickable-card" 
                                                                onclick="window.location.href='{{ route('team.show', [$project, $team]) }}'"
                                                                style="cursor: pointer;">
                                                                <div class="card-body p-3">
                                                                    <div class="d-flex align-items-start">
                                                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                                            <i class="bi bi-people-fill text-secondary" style="font-size: 1.5rem;"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <h6 class="fw-bold mb-0">{{ $team->name }}</h6>
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
                                                    <div class="text-center mt-3">
                                                        <button type="button" class="btn btn-link" id="show-less-teams">
                                                            Ver menos <i class="bi bi-chevron-up"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-collection text-primary me-2"></i>
                                            Módulos del Proyecto ({{ $project->modules->count() }})
                                        </h5>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('module.index', $project) }}" class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-list me-1"></i>Ver Todo
                                            </a>
                                            <a href="{{ route('module.create', $project) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-circle me-1"></i>Crear Módulo
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($project->modules->count() > 0)
                                        <div class="row g-3" id="modules-container">
                                            @foreach($project->modules->take(4) as $module)
                                            <div class="col-lg-6 module-item">
                                                <div class="card border clickable-card" 
                                                    onclick="window.location.href='{{ route('module.show', [$project, $module]) }}'"
                                                    style="cursor: pointer;">
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
                                        
                                        @if($project->modules->count() > 4)
                                            <div class="text-center mt-3">
                                                <button type="button" class="btn btn-link" id="show-more-modules">
                                                    Ver más <i class="bi bi-chevron-down"></i>
                                                </button>
                                                <div id="hidden-modules" style="display: none;">
                                                    <div class="row g-3 mt-2">
                                                        @foreach($project->modules->skip(4) as $module)
                                                        <div class="col-lg-6 module-item">
                                                            <div class="card border clickable-card" 
                                                                onclick="window.location.href='{{ route('module.show', [$project, $module]) }}'"
                                                                style="cursor: pointer;">
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
                                                    <div class="text-center mt-3">
                                                        <button type="button" class="btn btn-link" id="show-less-modules">
                                                            Ver menos <i class="bi bi-chevron-up"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
                                    <div class="card-header bg-white py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">
                                                <i class="bi bi-check2-square text-primary me-2"></i>
                                                Tareas del Proyecto (<span id="tasks-count">{{ $projectStats['total_tasks'] ?? 0 }}</span> total)
                                            </h5>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('task.index', $project) }}" class="btn btn-outline-info btn-sm">
                                                    <i class="bi bi-list me-1"></i>Ver Todo
                                                </a>
                                                <a href="{{ route('task.create', $project) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus-circle me-1"></i>Crear Tarea
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Filtro por módulo -->
                                    <div class="mb-3">
                                        <select class="form-select form-select-sm" id="module-filter" style="max-width: 300px;">
                                            <option value="">Todos los módulos</option>
                                            @foreach($project->modules as $module)
                                                <option value="{{ $module->id }}">{{ $module->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Loading spinner -->
                                    <div id="tasks-loading" style="display: none;">
                                        <div class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando tareas...</span>
                                            </div>
                                            <p class="text-muted mt-2">Filtrando tareas...</p>
                                        </div>
                                    </div>

                                    @php
                                        $allTasks = $project->modules->flatMap(function($module) {
                                            return $module->tasks->map(function($task) use ($module) {
                                                $task->module = $module; // Asegurar relación
                                                return $task;
                                            });
                                        });
                                        
                                        // Ordenación inicial
                                        $statusOrder = ['ACTIVE' => 1, 'PENDING' => 2];
                                        $priorityOrder = ['URGENT' => 1, 'HIGH' => 2, 'MEDIUM' => 3, 'LOW' => 4];
                                        
                                        $allTasks = $allTasks->sort(function($a, $b) use ($statusOrder, $priorityOrder) {
                                            $statusA = $statusOrder[$a->status] ?? 3;
                                            $statusB = $statusOrder[$b->status] ?? 3;
                                            
                                            if ($statusA !== $statusB) {
                                                return $statusA <=> $statusB;
                                            }
                                            
                                            $priorityA = $priorityOrder[$a->priority] ?? 5;
                                            $priorityB = $priorityOrder[$b->priority] ?? 5;
                                            
                                            return $priorityA <=> $priorityB;
                                        });
                                    @endphp

                                    <!-- Container de tareas -->
                                    <div id="tasks-container">
                                        @include('project.partials.tasks-list', ['tasks' => $allTasks])
                                    </div>
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
                                            <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-plus-circle text-primary" style="font-size: 1.5rem;"></i>
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
                                                <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-pencil text-secondary" style="font-size: 1.5rem;"></i>
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
                                                <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-people text-success" style="font-size: 1.5rem;"></i>
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
                            <option value="DEVELOPER">💻 Desarrollador</option>
                            <option value="SENIOR_DEV">🚀 Desarrollador Senior</option>
                            <option value="JUNIOR_DEV">🌱 Desarrollador Junior</option>
                            <option value="DESIGNER">🎨 Diseñador</option>
                            <option value="TESTER">🧪 Tester</option>
                            <option value="ANALYST">📊 Analista</option>
                            <option value="OBSERVER">👀 Observador</option>
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Solo puede haber un líder por proyecto (el creador)
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAddMember" disabled>
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Añadir al Proyecto
                </button>
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

// Control de descripción expandible
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-description');
    const toggleLessBtn = document.getElementById('toggle-description-less');
    const shortDesc = document.getElementById('project-description');
    const fullDesc = document.getElementById('full-description');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            shortDesc.style.display = 'none';
            fullDesc.style.display = 'block';
            toggleBtn.style.display = 'none';
        });
    }
    
    if (toggleLessBtn) {
        toggleLessBtn.addEventListener('click', function() {
            shortDesc.style.display = 'block';
            fullDesc.style.display = 'none';
            toggleBtn.style.display = 'inline-block';
        });
    }
});

// Funciones para gestión de miembros
function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function removeMember(userId, userName) {
    if (confirm(`¿Estás seguro de que quieres remover a ${userName} del proyecto?`)) {
        // Mostrar loading en el botón
        const memberCard = document.querySelector(`[data-member-id="${userId}"]`);
        const button = memberCard.querySelector('.btn-outline-danger');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        button.disabled = true;
        
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
                // Remover card del DOM con animación
                memberCard.style.transition = 'all 0.3s ease';
                memberCard.style.opacity = '0';
                memberCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    memberCard.remove();
                    updateMembersCount();
                    checkIfNoMembers();
                }, 300);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            alert('Error al remover el miembro');
        });
    }
}

function updateMembersCount() {
    const memberCards = document.querySelectorAll('[data-member-id]');
    const count = memberCards.length;
    document.getElementById('members-count').textContent = count;
}

function checkIfNoMembers() {
    const memberCards = document.querySelectorAll('[data-member-id]');
    const container = document.getElementById('members-container');
    
    if (memberCards.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4" id="no-members-message">
                <i class="bi bi-people display-4 text-muted mb-3"></i>
                <h6 class="text-muted">No hay miembros en el proyecto</h6>
                <p class="text-muted mb-3">Añade miembros para empezar a colaborar</p>
            </div>
        `;
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
                <div class="avatar-circle me-2">
                    ${user.name.charAt(0).toUpperCase()}
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
    
    const button = this;
    const spinner = button.querySelector('.spinner-border');
    const role = document.getElementById('user-role').value;
    
    // Mostrar loading
    spinner.style.display = 'inline-block';
    button.disabled = true;
    
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
            addMemberToDOM(data.member);
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al añadir el miembro');
    })
    .finally(() => {
        spinner.style.display = 'none';
        button.disabled = false;
    });
});

function addMemberToDOM(member) {
    // Remover mensaje de "no hay miembros" si existe
    const noMembersMsg = document.getElementById('no-members-message');
    if (noMembersMsg) {
        noMembersMsg.remove();
    }
    
    // Buscar contenedor de miembros
    let membersGrid = document.querySelector('#members-container .row.g-3');
    
    // Si no existe grid, crearlo
    if (!membersGrid) {
        const container = document.getElementById('members-container');
        container.innerHTML = '<div class="row g-3"></div>';
        membersGrid = container.querySelector('.row.g-3');
    }
    
    // Crear nueva card de miembro
    const memberCard = document.createElement('div');
    memberCard.className = 'col-lg-4 col-md-6';
    memberCard.setAttribute('data-member-id', member.id);
    memberCard.style.opacity = '0';
    memberCard.style.transform = 'scale(0.8)';
    
    const roleClass = member.role === 'LEAD' ? 'warning' : 'info';
    const isCurrentUser = member.id === {{ auth()->id() }};
    const canRemove = member.id !== {{ $project->created_by }} && member.id !== {{ auth()->id() }};
    
    memberCard.innerHTML = `
        <div class="d-flex align-items-center justify-content-between p-3 border rounded">
            <div class="d-flex align-items-center">
                <div class="avatar-circle me-3">
                    ${member.name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <h6 class="mb-0">${member.name}</h6>
                    <small class="text-muted">${member.email}</small>
                    <br>
                    <span class="badge bg-${roleClass}">
                        ${member.role}
                    </span>
                    ${isCurrentUser ? '<span class="badge bg-success">Tú</span>' : ''}
                </div>
            </div>
            ${canRemove ? `
                <button type="button" class="btn btn-outline-danger btn-sm" 
                        onclick="removeMember(${member.id}, '${member.name}')"
                        title="Remover del proyecto">
                    <i class="bi bi-x"></i>
                </button>
            ` : ''}
        </div>
    `;
    
    // Determinar posición según jerarquía de roles
    const roleOrder = ['LEAD', 'SENIOR_DEV', 'DEVELOPER', 'JUNIOR_DEV', 'DESIGNER', 'TESTER', 'ANALYST', 'OBSERVER'];
    const memberRoleIndex = roleOrder.indexOf(member.role);
    
    // Buscar posición correcta
    let inserted = false;
    const existingMembers = membersGrid.querySelectorAll('[data-member-id]');
    
    for (let existingMember of existingMembers) {
        const existingRole = existingMember.querySelector('.badge').textContent.trim();
        const existingRoleIndex = roleOrder.indexOf(existingRole);
        
        if (memberRoleIndex < existingRoleIndex) {
            membersGrid.insertBefore(memberCard, existingMember);
            inserted = true;
            break;
        }
    }
    
    // Si no se insertó en ninguna posición, añadir al final
    if (!inserted) {
        membersGrid.appendChild(memberCard);
    }
    
    // Animar entrada
    setTimeout(() => {
        memberCard.style.transition = 'all 0.3s ease';
        memberCard.style.opacity = '1';
        memberCard.style.transform = 'scale(1)';
    }, 50);
    
    // Actualizar contador
    updateMembersCount();
}

// Limpiar modal al cerrarse
document.getElementById('addMemberModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('member-search').value = '';
    document.getElementById('search-results').innerHTML = '';
    document.getElementById('selected-user').style.display = 'none';
    document.getElementById('confirmAddMember').disabled = true;
    selectedUserId = null;
});

// Filtro de tareas por módulo con AJAX
document.getElementById('module-filter').addEventListener('change', function() {
    const selectedModuleId = this.value;
    const loadingDiv = document.getElementById('tasks-loading');
    const tasksContainer = document.getElementById('tasks-container');
    
    // Mostrar loading
    loadingDiv.style.display = 'block';
    tasksContainer.style.display = 'none';
    
    // Construir URL con parámetros
    const url = new URL(`{{ route('project.filter-tasks', $project) }}`);
    if (selectedModuleId) {
        url.searchParams.append('module_id', selectedModuleId);
    }
    
    // Realizar petición AJAX
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Actualizar contenido
        tasksContainer.innerHTML = data.html;
    
        // Actualizar contador solo si el elemento existe
        const tasksCountElement = document.getElementById('tasks-count');
        if (tasksCountElement) {
            tasksCountElement.textContent = data.count;
        }
        
        // Re-aplicar event listeners para los nuevos botones
        attachTaskEventListeners();
        
        // Ocultar loading y mostrar contenido
        loadingDiv.style.display = 'none';
        tasksContainer.style.display = 'block'
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Mostrar error
        tasksContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                <h6 class="text-danger">Error al cargar tareas</h6>
                <p class="text-muted mb-2">Detalle: ${error.message}</p>
                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Recargar página
                </button>
            </div>
        `;
        
        loadingDiv.style.display = 'none';
        tasksContainer.style.display = 'block';
    });
});

// Función para aplicar event listeners a los botones de tareas
function attachTaskEventListeners() {
    // Botón "Ver más"
    const showMoreBtn = document.getElementById('show-more-tasks');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function() {
            // Mostrar todas las tareas ocultas
            document.querySelectorAll('.hidden-task').forEach(task => {
                task.style.display = 'block';
            });
            // Intercambiar botones
            this.style.display = 'none';
            const showLessBtn = document.getElementById('show-less-tasks');
            if (showLessBtn) {
                showLessBtn.style.display = 'inline-block';
            }
        });
    }
    
    // Botón "Ver menos"
    const showLessBtn = document.getElementById('show-less-tasks');
    if (showLessBtn) {
        showLessBtn.addEventListener('click', function() {
            // Ocultar tareas adicionales
            document.querySelectorAll('.hidden-task').forEach(task => {
                task.style.display = 'none';
            });
            // Intercambiar botones
            this.style.display = 'none';
            const showMoreBtn = document.getElementById('show-more-tasks');
            if (showMoreBtn) {
                showMoreBtn.style.display = 'inline-block';
            }
        });
    }
} 

// Aplicar event listeners inicial
document.addEventListener('DOMContentLoaded', function() {
    attachTaskEventListeners();
    
    // Efectos hover para tareas clickables
    const style = document.createElement('style');
    style.textContent = `
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

// Funcionalidad "Ver más" para equipos
document.getElementById('show-more-teams')?.addEventListener('click', function() {
    document.getElementById('hidden-teams').style.display = 'block';
    this.style.display = 'none';
});

document.getElementById('show-less-teams')?.addEventListener('click', function() {
    document.getElementById('hidden-teams').style.display = 'none';
    document.getElementById('show-more-teams').style.display = 'inline-block';
});

// Funcionalidad "Ver más" para módulos
document.getElementById('show-more-modules')?.addEventListener('click', function() {
    document.getElementById('hidden-modules').style.display = 'block';
    this.style.display = 'none';
});

document.getElementById('show-less-modules')?.addEventListener('click', function() {
    document.getElementById('hidden-modules').style.display = 'none';
    document.getElementById('show-more-modules').style.display = 'inline-block';
});

// Efectos hover para cards clickables
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .clickable-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.375rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .clickable-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});

// Funcionalidad "Ver más" para tareas (simplificado)
document.addEventListener('click', function(e) {
    if (e.target.id === 'show-more-tasks' || e.target.closest('#show-more-tasks')) {
        // Mostrar todas las tareas ocultas
        document.querySelectorAll('.hidden-task').forEach(task => {
            task.style.display = 'block';
        });
        // Ocultar el botón "Ver más"
        e.target.closest('button').style.display = 'none';
    }
});

// Efectos hover para tareas clickables
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
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

.user-result:hover {
    background-color: #f8f9fc;
}

.user-result.border-primary {
    background-color: #e3f2fd;
}
</style>
@endsection