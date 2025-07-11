@extends('layouts.app')

@section('title', 'Gestionar Proyectos - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4 align-items-center">
                <div class="col-lg-6 col-md-8">
                    <h1 class="display-5 fw-bold text-success mb-2">
                        <i class="bi bi-kanban me-3"></i>Gestión de Proyectos
                    </h1>
                    <p class="lead text-muted mb-0">
                        Administra todos los proyectos del sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-6 col-md-4">
                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                        </a>
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Crear Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de proyectos -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-success">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_projects'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Proyectos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-primary">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['active_projects'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Proyectos Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-info">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-globe"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['public_projects'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Proyectos Públicos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-warning">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['completed_projects'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Completados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.projects') }}">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="search" class="form-label">Buscar proyecto</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            value="{{ request('search') }}" placeholder="Título o descripción...">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Estado</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">Todos los estados</option>
                                            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="ACTIVE" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                            <option value="DONE" {{ request('status') === 'DONE' ? 'selected' : '' }}>Completado</option>
                                            <option value="PAUSED" {{ request('status') === 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                                            <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="public" class="form-label">Visibilidad</label>
                                        <select class="form-select" id="public" name="public">
                                            <option value="">Todos</option>
                                            <option value="1" {{ request('public') === '1' ? 'selected' : '' }}>Públicos</option>
                                            <option value="0" {{ request('public') === '0' ? 'selected' : '' }}>Privados</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-search me-1"></i>Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de proyectos expandidos -->
            @if($projects->count() > 0)
            @foreach($projects as $project)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Información principal del proyecto -->
                        <div class="col-lg-3 col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon success me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="bi bi-kanban"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $project->title }}</h5>
                                    <p class="mb-1 text-muted small">{{ Str::limit($project->description, 100) }}</p>
                                    <small class="text-muted">ID: {{ $project->id }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Creador -->
                        <div class="col-lg-2 col-md-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="feature-icon primary me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="small fw-bold">{{ $project->creator->name ?? 'Usuario eliminado' }}</div>
                                    <div class="small text-muted">{{ Str::limit($project->creator->email ?? 'N/A', 20) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado y visibilidad -->
                        <div class="col-lg-2 col-md-3">
                            <div class="mb-2">
                                <form method="POST" action="{{ route('admin.projects.update-status', $project) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="PENDING" {{ $project->status === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="ACTIVE" {{ $project->status === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                        <option value="DONE" {{ $project->status === 'DONE' ? 'selected' : '' }}>Completado</option>
                                        <option value="PAUSED" {{ $project->status === 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                                        <option value="CANCELLED" {{ $project->status === 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </form>
                            </div>
                            <div>
                                @if($project->public)
                                <span class="badge bg-info">
                                    <i class="bi bi-globe me-1"></i>Público
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-lock me-1"></i>Privado
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Fechas expandidas -->
                        <div class="col-lg-2 col-md-4">
                            <div class="small">
                                <div class="mb-1">
                                    <strong>Creado:</strong><br>
                                    <span class="text-muted">{{ $project->created_at->format('d/m/Y') }}</span><br>
                                    <span class="text-muted">{{ $project->created_at->diffForHumans() }}</span>
                                </div>
                                @if($project->start_date)
                                <div class="mb-1">
                                    <strong>Inicio:</strong><br>
                                    <span class="text-muted">{{ $project->start_date->format('d/m/Y') }}</span>
                                </div>
                                @endif
                                @if($project->end_date)
                                <div>
                                    <strong>Fin:</strong><br>
                                    <span class="text-muted">{{ $project->end_date->format('d/m/Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estadísticas expandidas -->
                        <div class="col-lg-2 col-md-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-primary">{{ $project->teams->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>Equipos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-success">{{ $project->modules->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-grid me-1"></i>Módulos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-info">
                                                {{ $project->modules->sum('tasks_count') ?: 0 }}                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-check-square me-1"></i>Tareas Total
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="col-lg-1 col-md-2 text-end">
                            <div class="btn-group-vertical d-grid gap-2" role="group">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteProjectModal{{ $project->id }}">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sección expandida de equipos y módulos -->
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-lg-6">
                            <h6 class="fw-bold text-success mb-2">
                                <i class="bi bi-people me-2"></i>Equipos Asignados ({{ $project->teams->count() }})
                            </h6>
                            @if($project->teams->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($project->teams as $team)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-people me-1"></i>{{ $team->name }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted small mb-0">Sin equipos asignados</p>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <h6 class="fw-bold text-success mb-2">
                                <i class="bi bi-grid me-2"></i>Módulos ({{ $project->modules->count() }})
                            </h6>
                            @if($project->modules->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($project->modules->take(5) as $module)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-{{ $module->category === 'DEVELOPMENT' ? 'code' : ($module->category === 'DESIGN' ? 'palette' : 'gear') }} me-1"></i>
                                    {{ $module->name }}
                                </span>
                                @endforeach
                                @if($project->modules->count() > 5)
                                <span class="badge bg-secondary">
                                    +{{ $project->modules->count() - 5 }} más
                                </span>
                                @endif
                            </div>
                            @else
                            <p class="text-muted small mb-0">Sin módulos creados</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación de eliminación -->
            <div class="modal fade" id="deleteProjectModal{{ $project->id }}" tabindex="-1">
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
                            <p>¿Estás seguro de que quieres eliminar el proyecto <strong>{{ $project->title }}</strong>?</p>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Esta acción es irreversible.</strong> Se eliminarán todos los módulos y tareas asociadas.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" action="{{ route('admin.projects.delete', $project) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>Eliminar Proyecto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $projects->appends(request()->query())->links() }}
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-kanban display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron proyectos</h5>
                    <p class="text-muted">Ajusta los filtros de búsqueda o crea nuevos proyectos</p>
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-success mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Crear tu Primer Proyecto
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