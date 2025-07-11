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
                    @if(isset($module))
                        <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-outline-success me-2">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Módulo
                        </a>
                    @endif
                    <a href="{{ route('admin.tasks') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Tareas
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            <!-- Información del módulo preseleccionado -->
            @if(isset($module))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                            </div>
                            <div class="col">
                                <div class="fw-bold">Creando tarea para el módulo: {{ $module->name }}</div>
                                <small>
                                    Proyecto: {{ $module->project->title ?? 'Sin proyecto' }} | 
                                    Categoría: {{ $module->category }} | 
                                    Prioridad: {{ $module->priority }}
                                    @if($module->is_core)
                                        | <span class="badge bg-danger">CORE</span>
                                    @endif
                                </small>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Volver al Módulo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

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
                                            <i class="bi bi-grid me-1"></i>Módulo
                                        </label>
                                        <select class="form-select @error('module_id') is-invalid @enderror" id="module_id" name="module_id" required>
                                            <option value="">Seleccionar módulo...</option>
                                            @foreach($modules as $moduleOption)
                                            <option value="{{ $moduleOption->id }}" 
                                                    {{ (old('module_id', $module->id ?? '') == $moduleOption->id) ? 'selected' : '' }}>
                                                {{ $moduleOption->name }} 
                                                <small>({{ $moduleOption->project->title ?? 'Sin proyecto' }})</small>
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('module_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            @if(isset($module))
                                                <strong class="text-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Módulo preseleccionado: {{ $module->name }}
                                                </strong>
                                            @else
                                                Selecciona el módulo al que pertenecerá esta tarea
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="assigned_users" class="form-label fw-bold">
                                            <i class="bi bi-people me-1"></i>Asignar a (Opcional)
                                        </label>
                                        <select class="form-select @error('assigned_users') is-invalid @enderror" id="assigned_users" name="assigned_users[]" multiple>
                                            @foreach($availableUsers as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('assigned_users', [])) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_users')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            @if(isset($module))
                                                Usuarios disponibles del proyecto: <strong>{{ $module->project->title }}</strong>
                                            @else
                                                Usuarios responsables de completar la tarea (mantén Ctrl para seleccionar múltiples)
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label fw-bold">
                                            <i class="bi bi-flag me-1"></i>Estado Inicial
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="PENDING" {{ old('status', 'PENDING') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="ACTIVE" {{ old('status') === 'ACTIVE' ? 'selected' : '' }}>Activa</option>
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
                                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date" value="{{ old('end_date') }}"
                                            min="{{ now()->format('Y-m-d\TH:i') }}">
                                        @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Fecha y hora límite para completar la tarea</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="depends_on" class="form-label fw-bold">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Depende de (Opcional)
                                        </label>
                                        <select class="form-select @error('depends_on') is-invalid @enderror" id="depends_on" name="depends_on">
                                            <option value="">Sin dependencias</option>
                                            @if($tasks && count($tasks) > 0)
                                                @foreach($tasks as $task)
                                                <option value="{{ $task->id }}" {{ old('depends_on') == $task->id ? 'selected' : '' }}>
                                                    {{ Str::limit($task->title, 40) }}
                                                    @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                                                    <small>({{ $task->assignedUsers->pluck('name')->join(', ') }})</small>
                                                    @endif
                                                </option>
                                                @endforeach
                                            @endif
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
                                            @if(isset($module))
                                                <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-success">
                                                    <i class="bi bi-arrow-left me-2"></i>Volver al Módulo
                                                </a>
                                            @else
                                                <a href="{{ route('admin.tasks') }}" class="btn btn-secondary">
                                                    <i class="bi bi-x-lg me-2"></i>Cancelar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar con información completa -->
                <div class="col-lg-4">
                    @if(isset($module))
                    <!-- Información del módulo preseleccionado -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-grid-3x3-gap me-2"></i>
                                Información del Módulo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="fw-bold text-success">{{ $module->name }}</h6>
                                <p class="text-muted small mb-2">{{ $module->description ?: 'Sin descripción' }}</p>
                            </div>
                            <div class="small">
                                <div><strong>Proyecto:</strong> {{ $module->project->title ?? 'Sin proyecto' }}</div>
                                <div><strong>Categoría:</strong> {{ $module->category }}</div>
                                <div><strong>Prioridad:</strong> 
                                    <span class="badge bg-{{ $module->priority === 'URGENT' ? 'danger' : ($module->priority === 'HIGH' ? 'warning' : ($module->priority === 'MEDIUM' ? 'info' : 'secondary')) }}">
                                        {{ $module->priority }}
                                    </span>
                                </div>
                                @if($module->is_core)
                                <div><strong>Tipo:</strong> <span class="badge bg-danger">CORE</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="card shadow-sm {{ isset($module) ? 'mt-4' : '' }}">
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
                                    <i class="bi bi-2-circle me-2"></i>Asignación Múltiple
                                </h6>
                                <p class="small text-muted mb-0">
                                    Puedes asignar la tarea a múltiples usuarios. Mantén presionado Ctrl para seleccionar varios.
                                </p>
                            </div>

                            @if(isset($module))
                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-3-circle me-2"></i>Usuarios del Proyecto
                                </h6>
                                <p class="small text-muted mb-0">
                                    Solo se muestran usuarios que pertenecen al proyecto <strong>{{ $module->project->title }}</strong>.
                                </p>
                            </div>
                            @else
                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-3-circle me-2"></i>Módulo y Proyecto
                                </h6>
                                <p class="small text-muted mb-0">
                                    Selecciona el módulo al que pertenece la tarea para mejor organización.
                                </p>
                            </div>
                            @endif

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
                                <span class="badge bg-primary">ACTIVE</span>
                                <small class="text-muted">Activa</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-success">DONE</span>
                                <small class="text-muted">Completada</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-warning">PAUSED</span>
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
                try {
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                } catch (e) {
                    console.log('Error hiding toast:', e);
                }
            }, 5000);
        });

        // Validación de fecha límite
        const endDateInput = document.getElementById('end_date');
        if (endDateInput) {
            endDateInput.addEventListener('change', function() {
                const selectedDateTime = new Date(this.value);
                const now = new Date();

                if (selectedDateTime < now) {
                    alert('La fecha límite no puede ser anterior al momento actual.');
                    this.value = '';
                }
            });
        }

        // Hacer el select múltiple más user-friendly
        const assignedUsersSelect = document.getElementById('assigned_users');
        if (assignedUsersSelect) {
            assignedUsersSelect.addEventListener('focus', function() {
                if (!this.hasAttribute('data-tooltip-shown')) {
                    // Mostrar tooltip solo la primera vez
                    this.title = 'Mantén Ctrl presionado para seleccionar múltiples usuarios';
                    this.setAttribute('data-tooltip-shown', 'true');
                }
            });
        }

        // Prevenir envío múltiple del formulario
        const form = document.querySelector('form');
        if (form) {
            let isSubmitting = false;
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }
                
                isSubmitting = true;
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando...';
                }
                
                // Re-habilitar después de 10 segundos para evitar bloqueo permanente
                setTimeout(() => {
                    isSubmitting = false;
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Crear Tarea';
                    }
                }, 10000);
            });
        }
    });
</script>
@endpush
@endsection