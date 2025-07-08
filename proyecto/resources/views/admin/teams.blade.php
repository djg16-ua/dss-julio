@extends('layouts.app')

@section('title', 'Gestionar Equipos - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4 align-items-center">
                <div class="col-lg-6 col-md-8">
                    <h1 class="display-5 fw-bold text-warning mb-2">
                        <i class="bi bi-diagram-3 me-3"></i>Gestión de Equipos
                    </h1>
                    <p class="lead text-muted mb-0">
                        Administra todos los equipos del sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-6 col-md-4">
                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                        </a>
                        <a href="{{ route('admin.teams.create') }}" class="btn btn-warning">
                            <i class="bi bi-plus-circle me-2"></i>Crear Equipo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de equipos -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-warning">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_teams'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Equipos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-info">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['active_members'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Miembros Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-success">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_assignments'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Asignaciones</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.teams') }}">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="search" class="form-label">Buscar equipo</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            value="{{ request('search') }}" placeholder="Nombre o descripción...">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="min_members" class="form-label">Mínimo miembros</label>
                                        <input type="number" class="form-control" id="min_members" name="min_members"
                                            value="{{ request('min_members') }}" placeholder="0" min="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="has_projects" class="form-label">Con proyectos</label>
                                        <select class="form-select" id="has_projects" name="has_projects">
                                            <option value="">Todos</option>
                                            <option value="1" {{ request('has_projects') === '1' ? 'selected' : '' }}>Con proyectos</option>
                                            <option value="0" {{ request('has_projects') === '0' ? 'selected' : '' }}>Sin proyectos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="bi bi-search me-1"></i>Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de equipos expandidos -->
            @if($teams->count() > 0)
            @foreach($teams as $team)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Información principal del equipo -->
                        <div class="col-lg-3 col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="feature-icon secondary me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $team->name }}</h5>
                                    <p class="mb-0 text-muted small">{{ Str::limit($team->description ?: 'Sin descripción disponible', 60) }}</p>
                                    <small class="text-muted">ID: {{ $team->id }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="col-lg-2 col-md-3">
                            <div class="small">
                                <div class="mb-1">
                                    <strong>Creado:</strong><br>
                                    <span class="text-muted">{{ $team->created_at->format('d/m/Y') }}</span><br>
                                    <span class="text-muted">{{ $team->created_at->diffForHumans() }}</span>
                                </div>
                                @if($team->updated_at->ne($team->created_at))
                                <div>
                                    <strong>Actualizado:</strong><br>
                                    <span class="text-muted">{{ $team->updated_at->diffForHumans() }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estadísticas expandidas -->
                        <div class="col-lg-3 col-md-4">
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-primary">{{ $team->users_count ?? $team->users->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>Miembros
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-success">{{ $team->projects_count ?? $team->projects->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-kanban me-1"></i>Proyectos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-info">{{ $team->modules_count ?? $team->modules->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-grid me-1"></i>Módulos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Miembros preview expandido -->
                        <div class="col-lg-3 col-md-6">
                            <div>
                                <h6 class="fw-bold small mb-2 text-warning">
                                    <i class="bi bi-people me-1"></i>Miembros ({{ $team->users->count() }})
                                </h6>
                                @if($team->users->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($team->users->take(4) as $user)
                                    <span class="badge bg-primary small">
                                        {{ Str::limit($user->name, 12) }}
                                        @if(isset($user->pivot->role))
                                        <small class="opacity-75">({{ $user->pivot->role }})</small>
                                        @endif
                                    </span>
                                    @endforeach
                                    @if($team->users->count() > 4)
                                    <span class="badge bg-secondary small">+{{ $team->users->count() - 4 }} más</span>
                                    @endif
                                </div>
                                @else
                                <p class="text-muted small mb-0">Sin miembros asignados</p>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="col-lg-1 col-md-2 text-end">
                            <div class="btn-group-vertical d-grid gap-2" role="group">
                                <a href="{{ route('admin.teams.edit', $team) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteTeamModal{{ $team->id }}">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sección expandida de miembros detallados -->
                    @if($team->users->count() > 0)
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-12">
                            <h6 class="fw-bold text-warning mb-3">
                                <i class="bi bi-people-fill me-2"></i>Miembros Detallados ({{ $team->users->count() }})
                            </h6>
                            <div class="row g-2">
                                @foreach($team->users as $user)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="feature-icon primary me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold small">{{ $user->name }}</div>
                                                    <div class="text-muted small">{{ Str::limit($user->email, 20) }}</div>
                                                    <div class="mt-1">
                                                        @if(isset($user->pivot->role))
                                                        <span class="badge bg-primary small">{{ $user->pivot->role }}</span>
                                                        @endif
                                                        @if(isset($user->pivot->is_active))
                                                        @if($user->pivot->is_active)
                                                        <span class="badge bg-success small">Activo</span>
                                                        @else
                                                        <span class="badge bg-secondary small">Inactivo</span>
                                                        @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Sección de módulos si existen -->
                    @if(isset($team->modules) && $team->modules->count() > 0)
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-12">
                            <h6 class="fw-bold text-warning mb-2">
                                <i class="bi bi-grid me-2"></i>Módulos Asignados ({{ $team->modules->count() }})
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($team->modules->take(8) as $module)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-{{ $module->category === 'DEVELOPMENT' ? 'code' : ($module->category === 'DESIGN' ? 'palette' : 'gear') }} me-1"></i>
                                    {{ $module->name }}
                                    @if($module->is_core)
                                    <span class="badge bg-danger ms-1" style="font-size: 0.7em;">CORE</span>
                                    @endif
                                </span>
                                @endforeach
                                @if($team->modules->count() > 8)
                                <span class="badge bg-secondary">
                                    +{{ $team->modules->count() - 8 }} más
                                </span>
                                @endif
                            </div>

                            <!-- Información adicional de módulos -->
                            @if($team->modules->count() > 0)
                            <div class="row g-2 mt-2">
                                <div class="col-auto">
                                    <small class="text-muted">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $team->modules->where('priority', 'URGENT')->count() }} urgentes
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <small class="text-muted">
                                        <i class="bi bi-check-circle me-1"></i>
                                        {{ $team->modules->where('status', 'DONE')->count() }} completados
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <small class="text-muted">
                                        <i class="bi bi-play-circle me-1"></i>
                                        {{ $team->modules->where('status', 'ACTIVE')->count() }} activos
                                    </small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Modal de confirmación de eliminación -->
            <div class="modal fade" id="deleteTeamModal{{ $team->id }}" tabindex="-1">
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
                            <p>¿Estás seguro de que quieres eliminar el equipo <strong>{{ $team->name }}</strong>?</p>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Esta acción es irreversible.</strong> Se perderán todas las asignaciones del equipo.
                            </div>
                            @if($team->users->count() > 0)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Este equipo tiene <strong>{{ $team->users->count() }} miembro(s)</strong> asignado(s).
                            </div>
                            @endif
                            @if(isset($team->projects) && $team->projects->count() > 0)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Este equipo está asignado a <strong>{{ $team->projects->count() }} proyecto(s)</strong>.
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" action="{{ route('admin.teams.delete', $team) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>Eliminar Equipo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $teams->appends(request()->query())->links() }}
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-diagram-3 display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron equipos</h5>
                    <p class="text-muted">Ajusta los filtros de búsqueda o crea nuevos equipos</p>
                    <a href="{{ route('admin.teams.create') }}" class="btn btn-warning mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Crear tu Primer Equipo
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
@endsection