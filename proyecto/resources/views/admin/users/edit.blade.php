@extends('layouts.app')

@section('title', 'Editar Usuario - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-person-gear me-3"></i>Editar Usuario
                    </h1>
                    <p class="lead text-muted">
                        Modifica todos los datos de <strong>{{ $user->name }}</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Usuarios
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Panel izquierdo - Información del usuario -->
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <div class="bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                    <i class="bi bi-person-fill text-white" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-primary">{{ $user->name }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>

                            <!-- Estado de verificación -->
                            @if($user->email_verified_at)
                            <span class="badge bg-success mb-2">
                                <i class="bi bi-check-circle me-1"></i>Email Verificado
                            </span>
                            @else
                            <span class="badge bg-warning mb-2">
                                <i class="bi bi-exclamation-circle me-1"></i>Email Pendiente
                            </span>
                            @endif

                            <!-- Rol -->
                            <div class="mb-3">
                                <span class="badge bg-{{ $user->role === 'ADMIN' ? 'danger' : 'secondary' }} fs-6">
                                    <i class="bi bi-shield-check me-1"></i>
                                    {{ $user->role }}
                                </span>
                            </div>

                            <p class="text-muted small">
                                <i class="bi bi-calendar me-1"></i>
                                Miembro desde {{ $user->created_at->format('d/m/Y') }}
                            </p>

                            <!-- Estadísticas del usuario -->
                            <div class="row g-2 mt-3">
                                <div class="col-6">
                                    <div class="card text-center">
                                        <div class="card-body p-2">
                                            <h5 class="mb-0 text-primary">{{ $user->createdProjects->count() }}</h5>
                                            <small>Proyectos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center">
                                        <div class="card-body p-2">
                                            <h5 class="mb-0 text-success">{{ $user->assignedTasks->count() }}</h5>
                                            <small>Tareas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center">
                                        <div class="card-body p-2">
                                            <h5 class="mb-0 text-warning">{{ $user->teams->count() }}</h5>
                                            <small>Equipos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center">
                                        <div class="card-body p-2">
                                            <h5 class="mb-0 text-info">{{ $user->comments->count() }}</h5>
                                            <small>Comentarios</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Panel derecho - Formularios de edición -->
                <div class="col-lg-8">
                    <!-- Tabs de edición -->
                    <ul class="nav nav-pills mb-4" id="editTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-tab" data-bs-toggle="pill" data-bs-target="#basic" type="button" role="tab">
                                <i class="bi bi-person me-2"></i>Información Básica
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                                <i class="bi bi-shield me-2"></i>Seguridad
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="teams-tab" data-bs-toggle="pill" data-bs-target="#teams" type="button" role="tab">
                                <i class="bi bi-people me-2"></i>Equipos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity" type="button" role="tab">
                                <i class="bi bi-activity me-2"></i>Actividad
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="editTabContent">
                        <!-- Tab de Información Básica -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-person-circle text-primary me-2"></i>
                                        Datos Personales
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label fw-medium">Nombre Completo</label>
                                                <input type="text"
                                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                    id="name"
                                                    name="name"
                                                    value="{{ old('name', $user->name) }}"
                                                    required>
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email" class="form-label fw-medium">Dirección de Email</label>
                                                <input type="email"
                                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                    id="email"
                                                    name="email"
                                                    value="{{ old('email', $user->email) }}"
                                                    required>
                                                @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="role" class="form-label fw-medium">Rol del Usuario</label>
                                                <select class="form-select form-select-lg @error('role') is-invalid @enderror"
                                                    id="role"
                                                    name="role"
                                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                    <option value="USER" {{ $user->role === 'USER' ? 'selected' : '' }}>Usuario Normal</option>
                                                    <option value="ADMIN" {{ $user->role === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                                                </select>
                                                @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($user->id === auth()->id())
                                                <div class="form-text text-warning">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    No puedes cambiar tu propio rol
                                                </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email_verified" class="form-label fw-medium">Estado de Verificación</label>
                                                <select class="form-select form-select-lg" id="email_verified" name="email_verified">
                                                    <option value="0" {{ !$user->email_verified_at ? 'selected' : '' }}>No Verificado</option>
                                                    <option value="1" {{ $user->email_verified_at ? 'selected' : '' }}>Verificado</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                                            </button>
                                            <button type="reset" class="btn btn-outline-secondary btn-lg ms-2">
                                                <i class="bi bi-arrow-clockwise me-2"></i>Restablecer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Acciones rápidas debajo de datos personales -->
                            <div class="card mt-4">
                                <div class="card-header bg-white py-3">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-lightning text-primary me-2"></i>
                                        Acciones Rápidas
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        @if(!$user->email_verified_at)
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-outline-success w-100"
                                                onclick="verifyUserEmail({{ $user->id }})">
                                                <i class="bi bi-check-circle me-2"></i>Verificar Email
                                            </button>
                                        </div>
                                        @endif

                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-outline-warning w-100"
                                                data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                                <i class="bi bi-key me-2"></i>Resetear Contraseña
                                            </button>
                                        </div>

                                        @if($user->id !== auth()->id())
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-outline-danger w-100"
                                                data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                                <i class="bi bi-trash me-2"></i>Eliminar Usuario
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab de Seguridad -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-shield-lock text-primary me-2"></i>
                                        Configuración de Seguridad
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Cambiar contraseña -->
                                    <form action="{{ route('admin.users.update-password', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>Cambiar Contraseña:</strong> Establecer una nueva contraseña para este usuario.
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="new_password" class="form-label fw-medium">Nueva Contraseña</label>
                                                <input type="password"
                                                    class="form-control form-control-lg @error('new_password') is-invalid @enderror"
                                                    id="new_password"
                                                    name="new_password"
                                                    placeholder="Mínimo 8 caracteres">
                                                @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="new_password_confirmation" class="form-label fw-medium">Confirmar Contraseña</label>
                                                <input type="password"
                                                    class="form-control form-control-lg"
                                                    id="new_password_confirmation"
                                                    name="new_password_confirmation"
                                                    placeholder="Repetir contraseña">
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-warning btn-lg">
                                                <i class="bi bi-key me-2"></i>Cambiar Contraseña
                                            </button>
                                        </div>
                                    </form>

                                    <hr class="my-4">

                                    <!-- Información de seguridad -->
                                    <h6 class="fw-bold">Información de Seguridad</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body p-3">
                                                    <h6 class="card-title small">Último Acceso</h6>
                                                    <p class="card-text text-muted small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $user->updated_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body p-3">
                                                    <h6 class="card-title small">Cuenta Creada</h6>
                                                    <p class="card-text text-muted small">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $user->created_at->format('d/m/Y H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab de Equipos -->
                        <div class="tab-pane fade" id="teams" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-people text-primary me-2"></i>
                                        Gestión de Equipos
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($user->teams->count() > 0)
                                    <div class="row g-3">
                                        @foreach($user->teams as $team)
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">{{ $team->name }}</h6>
                                                            <p class="text-muted small mb-2">{{ $team->description }}</p>
                                                            <span class="badge bg-primary">{{ $team->pivot->role }}</span>
                                                        </div>
                                                        <form action="{{ route('admin.users.remove-from-team', [$user, $team]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Remover del equipo">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <small class="text-muted">
                                                        Unido {{ \Carbon\Carbon::parse($team->pivot->joined_at)->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-people display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No pertenece a ningún equipo</h6>
                                        <p class="text-muted">Puedes asignar este usuario a equipos existentes</p>
                                        <button class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Asignar a Equipo
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tab de Actividad -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-activity text-primary me-2"></i>
                                        Actividad del Usuario
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Proyectos creados -->
                                    <h6 class="fw-bold">Proyectos Creados ({{ $user->createdProjects->count() }})</h6>
                                    @if($user->createdProjects->count() > 0)
                                    <div class="list-group list-group-flush mb-4">
                                        @foreach($user->createdProjects->take(5) as $project)
                                        <div class="list-group-item border-0 px-0">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">{{ $project->title }}</h6>
                                                    <p class="mb-1 small text-muted">{{ Str::limit($project->description, 80) }}</p>
                                                </div>
                                                <small class="text-muted">{{ $project->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-muted">No ha creado proyectos</p>
                                    @endif

                                    <!-- Tareas asignadas -->
                                    <h6 class="fw-bold">Tareas Asignadas ({{ $user->assignedTasks->count() }})</h6>
                                    @if($user->assignedTasks->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($user->assignedTasks->take(5) as $task)
                                        <div class="list-group-item border-0 px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $task->title }}</h6>
                                                    <small class="text-muted">{{ $task->description }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-{{ $task->status === 'DONE' ? 'success' : ($task->status === 'ACTIVE' ? 'primary' : 'secondary') }}">
                                                        {{ $task->status }}
                                                    </span>
                                                    <small class="text-muted d-block">{{ $task->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-muted">No tiene tareas asignadas</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para resetear contraseña -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-key me-2"></i>Resetear Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>¿Generar una nueva contraseña temporal para <strong>{{ $user->name }}</strong>?</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Se generará una contraseña temporal que deberá cambiar en su próximo acceso.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-1"></i>Generar Nueva Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar usuario -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Eliminar Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres eliminar a <strong>{{ $user->name }}</strong>?</p>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Esta acción es irreversible.</strong> Se eliminarán todos sus proyectos, tareas y comentarios.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Eliminar Usuario
                    </button>
                </div>
            </form>
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
@endsection