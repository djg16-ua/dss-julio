@extends('layouts.app')

@section('title', 'Gestionar Módulos - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4 align-items-center">
                <div class="col-lg-6 col-md-8">
                    <h1 class="display-5 fw-bold text-warning mb-2">
                        <i class="bi bi-grid-3x3-gap me-3"></i>Gestión de Módulos
                    </h1>
                    <p class="lead text-muted mb-0">
                        Administra todos los módulos del sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-6 col-md-4">
                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                        </a>
                        <a href="{{ route('admin.modules.create') }}" class="btn btn-warning">
                            <i class="bi bi-plus-circle me-2"></i>Crear Módulo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de módulos -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-warning">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon warning mx-auto mb-3">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_modules'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Módulos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-success">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['active_modules'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Módulos Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-danger">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-exclamation-diamond"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['core_modules'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Módulos CORE</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card border-info">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['completed_modules'] ?? 0 }}</h3>
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
                            <form method="GET" action="{{ route('admin.modules') }}">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Buscar módulo</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            value="{{ request('search') }}" placeholder="Nombre o descripción...">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Estado</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">Todos</option>
                                            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="ACTIVE" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                            <option value="DONE" {{ request('status') === 'DONE' ? 'selected' : '' }}>Completado</option>
                                            <option value="PAUSED" {{ request('status') === 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                                            <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="category" class="form-label">Categoría</label>
                                        <select class="form-select" id="category" name="category">
                                            <option value="">Todas</option>
                                            <option value="DEVELOPMENT" {{ request('category') === 'DEVELOPMENT' ? 'selected' : '' }}>Desarrollo</option>
                                            <option value="DESIGN" {{ request('category') === 'DESIGN' ? 'selected' : '' }}>Diseño</option>
                                            <option value="TESTING" {{ request('category') === 'TESTING' ? 'selected' : '' }}>Pruebas</option>
                                            <option value="DOCUMENTATION" {{ request('category') === 'DOCUMENTATION' ? 'selected' : '' }}>Documentación</option>
                                            <option value="RESEARCH" {{ request('category') === 'RESEARCH' ? 'selected' : '' }}>Investigación</option>
                                            <option value="DEPLOYMENT" {{ request('category') === 'DEPLOYMENT' ? 'selected' : '' }}>Despliegue</option>
                                            <option value="MAINTENANCE" {{ request('category') === 'MAINTENANCE' ? 'selected' : '' }}>Mantenimiento</option>
                                            <option value="INTEGRATION" {{ request('category') === 'INTEGRATION' ? 'selected' : '' }}>Integración</option>
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
                                        <label for="is_core" class="form-label">Tipo</label>
                                        <select class="form-select" id="is_core" name="is_core">
                                            <option value="">Todos</option>
                                            <option value="1" {{ request('is_core') === '1' ? 'selected' : '' }}>CORE</option>
                                            <option value="0" {{ request('is_core') === '0' ? 'selected' : '' }}>Regular</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de módulos -->
            @if($modules->count() > 0)
            @foreach($modules as $module)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Información principal del módulo -->
                        <div class="col-lg-3 col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon warning me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="bi bi-{{ $module->category === 'DEVELOPMENT' ? 'code' : ($module->category === 'DESIGN' ? 'palette' : ($module->category === 'TESTING' ? 'bug' : 'gear')) }}"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <h5 class="mb-0 fw-bold me-2">{{ $module->name }}</h5>
                                        @if($module->is_core)
                                        <span class="badge bg-danger small">CORE</span>
                                        @endif
                                    </div>
                                    <p class="mb-1 text-muted small">{{ Str::limit($module->description, 80) }}</p>
                                    <div class="d-flex gap-1 mb-1">
                                        <span class="badge bg-light text-dark">{{ $module->category }}</span>
                                        <span class="badge bg-{{ $module->priority === 'URGENT' ? 'danger' : ($module->priority === 'HIGH' ? 'warning' : ($module->priority === 'MEDIUM' ? 'info' : 'secondary')) }}">
                                            {{ $module->priority }}
                                        </span>
                                    </div>
                                    <small class="text-muted">ID: {{ $module->id }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Proyecto y dependencias -->
                        <div class="col-lg-2 col-md-3">
                            <div class="small">
                                <div class="mb-2">
                                    <strong>Proyecto:</strong><br>
                                    <a href="{{ route('admin.projects.edit', $module->project) }}" class="text-decoration-none">
                                        {{ Str::limit($module->project->title, 25) }}
                                    </a>
                                </div>
                                @if($module->depends_on)
                                <div>
                                    <strong>Depende de:</strong><br>
                                    <a href="{{ route('admin.modules.edit', $module->dependency) }}" class="text-decoration-none small">
                                        {{ Str::limit($module->dependency->name, 20) }}
                                    </a>
                                </div>
                                @endif
                                @if($module->dependents->count() > 0)
                                <div class="mt-1">
                                    <strong>Dependientes:</strong><br>
                                    <small class="text-info">{{ $module->dependents->count() }} módulo(s)</small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estado y progreso -->
                        <div class="col-lg-2 col-md-3">
                            <div class="mb-2">
                                <form method="POST" action="{{ route('admin.modules.update-status', $module) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="PENDING" {{ $module->status === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="ACTIVE" {{ $module->status === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                        <option value="DONE" {{ $module->status === 'DONE' ? 'selected' : '' }}>Completado</option>
                                        <option value="PAUSED" {{ $module->status === 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                                        <option value="CANCELLED" {{ $module->status === 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </form>
                            </div>
                            @php
                            $progress = $module->tasks->count() > 0 ?
                            round(($module->tasks->where('status', 'DONE')->count() / $module->tasks->count()) * 100) : 0;
                            @endphp
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                            </div>
                            <small class="text-muted">{{ $progress }}% completado</small>
                        </div>

                        <!-- Estadísticas del módulo -->
                        <div class="col-lg-3 col-md-6">
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-primary">{{ $module->tasks->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-check-square me-1"></i>Tareas
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-success">{{ $module->tasks->where('status', 'DONE')->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-check-circle me-1"></i>Hechas
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card text-center bg-light">
                                        <div class="card-body p-2">
                                            <div class="fw-bold text-info">{{ $module->teams->count() }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>Equipos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                Creado: {{ $module->created_at->format('d/m/Y') }}
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="col-lg-2 col-md-2 text-end">
                            <div class="btn-group-vertical d-grid gap-2" role="group">
                                <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModuleModal{{ $module->id }}">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sección expandida de equipos asignados -->
                    @if($module->teams->count() > 0)
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-12">
                            <h6 class="fw-bold text-warning mb-2">
                                <i class="bi bi-people me-2"></i>Equipos Asignados ({{ $module->teams->count() }})
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($module->teams as $team)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-people me-1"></i>{{ $team->name }}
                                    <small>({{ $team->users->where('pivot.is_active', true)->count() }} miembros)</small>
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Modal de confirmación de eliminación -->
            <div class="modal fade" id="deleteModuleModal{{ $module->id }}" tabindex="-1">
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
                            <p>¿Estás seguro de que quieres eliminar el módulo <strong>{{ $module->name }}</strong>?</p>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Esta acción es irreversible.</strong> Se eliminarán todas las tareas asociadas.
                            </div>
                            @if($module->tasks->count() > 0)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Este módulo tiene <strong>{{ $module->tasks->count() }} tarea(s)</strong> asociada(s).
                            </div>
                            @endif
                            @if($module->dependents->count() > 0)
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <strong>Atención:</strong> {{ $module->dependents->count() }} módulo(s) dependen de este.
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" action="{{ route('admin.modules.delete', $module) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>Eliminar Módulo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $modules->appends(request()->query())->links() }}
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-grid-3x3-gap display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron módulos</h5>
                    <p class="text-muted">Ajusta los filtros de búsqueda o crea nuevos módulos</p>
                    <a href="{{ route('admin.modules.create') }}" class="btn btn-warning mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Crear tu Primer Módulo
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