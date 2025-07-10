@extends('layouts.app')

@section('title', 'Editar Módulo - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-warning">
                        <i class="bi bi-pencil-square me-3"></i>Editar Módulo
                    </h1>
                    <p class="lead text-muted">
                        Gestiona la información y configuración del módulo <strong>{{ $module->name }}</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.modules') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Módulos
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            <!-- Información básica del módulo -->
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información del Módulo
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.modules.update', $module) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-bold">Nombre del Módulo</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $module->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="project_id" class="form-label fw-bold">Proyecto</label>
                                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                                            @if($projects->count() > 0)
                                                @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id', $module->project_id) == $project->id ? 'selected' : '' }}>
                                                    {{ $project->title }} ({{ $project->status }})
                                                </option>
                                                @endforeach
                                            @else
                                                <option value="{{ $module->project_id }}" selected>{{ $module->project->title ?? 'Proyecto actual' }}</option>
                                            @endif
                                        </select>
                                        @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">Descripción</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="3"
                                            placeholder="Describe la funcionalidad y objetivos del módulo...">{{ old('description', $module->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="category" class="form-label fw-bold">Categoría</label>
                                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                            @foreach($moduleCategories as $value => $label)
                                            <option value="{{ $value }}" {{ old('category', $module->category) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="priority" class="form-label fw-bold">Prioridad</label>
                                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                            @foreach($priorities as $value => $label)
                                            <option value="{{ $value }}" {{ old('priority', $module->priority) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="status" class="form-label fw-bold">Estado</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="PENDING" {{ old('status', $module->status) === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="ACTIVE" {{ old('status', $module->status) === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                            <option value="DONE" {{ old('status', $module->status) === 'DONE' ? 'selected' : '' }}>Completado</option>
                                            <option value="PAUSED" {{ old('status', $module->status) === 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                                            <option value="CANCELLED" {{ old('status', $module->status) === 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="depends_on" class="form-label fw-bold">Depende de</label>
                                        <select class="form-select @error('depends_on') is-invalid @enderror" id="depends_on" name="depends_on">
                                            <option value="">Sin dependencias</option>
                                            @foreach($projectModules as $otherModule)
                                            <option value="{{ $otherModule->id }}" {{ old('depends_on', $module->depends_on) == $otherModule->id ? 'selected' : '' }}>
                                                {{ $otherModule->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('depends_on')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_core" value="1"
                                                id="is_core" {{ old('is_core', $module->is_core) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-danger" for="is_core">
                                                Módulo Principal (CORE)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="bi bi-check-lg me-2"></i>Actualizar Módulo
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas del módulo -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $totalTasks = $module->tasks->count();
                                $completedTasks = $module->tasks->where('status', 'DONE')->count();
                                $totalTeams = $module->teams->count();
                                $totalDependents = $module->dependents->count();
                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            @endphp
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-primary">{{ $totalTasks }}</div>
                                        <small class="text-muted">Tareas Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-success">{{ $completedTasks }}</div>
                                        <small class="text-muted">Completadas</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-warning">{{ $totalTeams }}</div>
                                        <small class="text-muted">Equipos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-info">{{ $totalDependents }}</div>
                                        <small class="text-muted">Dependientes</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-bold">Progreso</small>
                                    <small class="fw-bold">{{ $progress }}%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            <hr>

                            <div class="small text-muted">
                                <div><strong>Proyecto:</strong> {{ $module->project->title ?? 'N/A' }}</div>
                                <div><strong>Creado:</strong> {{ $module->created_at->format('d/m/Y H:i') }}</div>
                                <div><strong>Actualizado:</strong> {{ $module->updated_at->format('d/m/Y H:i') }}</div>
                                <div><strong>ID:</strong> {{ $module->id }}</div>
                                @if($module->depends_on && $module->dependency)
                                <div><strong>Depende de:</strong> {{ $module->dependency->name }}</div>
                                @endif
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
                                        Equipos Asignados ({{ $totalTeams }})
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
                            @if($totalTeams > 0)
                            <div class="row g-3">
                                @foreach($module->teams as $team)
                                <div class="col-lg-6">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3" style="width: 40px; height: 40px; font-size: 1rem; background: var(--bs-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-people-fill"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold">{{ $team->name }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($team->description ?? '', 80) }}</p>
                                                    <div class="d-flex gap-2 mb-2">
                                                        <span class="badge bg-light text-dark">
                                                            {{ $team->active_users_count ?? 0 }} miembros
                                                        </span>
                                                        @if($team->is_general)
                                                        <span class="badge bg-warning text-dark">General</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Asignado: {{ $team->pivot ? \Carbon\Carbon::parse($team->pivot->assigned_at)->format('d/m/Y') : 'N/A' }}
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
                                                <p>¿Estás seguro de que quieres desasignar el equipo <strong>{{ $team->name }}</strong> del módulo?</p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    El equipo ya no tendrá acceso a las tareas de este módulo.
                                                </div>
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
                                <i class="bi bi-people display-1 text-muted"></i>
                                <h5 class="text-muted">No hay equipos asignados</h5>
                                <p class="text-muted">Asigna equipos al módulo para gestionar el trabajo</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTeamModal">
                                    <i class="bi bi-plus-circle me-2"></i>Asignar Primer Equipo
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tareas del módulo -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-check-square me-2"></i>
                                        Tareas del Módulo ({{ $totalTasks }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.tasks.create', $module) }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-plus-lg me-1"></i>Nueva Tarea
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($totalTasks > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tarea</th>
                                            <th>Estado</th>
                                            <th>Prioridad</th>
                                            <th>Asignado a</th>
                                            <th>Fecha límite</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($module->tasks as $task)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ Str::limit($task->title, 40) }}</div>
                                                <small class="text-muted">{{ Str::limit($task->description ?? '', 60) }}</small>
                                            </td>
                                            <td>
                                                @php
                                                $statusColors = [
                                                    'PENDING' => 'secondary',
                                                    'ACTIVE' => 'warning',
                                                    'DONE' => 'success',
                                                    'PAUSED' => 'info',
                                                    'CANCELLED' => 'danger'
                                                ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$task->status] ?? 'secondary' }}">
                                                    {{ $task->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                $priorityColors = [
                                                    'LOW' => 'secondary',
                                                    'MEDIUM' => 'info',
                                                    'HIGH' => 'warning',
                                                    'URGENT' => 'danger'
                                                ];
                                                @endphp
                                                <span class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }}">
                                                    {{ $task->priority }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                                                    @foreach($task->assignedUsers as $user)
                                                        <span class="badge bg-primary me-1">{{ $user->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No asignado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($task->end_date)
                                                {{ $task->end_date->format('d/m/Y') }}
                                                @if($task->end_date->isPast() && $task->status !== 'DONE')
                                                <span class="badge bg-danger ms-1">Vencida</span>
                                                @endif
                                                @else
                                                <span class="text-muted">Sin fecha</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-check-square display-1 text-muted"></i>
                                <h5 class="text-muted">No hay tareas creadas</h5>
                                <p class="text-muted">Crea tareas para este módulo para organizar el trabajo</p>
                                <a href="{{ route('admin.tasks.create', $module) }}" class="btn btn-success">
                                    <i class="bi bi-plus-lg me-2"></i>Crear Primera Tarea
                                </a>
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
                <h5 class="modal-title">Asignar Equipo al Módulo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" action="#" id="assignTeamForm">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <div class="modal-body">
                    @if($availableTeams->count() > 0)
                    <div class="mb-3">
                        <label for="team_select" class="form-label">Equipo</label>
                        <select class="form-select" name="team_select" id="team_select" required onchange="updateFormAction(this)">
                            <option value="">Seleccionar equipo...</option>
                            @foreach($availableTeams as $team)
                            <option value="{{ $team->id }}" data-route="{{ route('admin.teams.assign-module', $team) }}">
                                {{ $team->name }}
                                <small>({{ $team->active_users_count ?? 0 }} miembros{{ $team->is_general ? ' - General' : '' }})</small>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Solo se muestran equipos del mismo proyecto que no están ya asignados a este módulo.</small>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>No hay equipos disponibles</strong>
                        <hr>
                        <small>
                            <strong>Posibles causas:</strong><br>
                            • Todos los equipos del proyecto ya están asignados a este módulo<br>
                            • El proyecto no tiene equipos creados<br>
                            • Solo existe el equipo general y ya está asignado
                        </small>
                        <hr>
                        <a href="{{ route('admin.teams.create') }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-plus-circle me-1"></i>Crear Nuevo Equipo
                        </a>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @if($availableTeams->count() > 0)
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Asignar Equipo
                    </button>
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
    function updateFormAction(select) {
        const form = select.closest('form');
        const selectedOption = select.options[select.selectedIndex];
        const route = selectedOption.getAttribute('data-route');
        
        if (route) {
            form.action = route;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide toasts after 5 seconds
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(function(toast) {
            setTimeout(function() {
                try {
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                } catch (e) {
                    console.log('Error hiding toast:', e);
                }
            }, 5000);
        });
    });
</script>
@endpush
@endsection