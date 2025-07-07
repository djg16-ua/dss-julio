@extends('layouts.app')

@section('title', 'Gestionar Proyectos - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-success">
                        <i class="bi bi-kanban me-3"></i>Gestión de Proyectos
                    </h1>
                    <p class="lead text-muted">
                        Administra todos los proyectos del sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                    </a>
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

            <!-- Tabla de proyectos -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-table text-success me-2"></i>
                                Lista de Proyectos
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if($projects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Proyecto</th>
                                                <th>Creador</th>
                                                <th>Estado</th>
                                                <th>Visibilidad</th>
                                                <th>Fechas</th>
                                                <th>Estadísticas</th>
                                                <th width="150">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projects as $project)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $project->title }}</h6>
                                                        <p class="text-muted small mb-0">{{ Str::limit($project->description, 80) }}</p>
                                                        <small class="text-muted">ID: {{ $project->id }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="feature-icon primary me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                            <i class="bi bi-person-fill"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 small">{{ $project->creator->name ?? 'Usuario eliminado' }}</h6>
                                                            <small class="text-muted">{{ $project->creator->email ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
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
                                                </td>
                                                <td>
                                                    @if($project->public)
                                                        <span class="badge bg-info">
                                                            <i class="bi bi-globe me-1"></i>Público
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-lock me-1"></i>Privado
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <div class="text-muted">
                                                            <i class="bi bi-calendar-plus me-1"></i>
                                                            Creado: {{ $project->created_at->format('d/m/Y') }}
                                                        </div>
                                                        @if($project->start_date)
                                                        <div class="text-muted">
                                                            <i class="bi bi-play me-1"></i>
                                                            Inicio: {{ $project->start_date->format('d/m/Y') }}
                                                        </div>
                                                        @endif
                                                        @if($project->end_date)
                                                        <div class="text-muted">
                                                            <i class="bi bi-stop me-1"></i>
                                                            Fin: {{ $project->end_date->format('d/m/Y') }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <div class="text-muted">
                                                            <i class="bi bi-people me-1"></i>{{ $project->teams->count() }} equipos
                                                        </div>
                                                        <div class="text-muted">
                                                            <i class="bi bi-grid me-1"></i>{{ $project->modules->count() }} módulos
                                                        </div>
                                                        <div class="text-muted">
                                                            <i class="bi bi-check-square me-1"></i>
                                                            {{ $project->modules->sum(function($module) { return $module->tasks->count(); }) }} tareas
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-outline-success btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#projectModal{{ $project->id }}">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteProjectModal{{ $project->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal de detalles del proyecto -->
                                            <div class="modal fade" id="projectModal{{ $project->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-kanban me-2"></i>
                                                                {{ $project->title }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold">Información del Proyecto</h6>
                                                                    <table class="table table-sm">
                                                                        <tr>
                                                                            <td><strong>Título:</strong></td>
                                                                            <td>{{ $project->title }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Descripción:</strong></td>
                                                                            <td>{{ $project->description ?: 'Sin descripción' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Estado:</strong></td>
                                                                            <td>
                                                                                <span class="badge bg-{{ $project->status === 'ACTIVE' ? 'success' : ($project->status === 'PENDING' ? 'warning' : 'secondary') }}">
                                                                                    {{ $project->status }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Visibilidad:</strong></td>
                                                                            <td>
                                                                                <span class="badge bg-{{ $project->public ? 'info' : 'secondary' }}">
                                                                                    <i class="bi bi-{{ $project->public ? 'globe' : 'lock' }} me-1"></i>
                                                                                    {{ $project->public ? 'Público' : 'Privado' }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Creador:</strong></td>
                                                                            <td>{{ $project->creator->name ?? 'Usuario eliminado' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Creado:</strong></td>
                                                                            <td>{{ $project->created_at->format('d/m/Y H:i') }}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold">Equipos Asignados</h6>
                                                                    @if($project->teams->count() > 0)
                                                                        <div class="list-group list-group-flush">
                                                                            @foreach($project->teams as $team)
                                                                            <div class="list-group-item border-0 px-0 py-2">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="feature-icon secondary me-2" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                                                                        <i class="bi bi-people"></i>
                                                                                    </div>
                                                                                    <div>
                                                                                        <small class="fw-bold">{{ $team->name }}</small>
                                                                                        <br><small class="text-muted">{{ $team->description }}</small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="text-muted">Sin equipos asignados</p>
                                                                    @endif

                                                                    <h6 class="fw-bold mt-3">Módulos</h6>
                                                                    @if($project->modules->count() > 0)
                                                                        <div class="list-group list-group-flush">
                                                                            @foreach($project->modules as $module)
                                                                            <div class="list-group-item border-0 px-0 py-1">
                                                                                <small class="fw-bold">{{ $module->name }}</small>
                                                                                <small class="text-muted d-block">{{ $module->category }} - {{ $module->priority }}</small>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="text-muted">Sin módulos creados</p>
                                                                    @endif
                                                                </div>
                                                            </div>
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
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginación -->
                                <div class="card-footer bg-white">
                                    {{ $projects->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-kanban display-1 text-muted mb-3"></i>
                                    <h5 class="text-muted">No se encontraron proyectos</h5>
                                    <p class="text-muted">Ajusta los filtros de búsqueda</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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