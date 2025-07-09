@extends('layouts.app')

@section('title', 'Gestionar Tareas - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4 align-items-center">
                <div class="col-lg-6 col-md-8">
                    <h1 class="display-5 fw-bold text-dark mb-2">
                        <i class="bi bi-check-square me-3"></i>Gestión de Tareas
                    </h1>
                    <p class="lead text-muted mb-0">
                        Administra todas las tareas del sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-6 col-md-4">
                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                        </a>
                        <a href="{{ route('admin.tasks.create') }}" class="btn btn-dark">
                            <i class="bi bi-plus-circle me-2"></i>Crear Tarea
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de tareas -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-dark">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3" style="width: 60px; height: 60px; background: #6c757d; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="bi bi-check-square"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_tasks'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Tareas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-warning">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon warning mx-auto mb-3" style="width: 60px; height: 60px; background: #ffc107; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['pending_tasks'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-success">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3" style="width: 60px; height: 60px; background: #198754; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['completed_tasks'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Completadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-danger">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3" style="width: 60px; height: 60px; background: #dc3545; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['overdue_tasks'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Vencidas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.tasks') }}">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Buscar tarea</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            value="{{ request('search') }}" placeholder="Título o descripción...">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Estado</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">Todos</option>
                                            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="ACTIVE" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>Activa</option>
                                            <option value="DONE" {{ request('status') === 'DONE' ? 'selected' : '' }}>Completada</option>
                                            <option value="PAUSED" {{ request('status') === 'PAUSED' ? 'selected' : '' }}>Pausada</option>
                                            <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="priority" class="form-label">Prioridad</label>
                                        <select class="form-select" id="priority" name="priority">
                                            <option value="">Todas</option>
                                            <option value="LOW" {{ request('priority') === 'LOW' ? 'selected' : '' }}>Baja</option>
                                            <option value="MEDIUM" {{ request('priority') === 'MEDIUM' ? 'selected' : '' }}>Media</option>
                                            <option value="HIGH" {{ request('priority') === 'HIGH' ? 'selected' : '' }}>Alta</option>
                                            <option value="URGENT" {{ request('priority') === 'URGENT' ? 'selected' : '' }}>Urgente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="assigned" class="form-label">Asignación</label>
                                        <select class="form-select" id="assigned" name="assigned">
                                            <option value="">Todas</option>
                                            <option value="assigned" {{ request('assigned') === 'assigned' ? 'selected' : '' }}>Asignadas</option>
                                            <option value="unassigned" {{ request('assigned') === 'unassigned' ? 'selected' : '' }}>Sin asignar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="overdue" class="form-label">Vencimiento</label>
                                        <select class="form-select" id="overdue" name="overdue">
                                            <option value="">Todas</option>
                                            <option value="1" {{ request('overdue') === '1' ? 'selected' : '' }}>Vencidas</option>
                                            <option value="0" {{ request('overdue') === '0' ? 'selected' : '' }}>Vigentes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-dark w-100">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de tareas -->
            @if($tasks->count() > 0)
            @foreach($tasks as $task)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Información principal de la tarea -->
                        <div class="col-lg-3 col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon {{ $task->status === 'DONE' ? 'success' : ($task->status === 'ACTIVE' ? 'warning' : 'secondary') }} me-3" style="width: 50px; height: 50px; font-size: 1.2rem; background: {{ $task->status === 'DONE' ? '#198754' : ($task->status === 'ACTIVE' ? '#ffc107' : '#6c757d') }}; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-{{ $task->status === 'DONE' ? 'check-circle' : ($task->status === 'ACTIVE' ? 'clock' : 'circle') }}"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $task->title }}</h5>
                                    <p class="mb-1 text-muted small">{{ Str::limit($task->description ?? '', 80) }}</p>
                                    <div class="d-flex gap-1 mb-1">
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
                                        @if($task->end_date && $task->end_date->isPast() && $task->status !== 'DONE')
                                        <span class="badge bg-danger">VENCIDA</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">ID: {{ $task->id }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Asignación y módulo -->
                        <div class="col-lg-2 col-md-3">
                            <div class="small">
                                <div class="mb-2">
                                    <strong>Asignado a:</strong><br>
                                    @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                                        @foreach($task->assignedUsers->take(2) as $user)
                                            <span class="badge bg-primary me-1 mb-1">{{ $user->name }}</span>
                                        @endforeach
                                        @if($task->assignedUsers->count() > 2)
                                            <span class="badge bg-info">+{{ $task->assignedUsers->count() - 2 }} más</span>
                                        @endif
                                    @elseif(isset($task->assignedUser) && $task->assignedUser)
                                        {{-- Compatibilidad con estructura antigua --}}
                                        <span class="badge bg-primary">{{ $task->assignedUser->name }}</span>
                                    @else
                                        <span class="text-muted">Sin asignar</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <strong>Módulo:</strong><br>
                                    @if($task->module)
                                        <a href="{{ route('admin.modules.edit', $task->module) }}" class="text-decoration-none">
                                            {{ Str::limit($task->module->name, 20) }}
                                        </a>
                                    @else
                                        <span class="text-muted">Sin módulo</span>
                                    @endif
                                </div>
                                @if($task->dependency)
                                <div>
                                    <strong>Depende de:</strong><br>
                                    <small class="text-info">{{ Str::limit($task->dependency->title, 20) }}</small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estado y progreso -->
                        <div class="col-lg-2 col-md-3">
                            <div class="mb-2">
                                <form method="POST" action="{{ route('admin.tasks.update-status', $task) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="PENDING" {{ $task->status === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="ACTIVE" {{ $task->status === 'ACTIVE' ? 'selected' : '' }}>Activa</option>
                                        <option value="DONE" {{ $task->status === 'DONE' ? 'selected' : '' }}>Completada</option>
                                        <option value="PAUSED" {{ $task->status === 'PAUSED' ? 'selected' : '' }}>Pausada</option>
                                        <option value="CANCELLED" {{ $task->status === 'CANCELLED' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                </form>
                            </div>
                            <div class="small text-center">
                                @if($task->status === 'DONE')
                                    <span class="badge bg-success">100% Completada</span>
                                @elseif($task->status === 'ACTIVE')
                                    <span class="badge bg-primary">En Progreso</span>
                                @else
                                    <span class="badge bg-secondary">{{ $task->status }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="col-lg-2 col-md-4">
                            <div class="small">
                                <div class="mb-1">
                                    <strong>Creada:</strong><br>
                                    <span class="text-muted">{{ $task->created_at->format('d/m/Y') }}</span><br>
                                    <span class="text-muted">{{ $task->created_at->diffForHumans() }}</span>
                                </div>
                                @if($task->end_date)
                                <div class="mb-1">
                                    <strong>Vence:</strong><br>
                                    <span class="text-{{ $task->end_date->isPast() && $task->status !== 'DONE' ? 'danger' : 'muted' }}">
                                        @if($task->end_date instanceof \Carbon\Carbon)
                                            {{ $task->end_date->format('d/m/Y H:i') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($task->end_date)->format('d/m/Y H:i') }}
                                        @endif
                                    </span>
                                </div>
                                @endif
                                @if($task->completed_at)
                                <div>
                                    <strong>Completada:</strong><br>
                                    <span class="text-success">
                                        @if($task->completed_at instanceof \Carbon\Carbon)
                                            {{ $task->completed_at->format('d/m/Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($task->completed_at)->format('d/m/Y') }}
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estadísticas -->
                        <div class="col-lg-2 col-md-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-primary">{{ $task->comments ? $task->comments->count() : 0 }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-chat me-1"></i>Comentarios
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-info">{{ $task->dependents ? $task->dependents->count() : 0 }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-arrow-right me-1"></i>Dependientes
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small text-muted text-center">
                                <i class="bi bi-person me-1"></i>
                                Por: {{ $task->creator->name ?? 'Sistema' }}
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="col-lg-1 col-md-2 text-end">
                            <div class="btn-group-vertical d-grid gap-2" role="group">
                                <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-outline-dark btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteTaskModal{{ $task->id }}">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación de eliminación -->
            <div class="modal fade" id="deleteTaskModal{{ $task->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Confirmar Eliminación
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres eliminar la tarea <strong>{{ $task->title }}</strong>?</p>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Esta acción es irreversible.</strong> Se eliminarán todos los comentarios asociados.
                            </div>
                            @if($task->comments && $task->comments->count() > 0)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Esta tarea tiene <strong>{{ $task->comments->count() }} comentario(s)</strong> asociado(s).
                            </div>
                            @endif
                            @if($task->dependents && $task->dependents->count() > 0)
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <strong>Atención:</strong> {{ $task->dependents->count() }} tarea(s) dependen de esta.
                            </div>
                            @endif
                            @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                            <div class="alert alert-info">
                                <i class="bi bi-people me-2"></i>
                                Esta tarea está asignada a <strong>{{ $task->assignedUsers->count() }} usuario(s)</strong>.
                            </div>
                            @elseif(isset($task->assignedUser) && $task->assignedUser)
                            {{-- Compatibilidad con estructura antigua --}}
                            <div class="alert alert-info">
                                <i class="bi bi-person me-2"></i>
                                Esta tarea está asignada a <strong>{{ $task->assignedUser->name }}</strong>.
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" action="{{ route('admin.tasks.delete', $task) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>Eliminar Tarea
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $tasks->appends(request()->query())->links() }}
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-square display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron tareas</h5>
                    <p class="text-muted">Ajusta los filtros de búsqueda o crea nuevas tareas</p>
                    <a href="{{ route('admin.tasks.create') }}" class="btn btn-dark mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Crear tu Primera Tarea
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

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