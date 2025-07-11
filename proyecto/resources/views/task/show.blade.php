@extends('layouts.app')

@section('title', $task->title . ' - ' . $project->title . ' - TaskFlow')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header de la tarea -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('task.index', $project) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary mb-0">
                                <i class="bi bi-check2-square me-3"></i>{{ $task->title }}
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-collection me-1"></i>
                                <a href="{{ route('module.show', [$project, $task->module]) }}" class="text-decoration-none">
                                    {{ $task->module->name }}
                                </a>
                                <span class="mx-2">•</span>
                                <i class="bi bi-kanban me-1"></i>
                                <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                    {{ $project->title }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @switch($task->status)
                            @case('ACTIVE')
                                <span class="badge bg-success fs-6">✅ Activa</span>
                                @break
                            @case('PENDING')
                                <span class="badge bg-warning fs-6">⏳ Pendiente</span>
                                @break
                            @case('DONE')
                                <span class="badge bg-info fs-6">🎉 Completada</span>
                                @break
                            @default
                                <span class="badge bg-light text-dark fs-6">{{ $task->status }}</span>
                        @endswitch

                        @switch($task->priority)
                            @case('URGENT')
                                <span class="badge bg-danger fs-6">🚨 Urgente</span>
                                @break
                            @case('HIGH')
                                <span class="badge bg-warning fs-6">⚡ Alta</span>
                                @break
                            @case('MEDIUM')
                                <span class="badge bg-info fs-6">📋 Media</span>
                                @break
                            @case('LOW')
                                <span class="badge bg-secondary fs-6">📝 Baja</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editTaskModal">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </button>
                        @php
                            $currentUser = auth()->user();
                            $isProjectCreator = $project->created_by === $currentUser->id;
                            $isAdmin = $currentUser->role === 'ADMIN';
                            $isTaskCreator = $task->created_by === $currentUser->id;
                            $canDeleteTask = $isProjectCreator || $isAdmin || $isTaskCreator;
                        @endphp
                        @if($canDeleteTask)
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="bi bi-trash me-2"></i>Eliminar
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información de la tarea -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(78, 115, 223, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-check2-square text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary">Información de la Tarea</h4>
                            </div>
                            
                            <!-- Estado y Prioridad -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-flag me-2"></i>Estado y Prioridad
                                </h6>
                                <div class="d-flex flex-column gap-2">
                                    @switch($task->status)
                                        @case('ACTIVE')
                                            <span class="badge bg-success">✅ Activa</span>
                                            @break
                                        @case('PENDING')
                                            <span class="badge bg-warning">⏳ Pendiente</span>
                                            @break
                                        @case('DONE')
                                            <span class="badge bg-info">🎉 Completada</span>
                                            @break
                                    @endswitch
                                    
                                    @switch($task->priority)
                                        @case('URGENT')
                                            <span class="badge bg-danger">🚨 Urgente</span>
                                            @break
                                        @case('HIGH')
                                            <span class="badge bg-warning">⚡ Alta</span>
                                            @break
                                        @case('MEDIUM')
                                            <span class="badge bg-info">📋 Media</span>
                                            @break
                                        @case('LOW')
                                            <span class="badge bg-secondary">📝 Baja</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>

                            <!-- Creador de la tarea -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-person-plus me-2"></i>Creada por
                                </h6>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ strtoupper(substr($task->creator->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $task->creator->name }}</strong>
                                        @if($task->creator->id === auth()->id())
                                            <small class="text-primary">(Tú)</small>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $task->creator->email }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Módulo al que pertenece -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-collection me-2"></i>Módulo
                                </h6>
                                <a href="{{ route('module.show', [$project, $task->module]) }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-2 border rounded hover-card">
                                        <div class="me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-collection text-secondary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $task->module->name }}</strong>
                                            @if($task->module->is_core)
                                                <span class="badge bg-primary ms-1">Core</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Estadísticas de la tarea - 2 cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-people text-success mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $taskStats['assigned_users'] }}</h5>
                                    <p class="text-muted mb-0 small">Usuarios Asignados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-chat-dots text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $taskStats['total_comments'] }}</h5>
                                    <p class="text-muted mb-0 small">Comentarios</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción de la tarea -->
                    @if($task->description)
                        <div class="card feature-card" style="max-height: 140px; overflow: hidden;">
                            <div class="card-body py-2 px-3">
                                <h6 class="fw-bold text-primary mb-1">
                                    <i class="bi bi-card-text me-2"></i>Descripción
                                </h6>
                                <div class="description-content">
                                    <p class="text-muted mb-0" style="line-height: 1.4;">
                                        {{ Str::limit($task->description, 324) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gestión de usuarios asignados -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    Usuarios Asignados (<span id="users-count">{{ $task->assignedUsers->count() }}</span>)
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignUserModal">
                                    <i class="bi bi-person-plus me-1"></i>Asignar Usuario
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="users-container">
                                @if($task->assignedUsers->count() > 0)
                                    <div class="row g-3">
                                        @foreach($task->assignedUsers as $user)
                                            <div class="col-lg-4 col-md-6" data-user-id="{{ $user->id }}">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                                    <div class="d-flex align-items-center flex-grow-1">
                                                        <div class="avatar-circle me-3">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar-plus me-1"></i>
                                                                Asignado {{ $user->pivot->assigned_at ? \Carbon\Carbon::parse($user->pivot->assigned_at)->format('d/m/Y') : 'N/A' }}
                                                            </small>
                                                            @if($user->id === auth()->id())
                                                                <span class="badge bg-success ms-2">Tú</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-outline-danger btn-sm ms-2" 
                                                            onclick="removeUser({{ $user->id }}, '{{ $user->name }}')"
                                                            title="Desasignar usuario">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4" id="no-users-message">
                                        <i class="bi bi-people display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay usuarios asignados</h6>
                                        <p class="text-muted mb-3">Asigna usuarios de los equipos del módulo para trabajar en esta tarea</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gestión de comentarios -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-chat-dots text-primary me-2"></i>
                                    Comentarios (<span id="comments-count">{{ $task->comments->count() }}</span>)
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCommentModal">
                                    <i class="bi bi-chat-plus me-1"></i>Comentar Tarea
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="comments-container">
                                @if($task->comments->count() > 0)
                                    <div class="row g-3">
                                        @foreach($task->comments as $comment)
                                            <div class="col-12" data-comment-id="{{ $comment->id }}">
                                                <div class="d-flex align-items-start p-1 border rounded">
                                                    <div class="avatar-circle me-3">
                                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <div>
                                                                <strong>{{ $comment->user->name }}</strong>
                                                                @if($comment->user->id === auth()->id())
                                                                    <span class="badge bg-success ms-1">Tú</span>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <small class="text-muted">
                                                                    <i class="bi bi-clock me-1"></i>
                                                                    {{ $comment->created_at->diffForHumans() }}
                                                                </small>
                                                                @php
                                                                    $isCommentAuthor = $comment->user_id === $currentUser->id;
                                                                    $canDeleteComment = $isCommentAuthor || $isProjectCreator || $isAdmin;
                                                                @endphp
                                                                @if($canDeleteComment)
                                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                            onclick="removeComment({{ $comment->id }}, '{{ $comment->user->name }}')"
                                                                            title="Eliminar comentario">
                                                                        <i class="bi bi-x"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="comment-content">
                                                            <p class="mb-0" style="line-height: 1.5;">
                                                                {{ $comment->content }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4" id="no-comments-message">
                                        <i class="bi bi-chat-dots display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay comentarios</h6>
                                        <p class="text-muted mb-3">Sé el primero en comentar sobre esta tarea</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actividad reciente -->
            <div class="row">
                <div class="col-12">
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
                                        <h6 class="fw-bold mb-1">Tarea creada</h6>
                                        <p class="text-muted small mb-1">{{ $task->creator->name }} creó la tarea en el módulo {{ $task->module->name }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $task->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if($task->updated_at != $task->created_at)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-pencil text-secondary" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Tarea actualizada</h6>
                                            <p class="text-muted small mb-1">Se realizaron cambios en la tarea</p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $task->updated_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($task->assignedUsers->count() > 0)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-people text-success" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Usuarios asignados</h6>
                                            <p class="text-muted small mb-1">{{ $task->assignedUsers->count() }} usuario(s) asignado(s) a la tarea</p>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>
                                                Usuarios: {{ $task->assignedUsers->pluck('name')->join(', ') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif

                                @if($task->comments->count() > 0)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-chat-dots text-info" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Comentarios añadidos</h6>
                                            <p class="text-muted small mb-1">{{ $task->comments->count() }} comentario(s) en la tarea</p>
                                            <small class="text-muted">
                                                <i class="bi bi-chat-dots me-1"></i>
                                                Último comentario por {{ $task->comments->first()->user->name }} - {{ $task->comments->first()->created_at->diffForHumans() }}
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

<!-- Modal para asignar usuario -->
<div class="modal fade" id="assignUserModal" tabindex="-1" aria-labelledby="assignUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUserModalLabel">Asignar Usuario a la Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignUserForm">
                    @csrf
                    <div class="mb-3">
                        <label for="user-search" class="form-label">Buscar Usuario del Módulo</label>
                        <input type="text" class="form-control" id="user-search" placeholder="Escribe nombre o email...">
                        <div class="form-text">Busca entre los miembros de los equipos asignados al módulo</div>
                    </div>
                    
                    <div id="search-results" class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        <!-- Resultados de búsqueda aparecerán aquí -->
                    </div>
                    
                    <div class="mb-3" id="selected-user" style="display: none;">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Usuario seleccionado:</strong> <span id="selected-user-name"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAssignUser" disabled>
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Asignar a la Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir comentario -->
<div class="modal fade" id="addCommentModal" tabindex="-1" aria-labelledby="addCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCommentModalLabel">Comentar Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCommentForm">
                    @csrf
                    <div class="mb-3">
                        <label for="comment-content" class="form-label">Tu comentario <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="comment-content" rows="5" required 
                                  placeholder="Escribe tu comentario sobre la tarea..." maxlength="2000"></textarea>
                        <div class="form-text">
                            <span id="char-count">0</span>/2000 caracteres
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Recuerda:</strong> Los comentarios son visibles para todos los miembros del proyecto.
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Los comentarios no se pueden modificar una vez publicados. Solo pueden ser eliminados por el creador del proyecto o administradores.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAddComment" disabled>
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Publicar Comentario
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de confirmación para eliminar tarea -->
@if($canDeleteTask)
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
                    <strong>¿Estás seguro de que deseas eliminar la tarea "{{ $task->title }}"?</strong>
                </div>
                <p class="mb-3">Esta acción no se puede deshacer y se eliminarán:</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todos los comentarios de la tarea</li>
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todas las asignaciones de usuarios</li>
                </ul>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Nota:</strong> Solo puedes eliminar esta tarea porque eres 
                    @if($isTaskCreator && $isProjectCreator)
                        el creador de la tarea y del proyecto.
                    @elseif($isTaskCreator)
                        el creador de la tarea.
                    @elseif($isProjectCreator)
                        el creador del proyecto.
                    @elseif($isAdmin)
                        administrador del sistema.
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('task.destroy', [$project, $task]) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Eliminar Tarea
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar tarea -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Editar Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit-task-title" class="form-label">Título de la tarea <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit-task-title" value="{{ $task->title }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit-task-status" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit-task-status" required>
                                    <option value="PENDING" {{ $task->status === 'PENDING' ? 'selected' : '' }}>⏳ Pendiente</option>
                                    <option value="ACTIVE" {{ $task->status === 'ACTIVE' ? 'selected' : '' }}>✅ Activa</option>
                                    <option value="DONE" {{ $task->status === 'DONE' ? 'selected' : '' }}>🎉 Completada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-task-priority" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit-task-priority" required>
                                    <option value="URGENT" {{ $task->priority === 'URGENT' ? 'selected' : '' }}>🚨 Urgente</option>
                                    <option value="HIGH" {{ $task->priority === 'HIGH' ? 'selected' : '' }}>⚡ Alta</option>
                                    <option value="MEDIUM" {{ $task->priority === 'MEDIUM' ? 'selected' : '' }}>📋 Media</option>
                                    <option value="LOW" {{ $task->priority === 'LOW' ? 'selected' : '' }}>📝 Baja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-task-module" class="form-label">Módulo</label>
                                <input type="text" class="form-control" value="{{ $task->module->name }}" disabled>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    El módulo no se puede cambiar una vez creada la tarea
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit-task-description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit-task-description" rows="4" placeholder="Describe los detalles de la tarea...">{{ $task->description }}</textarea>
                        <div class="form-text">
                            <span id="edit-char-count">{{ strlen($task->description ?? '') }}</span>/2000 caracteres
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> Los usuarios asignados se gestionan desde las secciones correspondientes de esta página.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="confirmEditTask">
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Actualizar Tarea
                </button>
            </div>
        </div>
    </div>
</div>

@endif

@push('scripts')
<script>
let selectedUserId = null;

// Variables de permisos desde PHP
const canDeleteTask = @json($canDeleteTask ?? false);
const isProjectCreator = @json($isProjectCreator ?? false);
const isAdmin = @json($isAdmin ?? false);
const isTaskCreator = @json($isTaskCreator ?? false);

// Debug: verificar permisos
console.log('Permisos tarea:', {canDeleteTask, isProjectCreator, isAdmin, isTaskCreator});

// Funciones para gestión de tarea
function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function removeUser(userId, userName) {
    console.log('Intentando desasignar usuario:', userId, userName);
    
    if (confirm(`¿Estás seguro de que quieres desasignar a ${userName} de esta tarea?`)) {
        const userCard = document.querySelector(`[data-user-id="${userId}"]`);
        const button = userCard.querySelector('.btn-outline-danger');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        button.disabled = true;
        
        // URL CORREGIDA
        const url = `{{ route('task.remove-user', [$project, $task, ':userId']) }}`.replace(':userId', userId);
        console.log('URL de desasignación usuario:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta desasignación usuario:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos usuario:', data);
            if (data.success) {
                userCard.style.transition = 'all 0.3s ease';
                userCard.style.opacity = '0';
                userCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    userCard.remove();
                    updateUsersCount();
                    checkIfNoUsers();
                }, 300);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error completo usuario:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            alert('Error al desasignar el usuario: ' + error.message);
        });
    }
}

function removeComment(commentId, authorName) {
    console.log('Intentando eliminar comentario:', commentId, authorName);
    
    if (confirm(`¿Estás seguro de que quieres eliminar el comentario de ${authorName}?`)) {
        const commentCard = document.querySelector(`[data-comment-id="${commentId}"]`);
        const button = commentCard.querySelector('.btn-outline-danger');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        button.disabled = true;
        
        // URL CORREGIDA
        const url = `{{ route('task.delete-comment', [$project, $task, ':commentId']) }}`.replace(':commentId', commentId);
        console.log('URL de eliminación comentario:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta eliminación comentario:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos comentario:', data);
            if (data.success) {
                commentCard.style.transition = 'all 0.3s ease';
                commentCard.style.opacity = '0';
                commentCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    commentCard.remove();
                    updateCommentsCount();
                    checkIfNoComments();
                }, 300);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error completo comentario:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            alert('Error al eliminar el comentario: ' + error.message);
        });
    }
}

function updateUsersCount() {
    const userCards = document.querySelectorAll('[data-user-id]');
    const count = userCards.length;
    const countElement = document.getElementById('users-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function updateCommentsCount() {
    const commentCards = document.querySelectorAll('[data-comment-id]');
    const count = commentCards.length;
    const countElement = document.getElementById('comments-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function checkIfNoUsers() {
    const userCards = document.querySelectorAll('[data-user-id]');
    const container = document.getElementById('users-container');
    
    if (userCards.length === 0 && container) {
        container.innerHTML = `
            <div class="text-center py-4" id="no-users-message">
                <i class="bi bi-people display-4 text-muted mb-3"></i>
                <h6 class="text-muted">No hay usuarios asignados</h6>
                <p class="text-muted mb-3">Asigna usuarios de los equipos del módulo para trabajar en esta tarea</p>
            </div>
        `;
    }
}

function checkIfNoComments() {
    const commentCards = document.querySelectorAll('[data-comment-id]');
    const container = document.getElementById('comments-container');
    
    if (commentCards.length === 0 && container) {
        container.innerHTML = `
            <div class="text-center py-4" id="no-comments-message">
                <i class="bi bi-chat-dots display-4 text-muted mb-3"></i>
                <h6 class="text-muted">No hay comentarios</h6>
                <p class="text-muted mb-3">Sé el primero en comentar sobre esta tarea</p>
            </div>
        `;
    }
}

// Búsqueda de usuarios para asignar
let searchTimeout;
const userSearchInput = document.getElementById('user-search');
if (userSearchInput) {
    userSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        clearTimeout(searchTimeout);
        
        if (searchTerm.length < 2) {
            document.getElementById('search-results').innerHTML = '';
            document.getElementById('selected-user').style.display = 'none';
            document.getElementById('confirmAssignUser').disabled = true;
            selectedUserId = null;
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchUsers(searchTerm);
        }, 300);
    });
}

function searchUsers(term) {
    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer) return;
    
    resultsContainer.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <span class="ms-2">Buscando usuarios...</span>
        </div>
    `;
    
    const url = `{{ route('task.available-users', [$project, $task]) }}?search=${encodeURIComponent(term)}`;
    console.log('URL búsqueda usuarios:', url);
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Respuesta búsqueda usuarios:', response);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(users => {
        console.log('Usuarios encontrados:', users);
        displaySearchResults(users);
    })
    .catch(error => {
        console.error('Error búsqueda usuarios:', error);
        resultsContainer.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>Error al buscar usuarios: ${error.message}
            </div>
        `;
    });
}

function displaySearchResults(users) {
    const container = document.getElementById('search-results');
    if (!container) return;
    
    if (users.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-person-x me-2"></i>No se encontraron usuarios disponibles
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
                <div class="flex-grow-1">
                    <strong>${user.name}</strong>
                    ${user.is_current_user ? ' (Tú)' : ''}
                    <br>
                    <small class="text-muted">${user.email}</small>
                    ${user.team_names ? `<br><small class="text-info"><i class="bi bi-people me-1"></i>${user.team_names}</small>` : ''}
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
    console.log('Usuario seleccionado:', userId, user);
    
    // Resaltar usuario seleccionado
    document.querySelectorAll('.user-result').forEach(el => el.classList.remove('border-primary'));
    const selectedElement = document.querySelector(`[data-user-id="${userId}"]`);
    if (selectedElement) {
        selectedElement.classList.add('border-primary');
    }
    
    // Mostrar usuario seleccionado
    const selectedUserDiv = document.getElementById('selected-user');
    const selectedUserName = document.getElementById('selected-user-name');
    const confirmButton = document.getElementById('confirmAssignUser');
    
    if (selectedUserDiv && selectedUserName) {
        selectedUserName.textContent = user.name + (user.is_current_user ? ' (Tú)' : '');
        selectedUserDiv.style.display = 'block';
    }
    if (confirmButton) {
        confirmButton.disabled = false;
    }
}

// Confirmar asignar usuario
const confirmAssignUserButton = document.getElementById('confirmAssignUser');
if (confirmAssignUserButton) {
    confirmAssignUserButton.addEventListener('click', function() {
        if (!selectedUserId) return;
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        
        console.log('Asignando usuario:', selectedUserId);
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('task.assign-user', [$project, $task]) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: selectedUserId
            })
        })
        .then(response => {
            console.log('Respuesta asignar usuario:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos asignar usuario:', data);
            if (data.success) {
                const modal = document.getElementById('assignUserModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                location.reload(); // Recargar para mostrar el nuevo usuario
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error asignar usuario:', error);
            alert('Error al asignar el usuario: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Contador de caracteres para comentarios
const commentTextarea = document.getElementById('comment-content');
const charCountSpan = document.getElementById('char-count');
const confirmAddCommentButton = document.getElementById('confirmAddComment');

if (commentTextarea && charCountSpan) {
    commentTextarea.addEventListener('input', function() {
        const currentLength = this.value.length;
        charCountSpan.textContent = currentLength;
        
        // Cambiar color según el límite
        if (currentLength > 1800) {
            charCountSpan.className = 'text-danger fw-bold';
        } else if (currentLength > 1500) {
            charCountSpan.className = 'text-warning fw-bold';
        } else {
            charCountSpan.className = 'text-muted';
        }
        
        // Habilitar/deshabilitar botón
        if (confirmAddCommentButton) {
            confirmAddCommentButton.disabled = currentLength === 0 || currentLength > 2000;
        }
    });
}

// Confirmar añadir comentario
if (confirmAddCommentButton) {
    confirmAddCommentButton.addEventListener('click', function() {
        const content = commentTextarea ? commentTextarea.value.trim() : '';
        
        if (!content) {
            alert('Por favor escribe un comentario');
            return;
        }
        
        if (content.length > 2000) {
            alert('El comentario no puede exceder 2000 caracteres');
            return;
        }
        
        console.log('Añadiendo comentario:', content);
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('task.add-comment', [$project, $task]) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                content: content
            })
        })
        .then(response => {
            console.log('Respuesta añadir comentario:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos añadir comentario:', data);
            if (data.success) {
                const modal = document.getElementById('addCommentModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                location.reload(); // Recargar para mostrar el nuevo comentario
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error añadir comentario:', error);
            alert('Error al añadir el comentario: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Limpiar modales al cerrarse
const assignUserModal = document.getElementById('assignUserModal');
if (assignUserModal) {
    assignUserModal.addEventListener('hidden.bs.modal', function() {
        const userSearch = document.getElementById('user-search');
        const searchResults = document.getElementById('search-results');
        const selectedUser = document.getElementById('selected-user');
        const confirmButton = document.getElementById('confirmAssignUser');
        
        if (userSearch) userSearch.value = '';
        if (searchResults) searchResults.innerHTML = '';
        if (selectedUser) selectedUser.style.display = 'none';
        if (confirmButton) confirmButton.disabled = true;
        selectedUserId = null;
    });
}

const addCommentModal = document.getElementById('addCommentModal');
if (addCommentModal) {
    addCommentModal.addEventListener('hidden.bs.modal', function() {
        if (commentTextarea) {
            commentTextarea.value = '';
            // Trigger input event para resetear contador
            commentTextarea.dispatchEvent(new Event('input'));
        }
        if (confirmAddCommentButton) confirmAddCommentButton.disabled = true;
    });
}

// Efectos hover para cards clickables
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando tarea...');
    
    const style = document.createElement('style');
    style.textContent = `
        .hover-card:hover {
            background-color: #f8f9fc !important;
            transform: translateX(3px);
        }
        
        .hover-card {
            transition: all 0.2s ease-in-out;
        }
        
        .user-result:hover {
            background-color: #f8f9fc !important;
        }
        
        .user-result.border-primary {
            background-color: #e3f2fd !important;
        }
        
        .comment-content {
            word-wrap: break-word;
            white-space: pre-wrap;
        }
    `;
    document.head.appendChild(style);
});

// MODAL DE EDITAR TAREA
const editTaskModal = document.getElementById('editTaskModal');
const editTaskTextarea = document.getElementById('edit-task-description');
const editCharCountSpan = document.getElementById('edit-char-count');
const confirmEditTaskButton = document.getElementById('confirmEditTask');

// Contador de caracteres para descripción
if (editTaskTextarea && editCharCountSpan) {
    editTaskTextarea.addEventListener('input', function() {
        const currentLength = this.value.length;
        editCharCountSpan.textContent = currentLength;
        
        // Cambiar color según el límite
        if (currentLength > 1800) {
            editCharCountSpan.className = 'text-danger fw-bold';
        } else if (currentLength > 1500) {
            editCharCountSpan.className = 'text-warning fw-bold';
        } else {
            editCharCountSpan.className = 'text-muted';
        }
        
        // Habilitar/deshabilitar botón si excede límite
        if (confirmEditTaskButton) {
            const titleValue = document.getElementById('edit-task-title')?.value.trim() || '';
            confirmEditTaskButton.disabled = !titleValue || currentLength > 2000;
        }
    });
}

// Validar título
const editTitleInput = document.getElementById('edit-task-title');
if (editTitleInput) {
    editTitleInput.addEventListener('input', function() {
        const titleValue = this.value.trim();
        const descLength = editTaskTextarea ? editTaskTextarea.value.length : 0;
        
        if (confirmEditTaskButton) {
            confirmEditTaskButton.disabled = !titleValue || descLength > 2000;
        }
    });
}

// Confirmar editar tarea
if (confirmEditTaskButton) {
    confirmEditTaskButton.addEventListener('click', function() {
        const titleInput = document.getElementById('edit-task-title');
        const descriptionInput = document.getElementById('edit-task-description');
        const statusSelect = document.getElementById('edit-task-status');
        const prioritySelect = document.getElementById('edit-task-priority');
        
        const title = titleInput ? titleInput.value.trim() : '';
        const description = descriptionInput ? descriptionInput.value.trim() : '';
        const status = statusSelect ? statusSelect.value : '';
        const priority = prioritySelect ? prioritySelect.value : '';
        
        if (!title) {
            alert('Por favor ingresa un título para la tarea');
            return;
        }
        
        if (!status || !priority) {
            alert('Por favor selecciona estado y prioridad');
            return;
        }
        
        if (description.length > 2000) {
            alert('La descripción no puede exceder 2000 caracteres');
            return;
        }
        
        console.log('Editando tarea:', {title, description, status, priority});
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('task.update', [$project, $task]) }}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                description: description,
                status: status,
                priority: priority
            })
        })
        .then(response => {
            console.log('Respuesta editar tarea:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos editar tarea:', data);
            if (data.success) {
                const modal = document.getElementById('editTaskModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                
                // Mostrar mensaje de éxito y recargar
                showSuccessMessage('Tarea actualizada exitosamente');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error editar tarea:', error);
            alert('Error al actualizar la tarea: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Limpiar modal de edición al cerrarse
if (editTaskModal) {
    editTaskModal.addEventListener('hidden.bs.modal', function() {
        // Restaurar valores originales
        const titleInput = document.getElementById('edit-task-title');
        const descriptionInput = document.getElementById('edit-task-description');
        const statusSelect = document.getElementById('edit-task-status');
        const prioritySelect = document.getElementById('edit-task-priority');
        
        if (titleInput) titleInput.value = '{{ $task->title }}';
        if (descriptionInput) descriptionInput.value = '{{ $task->description ?? '' }}';
        if (statusSelect) statusSelect.value = '{{ $task->status }}';
        if (prioritySelect) prioritySelect.value = '{{ $task->priority }}';
        
        // Resetear contador de caracteres
        if (descriptionInput && editCharCountSpan) {
            editCharCountSpan.textContent = descriptionInput.value.length;
            editCharCountSpan.className = 'text-muted';
        }
        
        if (confirmEditTaskButton) confirmEditTaskButton.disabled = false;
    });
}

// Función para mostrar mensaje de éxito
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
    alertDiv.innerHTML = `
        <i class="bi bi-check-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-dismiss después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

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

.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #4e73df, rgba(78, 115, 223, 0.1));
}
</style>
@endsection