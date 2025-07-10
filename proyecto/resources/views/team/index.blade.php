@extends('layouts.app')

@section('title', 'Equipos de ' . $project->title)

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
                            <h1 class="display-5 fw-bold text-primary mb-0">
                                <i class="bi bi-people me-3"></i>Equipos del Proyecto
                            </h1>
                            <p class="text-muted mb-0">{{ $project->title }}</p>
                        </div>
                    </div>
                    <p class="lead text-muted">
                        Gestiona todos los equipos especializados del proyecto
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('team.create', $project) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Equipo
                    </a>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-diagram-3 text-primary mb-3" style="font-size: 2.5rem;"></i>
                            <h3 class="fw-bold text-primary">{{ $teams->count() }}</h3>
                            <p class="text-muted mb-0">Total Equipos</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-people text-success mb-3" style="font-size: 2.5rem;"></i>
                            <h3 class="fw-bold text-primary">{{ $teams->sum(function($team) { return $team->users->where('pivot.is_active', true)->count(); }) }}</h3>
                            <p class="text-muted mb-0">Total Miembros</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-collection text-secondary mb-3" style="font-size: 2.5rem;"></i>
                            <h3 class="fw-bold text-primary">{{ $teams->sum(function($team) { return $team->modules->count(); }) }}</h3>
                            <p class="text-muted mb-0">Módulos Asignados</p>
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
                                   placeholder="Nombre o descripción del equipo..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="module" class="form-label fw-bold">Módulo Asignado</label>
                            <select class="form-select" id="module" name="module">
                                <option value="">Todos los módulos</option>
                                @foreach($project->modules as $module)
                                    <option value="{{ $module->id }}" {{ request('module') == $module->id ? 'selected' : '' }}>
                                        {{ $module->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="member_count" class="form-label fw-bold">Miembros</label>
                            <select class="form-select" id="member_count" name="member_count">
                                <option value="">Cualquier cantidad</option>
                                <option value="1" {{ request('member_count') == '1' ? 'selected' : '' }}>1 miembro</option>
                                <option value="2-5" {{ request('member_count') == '2-5' ? 'selected' : '' }}>2-5 miembros</option>
                                <option value="6-10" {{ request('member_count') == '6-10' ? 'selected' : '' }}>6-10 miembros</option>
                                <option value="11+" {{ request('member_count') == '11+' ? 'selected' : '' }}>11+ miembros</option>
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

            <!-- Lista de equipos -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul text-primary me-2"></i>
                        Lista de Equipos (<span id="teams-count">{{ $teams->count() }}</span> equipos)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="teams-container">
                        @if($teams->count() > 0)
                            <div class="row g-3">
                                @foreach($teams as $team)
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="card border h-100 team-card" 
                                             onclick="window.location.href='{{ route('team.show', [$project, $team]) }}'"
                                             style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                                             data-team-id="{{ $team->id }}"
                                             data-member-count="{{ $team->users->where('pivot.is_active', true)->count() }}"
                                             data-modules="{{ $team->modules->pluck('id')->join(',') }}">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-people-fill text-primary" style="font-size: 1.8rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">{{ $team->name }}</h6>
                                                        @if($team->description)
                                                            <p class="text-muted small mb-2">{{ Str::limit($team->description, 80) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Información del equipo -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <small class="text-muted">
                                                            <i class="bi bi-person-check me-1"></i>
                                                            {{ $team->users->where('pivot.is_active', true)->count() }} miembros
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="bi bi-collection me-1"></i>
                                                            {{ $team->modules->count() }} módulos
                                                        </small>
                                                    </div>
                                                    
                                                    @if($team->modules->count() > 0)
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block mb-1">
                                                                <i class="bi bi-folder me-1"></i>Módulos asignados:
                                                            </small>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($team->modules->take(3) as $module)
                                                                    <span class="badge bg-light text-dark">{{ $module->name }}</span>
                                                                @endforeach
                                                                @if($team->modules->count() > 3)
                                                                    <span class="badge bg-secondary">+{{ $team->modules->count() - 3 }} más</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Miembros del equipo -->
                                                @if($team->users->where('pivot.is_active', true)->count() > 0)
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block mb-2">
                                                            <i class="bi bi-people me-1"></i>Miembros:
                                                        </small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($team->users->where('pivot.is_active', true)->take(4) as $member)
                                                                <div class="d-flex align-items-center bg-light rounded px-2 py-1">
                                                                    <div class="avatar-circle-sm me-1">
                                                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                                                    </div>
                                                                    <small class="text-dark">{{ explode(' ', $member->name)[0] }}</small>
                                                                </div>
                                                            @endforeach
                                                            @if($team->users->where('pivot.is_active', true)->count() > 4)
                                                                <div class="d-flex align-items-center bg-secondary rounded px-2 py-1">
                                                                    <small class="text-white">+{{ $team->users->where('pivot.is_active', true)->count() - 4 }}</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $team->created_at->format('d/m/Y') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $team->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5" id="no-teams-message">
                                <div class="mb-3">
                                    <i class="bi bi-people-fill display-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted">No hay equipos disponibles</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'module', 'member_count']))
                                        No se encontraron equipos que coincidan con los filtros aplicados.
                                        <button class="btn btn-link p-0" id="clear-filters-link">Limpiar filtros</button>
                                    @else
                                        Este proyecto aún no tiene equipos personalizados.
                                        <a href="{{ route('team.create', $project) }}" class="text-decoration-none">Crea el primer equipo</a>
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
// Función para filtrar equipos
function filterTeams() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const selectedModule = document.getElementById('module').value;
    const memberCountFilter = document.getElementById('member_count').value;
    
    const teamCards = document.querySelectorAll('.team-card');
    let visibleCount = 0;
    
    teamCards.forEach(card => {
        let isVisible = true;
        
        // Filtro por texto (nombre/descripción)
        if (searchTerm) {
            const teamName = card.querySelector('h6').textContent.toLowerCase();
            const teamDescription = card.querySelector('.text-muted.small');
            const descriptionText = teamDescription ? teamDescription.textContent.toLowerCase() : '';
            
            if (!teamName.includes(searchTerm) && !descriptionText.includes(searchTerm)) {
                isVisible = false;
            }
        }
        
        // Filtro por módulo
        if (selectedModule && isVisible) {
            const teamModules = card.dataset.modules;
            if (!teamModules.includes(selectedModule)) {
                isVisible = false;
            }
        }
        
        // Filtro por cantidad de miembros
        if (memberCountFilter && isVisible) {
            const memberCount = parseInt(card.dataset.memberCount);
            
            switch(memberCountFilter) {
                case '1':
                    if (memberCount !== 1) isVisible = false;
                    break;
                case '2-5':
                    if (memberCount < 2 || memberCount > 5) isVisible = false;
                    break;
                case '6-10':
                    if (memberCount < 6 || memberCount > 10) isVisible = false;
                    break;
                case '11+':
                    if (memberCount < 11) isVisible = false;
                    break;
            }
        }
        
        // Mostrar/ocultar card
        if (isVisible) {
            card.closest('.col-lg-6').style.display = 'block';
            visibleCount++;
        } else {
            card.closest('.col-lg-6').style.display = 'none';
        }
    });
    
    // Actualizar contador
    document.getElementById('teams-count').textContent = visibleCount;
    
    // Mostrar/ocultar mensaje de no encontrados
    const container = document.getElementById('teams-container');
    const noTeamsMsg = document.getElementById('no-teams-message');
    
    if (visibleCount === 0 && !noTeamsMsg) {
        container.innerHTML = `
            <div class="text-center py-5" id="no-teams-message">
                <div class="mb-3">
                    <i class="bi bi-funnel display-1 text-muted"></i>
                </div>
                <h5 class="text-muted">No se encontraron equipos</h5>
                <p class="text-muted">
                    No hay equipos que coincidan con los filtros aplicados.
                    <button class="btn btn-link p-0" id="clear-filters-link">Limpiar filtros</button>
                </p>
            </div>
        `;
        
        // Re-añadir event listener
        document.getElementById('clear-filters-link').addEventListener('click', clearFilters);
    }
}

// Función para limpiar filtros
function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('module').value = '';
    document.getElementById('member_count').value = '';
    filterTeams();
    
    // Si hay equipos, restaurar vista original
    if (document.querySelectorAll('.team-card').length > 0) {
        location.reload();
    }
}

// Event listeners
document.getElementById('filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    filterTeams();
});

// Filtros automáticos
document.getElementById('module').addEventListener('change', filterTeams);
document.getElementById('member_count').addEventListener('change', filterTeams);

// Búsqueda con debounce
let searchTimeout;
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(filterTeams, 300);
});

// Limpiar filtros
document.getElementById('clear-filters').addEventListener('click', clearFilters);

// Efectos hover para las cards
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .team-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .avatar-circle-sm {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #4e73df;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 10px;
            flex-shrink: 0;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
@endsection