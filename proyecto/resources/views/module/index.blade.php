@extends('layouts.app')

@section('title', 'Módulos - ' . $project->title . ' - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('project.show', $project) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary">
                                <i class="bi bi-collection me-3"></i>Módulos
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-kanban me-1"></i>
                                <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                    {{ $project->title }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <p class="lead text-muted">
                        Gestiona todos los módulos del proyecto
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('module.create', $project) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Módulo
                    </a>
                </div>
            </div>

            <!-- Estadísticas -->
            @php
                $moduleStats = [
                    'total_modules' => $modules->count(),
                    'active_modules' => $modules->where('status', 'ACTIVE')->count(),
                    'pending_modules' => $modules->where('status', 'PENDING')->count(),
                    'completed_modules' => $modules->where('status', 'DONE')->count(),
                    'core_modules' => $modules->where('is_core', true)->count(),
                    'standard_modules' => $modules->where('is_core', false)->count(),
                ];
            @endphp
            
            <div class="row g-4 mb-5">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-collection"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $moduleStats['total_modules'] }}</h3>
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
                            <h3 class="fw-bold text-primary">{{ $moduleStats['active_modules'] }}</h3>
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
                            <h3 class="fw-bold text-primary">{{ $moduleStats['pending_modules'] }}</h3>
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
                            <h3 class="fw-bold text-primary">{{ $moduleStats['completed_modules'] }}</h3>
                            <p class="text-muted mb-0">Completados</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $moduleStats['core_modules'] }}</h3>
                            <p class="text-muted mb-0">Core</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $moduleStats['standard_modules'] }}</h3>
                            <p class="text-muted mb-0">Estándar</p>
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
                                   placeholder="Nombre o descripción..." value="{{ request('search') }}">
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
                            <label for="priority" class="form-label fw-bold">Prioridad</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="">Todas las prioridades</option>
                                <option value="URGENT" {{ request('priority') == 'URGENT' ? 'selected' : '' }}>🚨 Urgente</option>
                                <option value="HIGH" {{ request('priority') == 'HIGH' ? 'selected' : '' }}>⚡ Alta</option>
                                <option value="MEDIUM" {{ request('priority') == 'MEDIUM' ? 'selected' : '' }}>📋 Media</option>
                                <option value="LOW" {{ request('priority') == 'LOW' ? 'selected' : '' }}>📝 Baja</option>
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

            <!-- Lista de módulos -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul text-primary me-2"></i>
                        Lista de Módulos (<span id="module-count">{{ $modules->count() }}</span> módulos)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="modules-container">
                        @if($modules->count() > 0)
                            @php
                                // Ordenar módulos: primero por estado (ACTIVE, PENDING, resto), luego por prioridad
                                $statusOrder = ['ACTIVE' => 1, 'PENDING' => 2, 'DONE' => 3, 'PAUSED' => 4, 'CANCELLED' => 5];
                                $priorityOrder = ['URGENT' => 1, 'HIGH' => 2, 'MEDIUM' => 3, 'LOW' => 4];
                                
                                $sortedModules = $modules->sort(function($a, $b) use ($statusOrder, $priorityOrder) {
                                    $statusA = $statusOrder[$a->status] ?? 6;
                                    $statusB = $statusOrder[$b->status] ?? 6;
                                    
                                    if ($statusA !== $statusB) {
                                        return $statusA <=> $statusB;
                                    }
                                    
                                    $priorityA = $priorityOrder[$a->priority] ?? 5;
                                    $priorityB = $priorityOrder[$b->priority] ?? 5;
                                    
                                    return $priorityA <=> $priorityB;
                                });
                            @endphp
                            
                            <div class="row g-3">
                                @foreach($sortedModules as $module)
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="card border h-100 module-card" 
                                             onclick="window.location.href='{{ route('module.show', [$project, $module]) }}'"
                                             style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <h6 class="fw-bold mb-0 flex-grow-1 me-2">{{ $module->name }}</h6>
                                                    <div class="d-flex flex-column align-items-end gap-1">
                                                        @switch($module->status)
                                                            @case('ACTIVE')
                                                                <span class="badge bg-success">✅ Activo</span>
                                                                @break
                                                            @case('PENDING')
                                                                <span class="badge bg-warning">⏳ Pendiente</span>
                                                                @break
                                                            @case('DONE')
                                                                <span class="badge bg-info">🎉 Completado</span>
                                                                @break
                                                            @case('PAUSED')
                                                                <span class="badge bg-secondary">⏸️ Pausado</span>
                                                                @break
                                                            @case('CANCELLED')
                                                                <span class="badge bg-danger">❌ Cancelado</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-light text-dark">{{ $module->status }}</span>
                                                        @endswitch
                                                        
                                                        @switch($module->priority)
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
                                                
                                                @if($module->description)
                                                    <p class="text-muted small mb-3">{{ Str::limit($module->description, 100) }}</p>
                                                @endif
                                                
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <small class="text-muted fw-bold">
                                                            <i class="bi bi-check2-square me-1"></i>Tareas:
                                                        </small>
                                                        <small class="text-muted">
                                                            {{ $module->tasks->count() }} total
                                                        </small>
                                                    </div>
                                                    
                                                    @if($module->tasks->count() > 0)
                                                        <div class="row g-1">
                                                            <div class="col-4">
                                                                <small class="text-success">
                                                                    <i class="bi bi-check-circle-fill"></i> {{ $module->tasks->where('status', 'DONE')->count() }}
                                                                </small>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="text-primary">
                                                                    <i class="bi bi-play-circle-fill"></i> {{ $module->tasks->where('status', 'ACTIVE')->count() }}
                                                                </small>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="text-warning">
                                                                    <i class="bi bi-clock-fill"></i> {{ $module->tasks->where('status', 'PENDING')->count() }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        @if($module->is_core)
                                                            <i class="bi bi-star-fill text-warning me-1"></i>Módulo Core
                                                        @else
                                                            <i class="bi bi-circle me-1"></i>Módulo Estándar
                                                        @endif
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-people me-1"></i>
                                                        {{ $module->teams->count() }} equipo(s)
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
                                    <i class="bi bi-collection-plus display-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted">No hay módulos disponibles</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'status', 'priority']))
                                        No se encontraron módulos que coincidan con los filtros aplicados.
                                        <a href="{{ route('module.index', $project) }}" class="text-decoration-none">Limpiar filtros</a>
                                    @else
                                        Este proyecto aún no tiene módulos. 
                                        <a href="{{ route('module.create', $project) }}" class="text-decoration-none">Crea el primer módulo</a>
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

@push('scripts')
<script>
// Función para cargar módulos con AJAX
function loadModules(filters = {}) {
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
    document.getElementById('modules-container').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-3">Buscando módulos...</p>
        </div>
    `;
    
    // Realizar petición AJAX
    fetch(`{{ route('module.index', $project) }}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('modules-container').innerHTML = data.html;
        document.getElementById('module-count').textContent = data.count;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('modules-container').innerHTML = `
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                </div>
                <h5 class="text-danger">Error al cargar módulos</h5>
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
    
    loadModules(filters);
});

// Filtros automáticos al cambiar los selects
document.getElementById('status').addEventListener('change', function() {
    document.getElementById('filter-form').dispatchEvent(new Event('submit'));
});

document.getElementById('priority').addEventListener('change', function() {
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
    document.getElementById('priority').value = '';
    loadModules(); // Cargar todos los módulos
});

// Efectos hover para las cards
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .module-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .module-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});

const style = document.createElement('style');
style.textContent = `
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .module-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
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
`;
</script>
@endpush
@endsection