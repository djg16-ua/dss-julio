@extends('layouts.app')

@section('title', 'Mis Proyectos')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-kanban me-3"></i>Mis Proyectos
                    </h1>
                    <p class="lead text-muted">
                        Gestiona todos tus proyectos de forma eficiente
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('project.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Proyecto
                    </a>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row g-4 mb-5">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_projects'] }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['active_projects'] }}</h3>
                            <p class="text-muted mb-0">Activos</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['pending_projects'] }}</h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['completed_projects'] }}</h3>
                            <p class="text-muted mb-0">Completados</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-globe"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['public_projects'] }}</h3>
                            <p class="text-muted mb-0">Públicos</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-lock"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['private_projects'] }}</h3>
                            <p class="text-muted mb-0">Privados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-funnel text-primary me-2"></i>
                        Filtros de búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <form id="filter-form" class="row g-3">
                        @csrf
                        <div class="col-md-4">
                            <label for="search" class="form-label fw-bold">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Título o descripción..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-bold">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos los estados</option>
                                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>✅ Activo</option>
                                <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>🎉 Completado</option>
                                <option value="PAUSED" {{ request('status') == 'PAUSED' ? 'selected' : '' }}>⏸️ Pausado</option>
                                <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>❌ Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="public" class="form-label fw-bold">Privacidad</label>
                            <select class="form-select" id="public" name="public">
                                <option value="">Todos</option>
                                <option value="1" {{ request('public') == '1' ? 'selected' : '' }}>🌍 Públicos</option>
                                <option value="0" {{ request('public') == '0' ? 'selected' : '' }}>🔒 Privados</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search me-1"></i>Buscar
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clear-filters" title="Limpiar filtros">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de proyectos -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul text-primary me-2"></i>
                        Lista de Proyectos (<span id="project-count">{{ $projects->count() }}</span> proyectos)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="projects-container">
                        @if($projects->count() > 0)
                            <div class="row g-3">
                                @foreach($projects as $project)
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="card border h-100 project-card" 
                                             onclick="window.location.href='{{ route('project.show', $project) }}'"
                                             style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <h6 class="fw-bold mb-0 flex-grow-1 me-2">{{ $project->title }}</h6>
                                                    <div class="d-flex flex-column align-items-end gap-1">
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
                                                        
                                                        @if($project->public)
                                                            <span class="badge bg-primary">
                                                                <i class="bi bi-globe me-1"></i>Público
                                                            </span>
                                                        @else
                                                            <span class="badge bg-dark">
                                                                <i class="bi bi-lock me-1"></i>Privado
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if($project->description)
                                                    <p class="text-muted small mb-3">{{ Str::limit($project->description, 100) }}</p>
                                                @endif
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-person me-1"></i>
                                                        {{ $project->creator->name }}
                                                        @if($project->creator->id === auth()->id())
                                                            <span class="text-primary">(Tú)</span>
                                                        @endif
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $project->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-folder-plus display-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted">No hay proyectos disponibles</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'status', 'public']))
                                        No se encontraron proyectos que coincidan con los filtros aplicados.
                                        <a href="{{ route('project.index') }}" class="text-decoration-none">Limpiar filtros</a>
                                    @else
                                        Aún no formas parte de ningún proyecto. 
                                        <a href="{{ route('project.create') }}" class="text-decoration-none">Crea tu primer proyecto</a>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
                    <i class="bi bi-exclamation-triangle me-2"></i>
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
// Función para cargar proyectos con AJAX
function loadProjects(filters = {}) {
    const params = new URLSearchParams();
    
    // Añadir filtros a los parámetros
    Object.keys(filters).forEach(key => {
        if (filters[key]) {
            params.append(key, filters[key]);
        }
    });
    
    // Añadir parámetro para indicar que es una petición AJAX
    params.append('ajax', '1');
    
    // Mostrar indicador de carga
    document.getElementById('projects-container').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-3">Buscando proyectos...</p>
        </div>
    `;
    
    // Realizar petición AJAX
    fetch(`{{ route('project.index') }}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('projects-container').innerHTML = data.html;
        document.getElementById('project-count').textContent = data.count;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('projects-container').innerHTML = `
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                </div>
                <h5 class="text-danger">Error al cargar proyectos</h5>
                <p class="text-muted">Inténtalo de nuevo más tarde</p>
            </div>
        `;
    });
}

// Manejar envío del formulario
document.getElementById('filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const filters = {};
    
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '' && key !== '_token') {
            filters[key] = value;
        }
    }
    
    loadProjects(filters);
});

// Filtros automáticos al cambiar los selects
document.getElementById('status').addEventListener('change', function() {
    document.getElementById('filter-form').dispatchEvent(new Event('submit'));
});

document.getElementById('public').addEventListener('change', function() {
    document.getElementById('filter-form').dispatchEvent(new Event('submit'));
});

// Filtro automático para búsqueda (con debounce)
let searchTimeout;
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filter-form').dispatchEvent(new Event('submit'));
    }, 500); // Esperar 500ms después de que el usuario deje de escribir
});

// Limpiar filtros
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search').value = '';
    document.getElementById('status').value = '';
    document.getElementById('public').value = '';
    loadProjects(); // Cargar todos los proyectos
});

// Efectos hover para las cards
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .project-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
@endsection