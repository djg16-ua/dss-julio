@extends('layouts.app')

@section('title', 'Gestionar Usuarios - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-people me-3"></i>Gestión de Usuarios
                    </h1>
                    <p class="lead text-muted">
                        Administra todos los usuarios del sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                    </a>
                </div>
            </div>

            <!-- Estadísticas de usuarios -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-primary">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_users'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Usuarios</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-danger">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['admin_users'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Administradores</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-success">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['verified_users'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Verificados</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-warning">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['recent_users'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Últimos 30 días</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.users') }}">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="search" class="form-label">Buscar usuario</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            value="{{ request('search') }}" placeholder="Nombre o email...">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="role" class="form-label">Rol</label>
                                        <select class="form-select" id="role" name="role">
                                            <option value="">Todos los roles</option>
                                            <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Administradores</option>
                                            <option value="USER" {{ request('role') === 'USER' ? 'selected' : '' }}>Usuarios</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="verified" class="form-label">Verificación</label>
                                        <select class="form-select" id="verified" name="verified">
                                            <option value="">Todos</option>
                                            <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verificados</option>
                                            <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>No verificados</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-search me-1"></i>Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de usuarios expandidos -->
            @if($users->count() > 0)
            @foreach($users as $user)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Información principal del usuario -->
                        <div class="col-lg-3 col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="feature-icon primary me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                                    <p class="mb-0 text-muted">{{ $user->email }}</p>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Rol y estado -->
                        <div class="col-lg-2 col-md-3">
                            <div class="mb-2">
                                <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="form-select form-select-sm"
                                        onchange="this.form.submit()"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <option value="USER" {{ $user->role === 'USER' ? 'selected' : '' }}>Usuario</option>
                                        <option value="ADMIN" {{ $user->role === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </div>
                            <div>
                                @if($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Verificado
                                </span>
                                @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-exclamation-circle me-1"></i>Pendiente
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="col-lg-2 col-md-3">
                            <div class="small">
                                <div class="mb-1">
                                    <strong>Registro:</strong><br>
                                    <span class="text-muted">{{ $user->created_at->format('d/m/Y') }}</span><br>
                                    <span class="text-muted">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                                @if($user->email_verified_at)
                                <div>
                                    <strong>Verificado:</strong><br>
                                    <span class="text-muted">{{ $user->email_verified_at->format('d/m/Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estadísticas expandidas -->
                        <div class="col-lg-3 col-md-6">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-primary">{{ $user->createdProjects->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-kanban me-1"></i>Proyectos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-success">{{ $user->assignedTasks->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-check-square me-1"></i>Tareas
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-info">{{ $user->teams->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>Equipos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-warning">{{ $user->comments->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-chat me-1"></i>Comentarios
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="col-lg-2 col-md-2 text-end">
                            <div class="btn-group-vertical d-grid gap-2" role="group">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación de eliminación -->
            @if($user->id !== auth()->id())
            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
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
                            <p>¿Estás seguro de que quieres eliminar al usuario <strong>{{ $user->name }}</strong>?</p>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Esta acción es irreversible.</strong> Se eliminarán todos sus proyectos, tareas y comentarios.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>Eliminar Usuario
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(request()->query())->links() }}
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-people display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron usuarios</h5>
                    <p class="text-muted">Ajusta los filtros de búsqueda</p>
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
@endsection