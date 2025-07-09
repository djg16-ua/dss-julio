@extends('layouts.app')

@section('title', 'Editar Equipo - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-warning">
                        <i class="bi bi-people-gear me-3"></i>Editar Equipo
                    </h1>
                    <p class="lead text-muted">
                        Gestiona la información y miembros del equipo <strong>{{ $team->name }}</strong>
                        @if($team->is_general)
                        <span class="badge bg-info ms-2">Equipo General</span>
                        @else
                        <span class="badge bg-warning ms-2">Personalizado</span>
                        @endif
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.teams') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Equipos
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            <!-- Información básica del equipo -->
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información del Equipo
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(!$team->is_general)
                            <form method="POST" action="{{ route('admin.teams.update', $team) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-bold">Nombre del Equipo</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $team->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="project_info" class="form-label fw-bold">Proyecto</label>
                                        <input type="text" class="form-control"
                                            value="{{ $team->project ? $team->project->title : 'Sin proyecto asignado' }}" disabled>
                                        <div class="form-text">
                                            @if($team->project)
                                            Estado: <span class="badge bg-{{ $team->project->status === 'ACTIVE' ? 'success' : 'secondary' }}">{{ $team->project->status }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">Descripción</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="3"
                                            placeholder="Describe el propósito y objetivos del equipo...">{{ old('description', $team->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="bi bi-check-lg me-2"></i>Actualizar Información
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @else
                            <div class="alert alert-info">
                                <i class="bi bi-shield-check me-2"></i>
                                <strong>Equipo General Protegido:</strong> Este es el equipo general del proyecto y no se puede modificar su información básica.
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nombre del Equipo</label>
                                    <input type="text" class="form-control" value="{{ $team->name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Proyecto</label>
                                    <input type="text" class="form-control"
                                        value="{{ $team->project ? $team->project->title : 'Sin proyecto asignado' }}" disabled>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control" rows="3" disabled>{{ $team->description ?: 'Equipo general del proyecto - incluye todos los miembros' }}</textarea>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Estadísticas del equipo -->
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
                                        <div class="fw-bold h4 text-primary">{{ $team->users ? $team->users->count() : 0 }}</div>
                                        <small class="text-muted">Miembros Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-success">{{ $team->users ? $team->users->count() : 0 }}</div>
                                        <small class="text-muted">Miembros</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-warning">{{ $team->project ? 1 : 0 }}</div>
                                        <small class="text-muted">Proyecto</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-info">{{ $team->modules ? $team->modules->count() : 0 }}</div>
                                        <small class="text-muted">Módulos</small>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="small text-muted">
                                <div><strong>ID Equipo:</strong> {{ $team->id }}</div>
                                <div><strong>Tipo:</strong> {{ $team->is_general ? 'General' : 'Personalizado' }}</div>
                                @if($team->project)
                                <div><strong>Proyecto:</strong> {{ $team->project->title }}</div>
                                @endif
                                <div><strong>Creado:</strong> {{ $team->created_at->format('d/m/Y H:i') }}</div>
                                <div><strong>Actualizado:</strong> {{ $team->updated_at->format('d/m/Y H:i') }}</div>
                                <div><strong>Días activo:</strong> {{ $team->created_at->diffInDays() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de miembros -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-people-fill me-2"></i>
                                        Miembros del Equipo ({{ $team->users ? $team->users->count() : 0 }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    @if($availableUsers && $availableUsers->count() > 0)
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                        <i class="bi bi-person-plus me-1"></i>Agregar Miembro
                                    </button>
                                    @else
                                    <span class="text-light small">No hay usuarios disponibles</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($team->users && $team->users->count() > 0)
                            <div class="row g-3">
                                @foreach($team->users as $user)
                                <div class="col-lg-6">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="feature-icon primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold">{{ $user->name }}</h6>
                                                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                                                    <div class="d-flex gap-2 mb-2">
                                                        @if(isset($user->pivot->role))
                                                        <span class="badge bg-primary">{{ $user->pivot->role }}</span>
                                                        @endif
                                                        <span class="badge bg-{{ $user->role === 'ADMIN' ? 'danger' : 'light text-dark' }}">
                                                            {{ $user->role }}
                                                        </span>
                                                    </div>
                                                    @if(isset($user->pivot->joined_at))
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Unido: {{ \Carbon\Carbon::parse($user->pivot->joined_at)->format('d/m/Y') }}
                                                    </small>
                                                    @endif
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button class="dropdown-item"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#updateRoleModal{{ $user->id }}">
                                                                <i class="bi bi-person-badge me-2"></i>Cambiar Rol
                                                            </button>
                                                        </li>
                                                        @if(!$team->is_general)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#removeMemberModal{{ $user->id }}">
                                                                <i class="bi bi-person-dash me-2"></i>Remover del Equipo
                                                            </button>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para cambiar rol -->
                                <div class="modal fade" id="updateRoleModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Cambiar Rol - {{ $user->name }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.teams.update-member-role', [$team, $user]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="role{{ $user->id }}" class="form-label">Nuevo Rol</label>
                                                        <select class="form-select" name="role" required>
                                                            @if(isset($teamRoles))
                                                            @foreach($teamRoles as $value => $label)
                                                            <option value="{{ $value }}" {{ (isset($user->pivot->role) && $user->pivot->role === $value) ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                            @endforeach
                                                            @else
                                                            @php
                                                            $defaultRoles = [
                                                            'LEAD' => 'Líder de Equipo',
                                                            'SENIOR_DEV' => 'Desarrollador Senior',
                                                            'DEVELOPER' => 'Desarrollador',
                                                            'JUNIOR_DEV' => 'Desarrollador Junior',
                                                            'DESIGNER' => 'Diseñador',
                                                            'TESTER' => 'Tester',
                                                            'ANALYST' => 'Analista',
                                                            'OBSERVER' => 'Observador'
                                                            ];
                                                            @endphp
                                                            @foreach($defaultRoles as $value => $label)
                                                            <option value="{{ $value }}" {{ (isset($user->pivot->role) && $user->pivot->role === $value) ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Actualizar Rol</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para remover miembro (solo para equipos personalizados) -->
                                @if(!$team->is_general)
                                <div class="modal fade" id="removeMemberModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Remover Miembro</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que quieres remover a <strong>{{ $user->name }}</strong> del equipo?</p>
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    El usuario será marcado como inactivo en lugar de ser eliminado completamente.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="{{ route('admin.users.remove-from-team', [$user, $team]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Remover</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-people display-1 text-muted"></i>
                                <h5 class="text-muted">No hay miembros en el equipo</h5>
                                <p class="text-muted">Agrega miembros al equipo para comenzar a trabajar</p>
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
                        <div class="card-header bg-success text-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                                        Módulos Asignados ({{ $team->modules ? $team->modules->count() : 0 }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    @if(isset($availableModules) && $availableModules->count() > 0)
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#assignModuleModal">
                                        <i class="bi bi-plus-circle me-1"></i>Asignar Módulo
                                    </button>
                                    @else
                                    <span class="text-light small">No hay módulos disponibles</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($team->modules && $team->modules->count() > 0)
                            <div class="row g-3">
                                @foreach($team->modules as $module)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="feature-icon success me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                    <i class="bi bi-{{ $module->category === 'DEVELOPMENT' ? 'code' : ($module->category === 'DESIGN' ? 'palette' : 'gear') }}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold">{{ $module->name }}</h6>
                                                    @if($module->is_core)
                                                    <span class="badge bg-danger small">CORE</span>
                                                    @endif
                                                    <p class="text-muted small mb-2">{{ Str::limit($module->description, 60) }}</p>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#unassignModuleModal{{ $module->id }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>

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
                                                @if($module->project)
                                                <div><strong>Proyecto:</strong> {{ $module->project->title }}</div>
                                                @endif
                                                @if($module->tasks)
                                                <div><strong>Tareas:</strong> {{ $module->tasks->count() }} total</div>
                                                <div><strong>Completadas:</strong> {{ $module->tasks->where('status', 'DONE')->count() }}</div>
                                                @endif
                                                @if($module->depends_on && $module->dependency)
                                                <div><strong>Depende de:</strong> {{ $module->dependency->name }}</div>
                                                @endif
                                                @if(isset($module->pivot->assigned_at))
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Asignado: {{ \Carbon\Carbon::parse($module->pivot->assigned_at)->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para desasignar módulo -->
                                <div class="modal fade" id="unassignModuleModal{{ $module->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Desasignar Módulo</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que quieres desasignar el módulo <strong>{{ $module->name }}</strong> del equipo?</p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    El equipo ya no tendrá acceso a las tareas de este módulo.
                                                </div>
                                                @if($module->tasks && $module->tasks->count() > 0)
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Este módulo tiene <strong>{{ $module->tasks->count() }} tarea(s)</strong> asociada(s).
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="{{ route('admin.teams.unassign-module', [$team, $module]) }}" class="d-inline">
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
                                <i class="bi bi-grid display-1 text-muted"></i>
                                <h5 class="text-muted">No hay módulos asignados</h5>
                                <p class="text-muted">Asigna módulos al equipo para gestionar el trabajo específico</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal para agregar miembro -->
@if(isset($availableUsers) && $availableUsers->count() > 0)
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Agregar Miembro al Equipo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.teams.add-member', $team) }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="user_id" class="form-label">Usuario</label>
                            <select class="form-select" name="user_id" required>
                                <option value="">Seleccionar usuario...</option>
                                @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="role" class="form-label">Rol en el equipo</label>
                            <select class="form-select" name="role" required>
                                @if(isset($teamRoles))
                                @foreach($teamRoles as $value => $label)
                                <option value="{{ $value }}" {{ $value === 'DEVELOPER' ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                                @else
                                @php
                                $defaultRoles = [
                                'LEAD' => 'Líder de Equipo',
                                'SENIOR_DEV' => 'Desarrollador Senior',
                                'DEVELOPER' => 'Desarrollador',
                                'JUNIOR_DEV' => 'Desarrollador Junior',
                                'DESIGNER' => 'Diseñador',
                                'TESTER' => 'Tester',
                                'ANALYST' => 'Analista',
                                'OBSERVER' => 'Observador'
                                ];
                                @endphp
                                @foreach($defaultRoles as $value => $label)
                                <option value="{{ $value }}" {{ $value === 'DEVELOPER' ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar Miembro</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal para asignar módulo -->
@if(isset($availableModules) && $availableModules->count() > 0)
<div class="modal fade" id="assignModuleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Asignar Módulo al Equipo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.teams.assign-module', $team) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="module_id" class="form-label">Módulo</label>
                        <select class="form-select" name="module_id" required>
                            <option value="">Seleccionar módulo...</option>
                            @foreach($availableModules as $module)
                            <option value="{{ $module->id }}">
                                {{ $module->name }}
                                @if($module->project)
                                <small>({{ $module->project->title }} - {{ $module->category }})</small>
                                @endif
                                @if($module->is_core) - CORE @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Solo se muestran módulos que no están asignados a este equipo.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Asignar Módulo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

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