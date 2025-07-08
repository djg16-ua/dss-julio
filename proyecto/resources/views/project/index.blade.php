@extends('layouts.app')

@section('title', 'Mis Proyectos')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Mis Proyectos</h1>
            <p class="text-muted">Gestiona todos tus proyectos</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Proyecto
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_projects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_projects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_projects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_projects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Públicos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['public_projects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Privados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['private_projects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-lock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros de búsqueda</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('projects.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Título o descripción..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos los estados</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                        <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                        <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completado</option>
                        <option value="PAUSED" {{ request('status') == 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="public" class="form-label">Privacidad</label>
                    <select class="form-select" id="public" name="public">
                        <option value="">Todos</option>
                        <option value="1" {{ request('public') == '1' ? 'selected' : '' }}>Públicos</option>
                        <option value="0" {{ request('public') == '0' ? 'selected' : '' }}>Privados</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de proyectos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Lista de Proyectos ({{ $projects->count() }} proyectos)
            </h6>
        </div>
        <div class="card-body">
            @if($projects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Estado</th>
                                <th>Privacidad</th>
                                <th>Creador</th>
                                <th>Fechas</th>
                                <th>Equipos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $project->title }}</strong>
                                            @if($project->description)
                                                <br>
                                                <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @switch($project->status)
                                            @case('ACTIVE')
                                                <span class="badge bg-success">Activo</span>
                                                @break
                                            @case('PENDING')
                                                <span class="badge bg-warning">Pendiente</span>
                                                @break
                                            @case('COMPLETED')
                                                <span class="badge bg-info">Completado</span>
                                                @break
                                            @case('PAUSED')
                                                <span class="badge bg-secondary">Pausado</span>
                                                @break
                                            @case('CANCELLED')
                                                <span class="badge bg-danger">Cancelado</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ $project->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($project->public)
                                            <span class="badge bg-primary">
                                                <i class="fas fa-globe me-1"></i>Público
                                            </span>
                                        @else
                                            <span class="badge bg-dark">
                                                <i class="fas fa-lock me-1"></i>Privado
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-title rounded-circle bg-light text-dark">
                                                    {{ strtoupper(substr($project->creator->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $project->creator->name }}</strong>
                                                @if($project->creator->id === auth()->id())
                                                    <small class="text-primary">(Tú)</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            @if($project->start_date)
                                                <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}<br>
                                            @endif
                                            @if($project->end_date)
                                                <strong>Fin:</strong> {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}<br>
                                            @endif
                                            <strong>Creado:</strong> {{ $project->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $projectTeams = $project->teams ?? collect();
                                        @endphp
                                        @if($projectTeams->count() > 0)
                                            @foreach($projectTeams->take(2) as $team)
                                                <span class="badge bg-light text-dark mb-1">{{ $team->name }}</span><br>
                                            @endforeach
                                            @if($projectTeams->count() > 2)
                                                <small class="text-muted">+{{ $projectTeams->count() - 2 }} más</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Sin equipos</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('projects.show', $project) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Ver proyecto">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('projects.edit', $project) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Editar proyecto">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($project->created_by === auth()->id())
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        title="Eliminar proyecto"
                                                        onclick="confirmDelete('{{ $project->id }}', '{{ $project->title }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-project-diagram fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No hay proyectos disponibles</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'status', 'public']))
                            No se encontraron proyectos que coincidan con los filtros aplicados.
                            <a href="{{ route('projects.index') }}" class="text-decoration-none">Limpiar filtros</a>
                        @else
                            Aún no formas parte de ningún proyecto. 
                            <a href="{{ route('projects.create') }}" class="text-decoration-none">Crea tu primer proyecto</a>
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar el proyecto <strong id="projectToDelete"></strong>?
                <br><br>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Advertencia:</strong> Esta acción no se puede deshacer. Se eliminarán todos los módulos, tareas y comentarios asociados.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar proyecto</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(projectId, projectTitle) {
    document.getElementById('projectToDelete').textContent = projectTitle;
    document.getElementById('deleteForm').action = `/projects/${projectId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Aplicar filtros automáticamente al cambiar los selects
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('public').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush

<style>
.avatar {
    width: 2rem;
    height: 2rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.875rem;
    font-weight: 600;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}

.border-left-dark {
    border-left: 0.25rem solid #5a5c69 !important;
}

.card {
    border: 1px solid #e3e6f0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    background-color: #f8f9fc;
}

.btn-group .btn {
    border-radius: 0.35rem;
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection