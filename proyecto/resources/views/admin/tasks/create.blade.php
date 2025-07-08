@extends('layouts.app')

@section('title', 'Crear Tarea - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-dark">
                        <i class="bi bi-plus-square me-3"></i>Crear Nueva Tarea
                    </h1>
                    <p class="lead text-muted">
                        Agrega una nueva tarea al sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.tasks') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Tareas
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            <!-- Formulario principal -->
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información de la Tarea
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.tasks.store') }}">
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label fw-bold">
                                            <i class="bi bi-tag me-1"></i>Título de la Tarea
                                        </label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}" required
                                            placeholder="Ej: Implementar autenticación de usuarios">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Título claro y descriptivo de la tarea</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="priority" class="form-label fw-bold">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Prioridad
                                        </label>
                                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                            @foreach($priorities as $value => $label)
                                            <option value="{{ $value }}" {{ old('priority', 'MEDIUM') === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Nivel de importancia de la tarea</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">
                                            <i class="bi bi-text-paragraph me-1"></i>Descripción
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="4"
                                            placeholder="Describe detalladamente qué se debe hacer, criterios de aceptación, etc...">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Información detallada sobre lo que hay que hacer</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="module_id" class="form-label fw-bold">
                                            <i class="bi bi-grid me-1"></i>Módulo (Opcional)
                                        </label>
                                        <select class="form-select @error('module_id') is-invalid @enderror" id="module_id" name="module_id">
                                            <option value="">Sin módulo específico</option>
                                            @foreach($modules as $module)
                                            <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                                {{ $module->name }}
                                                <small>({{ $module->project->title }})</small>
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('module_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Módulo al que pertenece esta tarea</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="assigned_to" class="form-label fw-bold">
                                            <i class="bi bi-person me-1"></i>Asignar a (Opcional)
                                        </label>
                                        <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                            <option value="">Sin asignar</option>
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Usuario responsable de completar la tarea</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label fw-bold">
                                            <i class="bi bi-flag me-1"></i>Estado Inicial
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="PENDING" {{ old('status', 'PENDING') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="IN_PROGRESS" {{ old('status') === 'IN_PROGRESS' ? 'selected' : '' }}>En Progreso</option>
                                            <option value="DONE" {{ old('status') === 'DONE' ? 'selected' : '' }}>Completada</option>
                                            <option value="PAUSED" {{ old('status') === 'PAUSED' ? 'selected' : '' }}>Pausada</option>
                                            <option value="CANCELLED" {{ old('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Estado inicial de la tarea</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="end_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-event me-1"></i>Fecha Límite (Opcional)
                                        </label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date" value="{{ old('end_date') }}"
                                            min="{{ date('Y-m-d') }}">
                                        @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Fecha límite para completar la tarea</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="depends_on" class="form-label fw-bold">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Depende de (Opcional)
                                        </label>
                                        <select class="form-select @error('depends_on') is-invalid @enderror" id="depends_on" name="depends_on">
                                            <option value="">Sin dependencias</option>
                                            @foreach($tasks as $task)
                                            <option value="{{ $task->id }}" {{ old('depends_on') == $task->id ? 'selected' : '' }}>
                                                {{ Str::limit($task->title, 40) }}
                                                @if($task->assignedUser)
                                                <small>({{ $task->assignedUser->name }})</small>
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('depends_on')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Tarea que debe completarse antes que esta</div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-dark">
                                                <i class="bi bi-check-lg me-2"></i>Crear Tarea
                                            </button>
                                            <a href="{{ route('admin.tasks') }}" class="btn btn-secondary">
                                                <i class="bi bi-x-lg me-2"></i>Cancelar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar con información -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightbulb me-2"></i>
                                Guía de Creación
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-1-circle me-2"></i>Información Básica
                                </h6>
                                <p class="small text-muted mb-0">
                                    Proporciona un título claro y una descripción detallada de lo que se debe hacer.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-2-circle me-2"></i>Asignación
                                </h6>
                                <p class="small text-muted mb-0">
                                    Puedes asignar la tarea a un usuario específico o dejarla sin asignar para asignarla después.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-3-circle me-2"></i>Módulo y Proyecto
                                </h6>
                                <p class="small text-muted mb-0">
                                    Si la tarea pertenece a un módulo específico, selecciónalo para mejor organización.
                                </p>
                            </div>

                            <div>
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-4-circle me-2"></i>Dependencias
                                </h6>
                                <p class="small text-muted mb-0">
                                    Si esta tarea requiere que otra se complete primero, establece la dependencia.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Prioridades disponibles -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-warning text-dark py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Niveles de Prioridad
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($priorities as $value => $label)
                            @php
                            $color = $value === 'URGENT' ? 'danger' : ($value === 'HIGH' ? 'warning' : ($value === 'MEDIUM' ? 'info' : 'secondary'));
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $color }}">{{ $value }}</span>
                                <small class="text-muted">{{ $label }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Estados disponibles -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-secondary text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-flag me-2"></i>
                                Estados de Tarea
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary">PENDING</span>
                                <small class="text-muted">Pendiente</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-warning">IN_PROGRESS</span>
                                <small class="text-muted">En Progreso</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-success">DONE</span>
                                <small class="text-muted">Completada</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-info">PAUSED</span>
                                <small class="text-muted">Pausada</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-danger">CANCELLED</span>
                                <small class="text-muted">Cancelada</small>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas del sistema -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-success text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Estadísticas del Sistema
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-dark">{{ $stats['total_tasks'] ?? 0 }}</div>
                                        <small class="text-muted">Tareas Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-warning">{{ $stats['pending_tasks'] ?? 0 }}</div>
                                        <small class="text-muted">Pendientes</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-success">{{ $stats['completed_tasks'] ?? 0 }}</div>
                                        <small class="text-muted">Completadas</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-danger">{{ $stats['overdue_tasks'] ?? 0 }}</div>
                                        <small class="text-muted">Vencidas</small>
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

        // Validación de fecha límite
        const endDateInput = document.getElementById('end_date');
        if (endDateInput) {
            endDateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDate < today) {
                    alert('La fecha límite no puede ser anterior a hoy.');
                    this.value = '';
                }
            });
        }
    });
</script>
@endpush
@endsection