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
                        <i class="bi bi-pencil-square me-3"></i>Editar Equipo
                    </h1>
                    <p class="lead text-muted">
                        Gestiona la información y miembros del equipo <strong>{{ $team->name }}</strong>
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
                                Información Básica
                            </h5>
                        </div>
                        <div class="card-body">
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
                                        <label class="form-label fw-bold">ID del Equipo</label>
                                        <input type="text" class="form-control bg-light" value="{{ $team->id }}" readonly>
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
                                        <div class="fw-bold h4 text-primary">{{ $team->users->where('pivot.is_active', true)->count() }}</div>
                                        <small class="text-muted">Miembros Activos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-success">{{ $team->projects->count() }}</div>
                                        <small class="text-muted">Proyectos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-warning">{{ $team->created_at->diffInDays() }}</div>
                                        <small class="text-muted">Días Activo</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-info">{{ $team->projects->sum(function($p) { return $p->modules->count(); }) }}</div>
                                        <small class="text-muted">Módulos</small>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="small text-muted">
                                <div><strong>Creado:</strong> {{ $team->created_at->format('d/m/Y H:i') }}</div>
                                <div><strong>Actualizado:</strong> {{ $team->updated_at->format('d/m/Y H:i') }}</div>
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
                                        Gestión de Miembros ({{ $team->users->where('pivot.is_active', true)->count() }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                        <i class="bi bi-person-plus me-1"></i>Agregar Miembro
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($team->users->where('pivot.is_active', true)->count() > 0)
                            <div class="row g-3">
                                @foreach($team->users->where('pivot.is_active', true) as $user)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="feature-icon primary me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <div class="text-muted small">{{ $user->email }}</div>
                                                    <div class="mt-2">
                                                        <span class="badge bg-primary">{{ $user->pivot->role }}</span>
                                                        @if($user->pivot->is_active)
                                                        <span class="badge bg-success">Activo</span>
                                                        @else
                                                        <span class="badge bg-secondary">Inactivo</span>
                                                        @endif
                                                    </div>
                                                    <div class="small text-muted mt-1">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Se unió: {{ \Carbon\Carbon::parse($user->pivot->joined_at)->format('d/m/Y') }}
                                                    </div>
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
                                                                <i class="bi bi-pencil me-2"></i>Cambiar Rol
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#removeMemberModal{{ $user->id }}">
                                                                <i class="bi bi-person-dash me-2"></i>Remover
                                                            </button>
                                                        </li>
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
                                                        <label for="role" class="form-label">Nuevo Rol</label>
                                                        <select class="form-select" name="role" required>
                                                            @foreach($teamRoles as $value => $label)
                                                            <option value="{{ $value }}" 
                                                                    {{ $user->pivot->role === $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                            @endforeach
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

                                <!-- Modal para remover miembro -->
                                <div class="modal fade" id="removeMemberModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Remover Miembro</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que quieres remover a <strong>{{ $user->name }}</strong> del equipo?</p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    El usuario será marcado como inactivo pero se mantendrá el historial.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="{{ route('admin.teams.remove-member', [$team, $user]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Remover</button>
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
                                <h5 class="text-muted">No hay miembros activos</h5>
                                <p class="text-muted">Agrega miembros al equipo para comenzar</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de proyectos -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-kanban-fill me-2"></i>
                                        Proyectos Asignados ({{ $team->projects->count() }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#assignProjectModal">
                                        <i class="bi bi-plus-circle me-1"></i>Asignar Proyecto
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($team->projects->count() > 0)
                            <div class="row g-3">
                                @foreach($team->projects as $project)
                                <div class="col-lg-6">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="feature-icon success me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                    <i class="bi bi-kanban"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold">{{ $project->title }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($project->description, 100) }}</p>
                                                    <div class="d-flex gap-2 mb-2">
                                                        <span class="badge bg-{{ $project->status === 'ACTIVE' ? 'success' : ($project->status === 'DONE' ? 'primary' : 'secondary') }}">
                                                            {{ $project->status }}
                                                        </span>
                                                        <span class="badge bg-light text-dark">
                                                            {{ $project->modules->count() }} módulos
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Asignado: {{ \Carbon\Carbon::parse($project->pivot->assigned_at)->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#unassignProjectModal{{ $project->id }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para desasignar proyecto -->
                                <div class="modal fade" id="unassignProjectModal{{ $project->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Desasignar Proyecto</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que quieres desasignar el proyecto <strong>{{ $project->title }}</strong> del equipo?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="{{ route('admin.teams.unassign-project', [$team, $project]) }}" class="d-inline">
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
                                <i class="bi bi-kanban display-1 text-muted"></i>
                                <h5 class="text-muted">No hay proyectos asignados</h5>
                                <p class="text-muted">Asigna proyectos al equipo para gestionar el trabajo</p>
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
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuario</label>
                        <select class="form-select" name="user_id" required>
                            <option value="">Seleccionar usuario...</option>
                            @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rol en el Equipo</label>
                        <select class="form-select" name="role" required>
                            @foreach($teamRoles as $value => $label)
                            <option value="{{ $value }}" {{ $value === 'DEVELOPER' ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
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

<!-- Modal para asignar proyecto -->
<div class="modal fade" id="assignProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Asignar Proyecto al Equipo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.teams.assign-project', $team) }}">
                @csrf
                <div class="modal-body">
                    @if($availableProjects->count() > 0)
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Proyecto</label>
                        <select class="form-select" name="project_id" required>
                            <option value="">Seleccionar proyecto...</option>
                            @foreach($availableProjects as $project)
                            <option value="{{ $project->id }}">
                                {{ $project->title }} 
                                <small>({{ $project->status }})</small>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay proyectos disponibles para asignar a este equipo.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @if($availableProjects->count() > 0)
                    <button type="submit" class="btn btn-success">Asignar Proyecto</button>
                    @endif
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