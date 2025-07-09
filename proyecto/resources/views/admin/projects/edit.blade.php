@extends('layouts.app')

@section('title', 'Editar Proyecto - Admin')

@section('content')
{{-- Código PHP eliminado ya que no se necesita asignar equipos --}}

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
                                            @php
                                            $projectStatuses = [
                                            'PENDING' => 'Pendiente',
                                            'ACTIVE' => 'Activo',
                                            'DONE' => 'Completado',
                                            'PAUSED' => 'Pausado',
                                            'CANCELLED' => 'Cancelado'
                                            ];
                                            @endphp
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
                                                        Asignado: {{ isset($team->pivot->assigned_at) ? \Carbon\Carbon::parse($team->pivot->assigned_at)->format('d/m/Y') : 'N/A' }}
                                                    </small>
                                                </div>
                                                @if(!$team->is_general)
                                                <button class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#unassignTeamModal{{ $team->id }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                                @else
                                                <span class="badge bg-info">General</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para desasignar equipo (solo para equipos no generales) -->
                                @if(!$team->is_general)
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
                                                <form method="POST" action="{{ route('admin.teams.unassign-project', [$team, $project]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Desasignar</button>
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
                                    <a href="{{ route('admin.modules.create') }}" class="btn btn-dark btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i>Crear Módulo
                                    </a>
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
                                                <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
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
                                                @if($module->depends_on && $module->dependency)
                                                <div>Depende de: {{ $module->dependency->name }}</div>
                                                @endif
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
                                <p class="text-muted">Asigna módulos al proyecto para organizar el trabajo</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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