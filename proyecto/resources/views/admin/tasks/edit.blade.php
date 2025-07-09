@extends('layouts.app')

@section('title', 'Editar Tarea - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-dark">
                        <i class="bi bi-pencil-square me-3"></i>Editar Tarea
                    </h1>
                    <p class="lead text-muted">
                        Gestiona la información y configuración de la tarea <strong>{{ $task->title }}</strong>
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

            <!-- Información básica de la tarea -->
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
                            <form method="POST" action="{{ route('admin.tasks.update', $task) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label fw-bold">Título de la Tarea</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $task->title) }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="priority" class="form-label fw-bold">Prioridad</label>
                                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                            @foreach($priorities as $value => $label)
                                            <option value="{{ $value }}" {{ old('priority', $task->priority) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">Descripción</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="4"
                                            placeholder="Describe detalladamente qué se debe hacer...">{{ old('description', $task->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="module_id" class="form-label fw-bold">Módulo</label>
                                        <select class="form-select @error('module_id') is-invalid @enderror" id="module_id" name="module_id">
                                            <option value="">Sin módulo específico</option>
                                            @foreach($modules as $module)
                                            <option value="{{ $module->id }}" {{ old('module_id', $task->module_id) == $module->id ? 'selected' : '' }}>
                                                {{ $module->name }} ({{ $module->project->title }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('module_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="assigned_users" class="form-label fw-bold">Asignado a</label>
                                        <select class="form-select @error('assigned_users') is-invalid @enderror" id="assigned_users" name="assigned_users[]" multiple>
                                            @foreach($availableUsers as $user)
                                            @php
                                                $selectedUsers = old('assigned_users', $task->assignedUsers->pluck('id')->toArray());
                                            @endphp
                                            <option value="{{ $user->id }}" {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_users')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Mantén Ctrl presionado para seleccionar múltiples usuarios</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label fw-bold">Estado</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="PENDING" {{ old('status', $task->status) === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="ACTIVE" {{ old('status', $task->status) === 'ACTIVE' ? 'selected' : '' }}>Activa</option>
                                            <option value="DONE" {{ old('status', $task->status) === 'DONE' ? 'selected' : '' }}>Completada</option>
                                            <option value="PAUSED" {{ old('status', $task->status) === 'PAUSED' ? 'selected' : '' }}>Pausada</option>
                                            <option value="CANCELLED" {{ old('status', $task->status) === 'CANCELLED' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="end_date" class="form-label fw-bold">Fecha Límite</label>
                                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date"
                                            value="{{ old('end_date', $task->end_date?->format('Y-m-d\TH:i')) }}">
                                        @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="depends_on" class="form-label fw-bold">Depende de</label>
                                        <select class="form-select @error('depends_on') is-invalid @enderror" id="depends_on" name="depends_on">
                                            <option value="">Sin dependencias</option>
                                            @foreach($availableTasks as $otherTask)
                                            @if($otherTask->id !== $task->id)
                                            <option value="{{ $otherTask->id }}" {{ old('depends_on', $task->depends_on) == $otherTask->id ? 'selected' : '' }}>
                                                {{ Str::limit($otherTask->title, 40) }}
                                                @if($otherTask->assignedUsers->count() > 0)
                                                <small>({{ $otherTask->assignedUsers->pluck('name')->join(', ') }})</small>
                                                @endif
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('depends_on')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-dark">
                                            <i class="bi bi-check-lg me-2"></i>Actualizar Tarea
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de la tarea -->
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
                                        <div class="fw-bold h4 text-primary">{{ $task->comments->count() }}</div>
                                        <small class="text-muted">Comentarios</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-warning">{{ $task->dependents->count() }}</div>
                                        <small class="text-muted">Dependientes</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h4 text-success">{{ $task->assignedUsers->count() }}</div>
                                        <small class="text-muted">Asignados</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        @php
                                        $isOverdue = $task->end_date && $task->end_date->isPast() && $task->status !== 'DONE';
                                        @endphp
                                        <div class="fw-bold h4 {{ $isOverdue ? 'text-danger' : 'text-secondary' }}">
                                            {{ $isOverdue ? 'SÍ' : 'NO' }}
                                        </div>
                                        <small class="text-muted">Vencida</small>
                                    </div>
                                </div>
                            </div>

                            @php
                            $progress = $task->status === 'DONE' ? 100 : ($task->status === 'ACTIVE' ? 50 : 0);
                            @endphp
                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-bold">Progreso</small>
                                    <small class="fw-bold">{{ $progress }}%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $progress == 100 ? 'success' : ($progress == 50 ? 'warning' : 'secondary') }}" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            <hr>

                            <div class="small text-muted">
                                <div><strong>Creador:</strong> {{ $task->creator->name ?? 'Sistema' }}</div>
                                @if($task->assignedUsers->count() > 0)
                                <div><strong>Asignado a:</strong> {{ $task->assignedUsers->pluck('name')->join(', ') }}</div>
                                @else
                                <div><strong>Asignado a:</strong> <span class="text-warning">Sin asignar</span></div>
                                @endif
                                @if($task->module)
                                <div><strong>Módulo:</strong> {{ $task->module->name }}</div>
                                <div><strong>Proyecto:</strong> {{ $task->module->project->title }}</div>
                                @endif
                                <div><strong>Creada:</strong> {{ $task->created_at->format('d/m/Y H:i') }}</div>
                                <div><strong>Actualizada:</strong> {{ $task->updated_at->format('d/m/Y H:i') }}</div>
                                @if($task->completed_at)
                                <div><strong>Completada:</strong> {{ $task->completed_at->format('d/m/Y H:i') }}</div>
                                @endif
                                @if($task->end_date)
                                <div><strong>Fecha límite:</strong> {{ $task->end_date->format('d/m/Y H:i') }}</div>
                                @endif
                                <div><strong>ID:</strong> {{ $task->id }}</div>
                                @if($task->dependency)
                                <div><strong>Depende de:</strong> {{ $task->dependency->title }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Usuarios asignados -->
                    @if($task->assignedUsers->count() > 0)
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-people me-2"></i>
                                Usuarios Asignados
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($task->assignedUsers as $user)
                            <div class="d-flex align-items-center mb-2">
                                <div class="feature-icon primary me-3" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                    <div class="small text-muted">
                                        Asignado: {{ $user->pivot->assigned_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)<hr class="my-2">@endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Comentarios de la tarea -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-chat-dots me-2"></i>
                                        Comentarios ({{ $task->comments->count() }})
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addCommentModal">
                                        <i class="bi bi-plus-circle me-1"></i>Agregar Comentario
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($task->comments->count() > 0)
                            <div class="timeline">
                                @foreach($task->comments->sortByDesc('created_at') as $comment)
                                <div class="timeline-item mb-4">
                                    <div class="d-flex">
                                        <div class="feature-icon secondary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                                    <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }} - {{ $comment->created_at->diffForHumans() }}</small>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteCommentModal{{ $comment->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <div class="bg-light p-3 rounded">
                                                <p class="mb-0">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para eliminar comentario -->
                                <div class="modal fade" id="deleteCommentModal{{ $comment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Eliminar Comentario</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que quieres eliminar este comentario?</p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    Esta acción es irreversible.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="{{ route('admin.tasks.delete-comment', [$task, $comment]) }}" class="d-inline">
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
                                <i class="bi bi-chat-dots display-1 text-muted"></i>
                                <h5 class="text-muted">No hay comentarios</h5>
                                <p class="text-muted">Agrega comentarios para documentar el progreso de la tarea</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tareas dependientes -->
            @if($task->dependents->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-arrow-right me-2"></i>
                                Tareas Dependientes ({{ $task->dependents->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($task->dependents as $dependent)
                                <div class="col-lg-6">
                                    <div class="card border-warning">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="feature-icon warning me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                    <i class="bi bi-check-square"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold">{{ $dependent->title }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($dependent->description, 60) }}</p>
                                                    <div class="d-flex gap-2 mb-2">
                                                        @php
                                                        $statusColors = [
                                                        'PENDING' => 'secondary',
                                                        'ACTIVE' => 'primary',
                                                        'DONE' => 'success',
                                                        'PAUSED' => 'warning',
                                                        'CANCELLED' => 'danger'
                                                        ];
                                                        @endphp
                                                        <span class="badge bg-{{ $statusColors[$dependent->status] ?? 'secondary' }}">
                                                            {{ $dependent->status }}
                                                        </span>
                                                        @if($dependent->assignedUsers->count() > 0)
                                                        <span class="badge bg-light text-dark">
                                                            {{ $dependent->assignedUsers->pluck('name')->join(', ') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        Creada: {{ $dependent->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <a href="{{ route('admin.tasks.edit', $dependent) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para agregar comentario -->
<div class="modal fade" id="addCommentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Agregar Comentario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.tasks.add-comment', $task) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="content" class="form-label">Comentario</label>
                        <textarea class="form-control" name="content" id="content" rows="4"
                            placeholder="Escribe tu comentario sobre el progreso, problemas o actualizaciones..." required></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Los comentarios ayudan a documentar el progreso y comunicar actualizaciones del equipo.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Agregar Comentario</button>
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

        // Actualizar fecha de completado automáticamente cuando se marca como DONE
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                if (this.value === 'DONE') {
                    console.log('Tarea marcada como completada');
                    // Aquí podrías agregar lógica adicional como confirmación
                }
            });
        }

        // Hacer el select múltiple más user-friendly
        const assignedUsersSelect = document.getElementById('assigned_users');
        if (assignedUsersSelect) {
            assignedUsersSelect.addEventListener('focus', function() {
                if (!this.hasAttribute('data-tooltip-shown')) {
                    this.title = 'Mantén Ctrl presionado para seleccionar múltiples usuarios';
                    this.setAttribute('data-tooltip-shown', 'true');
                }
            });
        }

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
    });
</script>
@endpush
@endsection