@extends('layouts.app')

@section('title', 'Editar Proyecto - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-success">
                        <i class="bi bi-pencil-square me-3"></i>Editar Proyecto
                    </h1>
                    <p class="lead text-muted">
                        Gestiona la información y recursos del proyecto <strong>{{ $project->title }}</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.projects') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Proyectos
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            <!-- Información básica del proyecto -->
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información Básica
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label fw-bold">Título del Proyecto</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $project->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="status" class="form-label fw-bold">Estado</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            @foreach($projectStatuses as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $project->status) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">Descripción</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3" 
                                                  placeholder="Describe el objetivo y alcance del proyecto...">{{ old('description', $project->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="start_date" class="form-label fw-bold">Fecha de Inicio</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                               id="start_date" name="start_date" 
                                               value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="end_date" class="form-label fw-bold">Fecha de Fin</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                               id="end_date" name="end_date" 
                                               value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="public" class="form-label fw-bold">Visibilidad</label>
                                        <select class="form-select @error('public') is-invalid @enderror" id="public" name="public" required>
                                            <option value="0" {{ old('public', $project->public) == '0' ? 'selected' : '' }}>Privado</option>
                                            <option value="1" {{ old('public', $project->public) == '1' ? 'selected' : '' }}>Público</option>
                                        </select>
                                        @error('public')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-lg me-2"></i>Actualizar Información
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Estadísticas del proyecto -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-primary">{{ $project->teams->count() }}</div>
                                        <small class="text-muted">Equipos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-success">{{ $project->modules->count() }}</div>
                                        <small class="text-muted">Módulos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-warning">{{ $project->modules->sum(function($m) { return $m->tasks->count(); }) }}</div>
                                        <small class="text-muted">Tareas</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-info">{{ $project->created_at->diffInDays() }}</div>
                                        <small class="text-muted">Días Activo</small>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="small text-muted">
                                <div><strong>Creado por:</strong> {{ $project->creator->name }}</div>
                                <div><strong>Fecha:</strong> {{ $project->created_at->format('d/m/Y H:i') }}</div>
                                <div><strong>Actualizado:</strong> {{ $project->updated_at->format('d/m/Y H:i') }}</div>
                                <div><strong>ID:</strong> {{ $project->id }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de equipos -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-people-fill me-2"></i>
                                        Equipos Asignados ({{ $project->teams->count() }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#assignTeamModal">
                                        <i class="bi bi-plus-circle me-1"></i>Asignar Equipo
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($project->teams->count() > 0)
                            <div class="row g-3">
                                @foreach($project->teams as $team)
                                <div class="col-lg-6">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="feature-icon primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                    <i class="bi bi-people-fill"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold">{{ $team->name }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($team->description, 80) }}</p>
                                                    <div class="d-flex gap-2 mb-2">
                                                        <span class="badge bg-light text-dark">
                                                            {{ $team->users->where('pivot.is_active', true)->count() }} miembros
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Asignado: {{ \Carbon\Carbon::parse($team->pivot->assigned_at)->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#unassignTeamModal{{ $team->id }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para desasignar equipo -->
                                <div class="modal fade" id="unassignTeamModal{{ $team->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Desasignar Equipo</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que quieres desasignar el equipo <strong>{{ $team->name }}</strong> del proyecto?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="{{ route('admin.projects.unassign-team', [$project, $team]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Desasignar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-people display-1 text-muted"></i>
                                <h5 class="text-muted">No hay equipos asignados</h5>
                                <p class="text-muted">Asigna equipos al proyecto para gestionar el trabajo</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de módulos -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                                        Módulos del Proyecto ({{ $project->modules->count() }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createModuleModal">
                                        <i class="bi bi-plus-circle me-1"></i>Crear Módulo
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($project->modules->count() > 0)
                            <div class="row g-3">
                                @foreach($project->modules as $module)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card border-warning">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="feature-icon warning me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                    <i class="bi bi-grid"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold">{{ $module->name }}</h6>
                                                    @if($module->is_core)
                                                    <span class="badge bg-danger small">CORE</span>
                                                    @endif
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-warning dropdown-toggle" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button class="dropdown-item" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editModuleModal{{ $module->id }}">
                                                                <i class="bi bi-pencil me-2"></i>Editar
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#deleteModuleModal{{ $module->id }}">
                                                                <i class="bi bi-trash me-2"></i>Eliminar
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <p class="text-muted small mb-2">{{ Str::limit($module->description, 60) }}</p>
                                            
                                            <div class="d-flex gap-1 mb-2 flex-wrap">
                                                <span class="badge bg-{{ $module->status === 'ACTIVE' ? 'success' : ($module->status === 'DONE' ? 'primary' : 'secondary') }}">
                                                    {{ $module->status }}
                                                </span>
                                                <span class="badge bg-{{ $module->priority === 'URGENT' ? 'danger' : ($module->priority === 'HIGH' ? 'warning' : 'info') }}">
                                                    {{ $module->priority }}
                                                </span>
                                                <span class="badge bg-light text-dark">
                                                    {{ $module->category }}
                                                </span>
                                            </div>
                                            
                                            <div class="small text-muted">
                                                <div>{{ $module->tasks->count() }} tareas</div>
                                                @if($module->depends_on)
                                                <div>Depende de: {{ $module->dependency->name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para editar módulo -->
                                <div class="modal fade" id="editModuleModal{{ $module->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title">Editar Módulo - {{ $module->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.projects.update-module', [$project, $module]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label for="name{{ $module->id }}" class="form-label">Nombre</label>
                                                            <input type="text" class="form-control" name="name" 
                                                                   value="{{ $module->name }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="status{{ $module->id }}" class="form-label">Estado</label>
                                                            <select class="form-select" name="status" required>
                                                                <option value="PENDING" {{ $module->status === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                                                <option value="ACTIVE" {{ $module->status === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                                                <option value="DONE" {{ $module->status === 'DONE' ? 'selected' : '' }}>Completado</option>
                                                                <option value="PAUSED" {{ $module->status === 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                                                                <option value="CANCELLED" {{ $module->status === 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="description{{ $module->id }}" class="form-label">Descripción</label>
                                                            <textarea class="form-control" name="description" rows="3">{{ $module->description }}</textarea>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="priority{{ $module->id }}" class="form-label">Prioridad</label>
                                                            <select class="form-select" name="priority" required>
                                                                @foreach($priorities as $value => $label)
                                                                <option value="{{ $value }}" {{ $module->priority === $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="category{{ $module->id }}" class="form-label">Categoría</label>
                                                            <select class="form-select" name="category" required>
                                                                @foreach($moduleCategories as $value => $label)
                                                                <option value="{{ $value }}" {{ $module->category === $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="depends_on{{ $module->id }}" class="form-label">Depende de</label>
                                                            <select class="form-select" name="depends_on">
                                                                <option value="">Sin dependencias</option>
                                                                @foreach($project->modules->where('id', '!=', $module->id) as $otherModule)
                                                                <option value="{{ $otherModule->id }}" 
                                                                        {{ $module->depends_on == $otherModule->id ? 'selected' : '' }}>
                                                                    {{ $otherModule->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="is_core" value="1" 
                                                                       {{ $module->is_core ? 'checked' : '' }}>
                                                                <label class="form-check-label">
                                                                    Módulo principal (CORE)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-warning">Actualizar Módulo</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para eliminar módulo -->
                                <div class="modal fade" id="deleteModuleModal{{ $module->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Eliminar Módulo</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que quieres eliminar el módulo <strong>{{ $module->name }}</strong>?</p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    Esta acción eliminará todas las tareas asociadas al módulo.
                                                </div>
                                                @if($module->tasks->count() > 0)
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Este módulo tiene <strong>{{ $module->tasks->count() }} tarea(s)</strong> asociada(s).
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="{{ route('admin.projects.delete-module', [$project, $module]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-grid display-1 text-muted"></i>
                                <h5 class="text-muted">No hay módulos creados</h5>
                                <p class="text-muted">Crea módulos para organizar el trabajo del proyecto</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar equipo -->
<div class="modal fade" id="assignTeamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Asignar Equipo al Proyecto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.projects.assign-team', $project) }}">
                @csrf
                <div class="modal-body">
                    @if($availableTeams->count() > 0)
                    <div class="mb-3">
                        <label for="team_id" class="form-label">Equipo</label>
                        <select class="form-select" name="team_id" required>
                            <option value="">Seleccionar equipo...</option>
                            @foreach($availableTeams as $team)
                            <option value="{{ $team->id }}">
                                {{ $team->name }} 
                                <small>({{ $team->users->where('pivot.is_active', true)->count() }} miembros)</small>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay equipos disponibles para asignar a este proyecto.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @if($availableTeams->count() > 0)
                    <button type="submit" class="btn btn-primary">Asignar Equipo</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear módulo -->
<div class="modal fade" id="createModuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Crear Nuevo Módulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.projects.create-module', $project) }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre del Módulo</label>
                            <input type="text" class="form-control" name="name" required 
                                   placeholder="Ej: Sistema de Autenticación">
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Categoría</label>
                            <select class="form-select" name="category" required>
                                @foreach($moduleCategories as $value => $label)
                                <option value="{{ $value }}" {{ $value === 'DEVELOPMENT' ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" name="description" rows="3" 
                                      placeholder="Describe la funcionalidad y objetivos del módulo..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="priority" class="form-label">Prioridad</label>
                            <select class="form-select" name="priority" required>
                                @foreach($priorities as $value => $label)
                                <option value="{{ $value }}" {{ $value === 'MEDIUM' ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="depends_on" class="form-label">Depende de</label>
                            <select class="form-select" name="depends_on">
                                <option value="">Sin dependencias</option>
                                @foreach($project->modules as $existingModule)
                                <option value="{{ $existingModule->id }}">
                                    {{ $existingModule->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_core" value="1">
                                <label class="form-check-label">
                                    Módulo principal (CORE)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Crear Módulo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast notifications -->
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

@if(session('error'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header bg-danger text-white">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header bg-warning text-dark">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong class="me-auto">Errores de Validación</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide toasts after 5 seconds
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(function(toast) {
        setTimeout(function() {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });
});
</script>
@endpush
@endsection