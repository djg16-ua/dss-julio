@extends('layouts.app')

@section('title', 'Editar Módulo - ' . $module->name . ' - TaskFlow')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('module.show', ['project' => $project, 'module' => $module]) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary">
                                <i class="bi bi-pencil-square me-3"></i>Editar Módulo
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-kanban me-1"></i>
                                <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                    {{ $project->title }}
                                </a>
                                <span class="mx-2">→</span>
                                <span class="fw-bold">{{ $module->name }}</span>
                            </p>
                        </div>
                    </div>
                    <p class="lead text-muted">
                        Modifica la información del módulo y gestiona sus equipos asignados
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('module.show', ['project' => $project, 'module' => $module]) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Módulo
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle text-primary me-2"></i>Información del Módulo
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Errores encontrados:</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('module.update', ['project' => $project, 'module' => $module]) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Información básica -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label fw-bold">
                                                Nombre del Módulo <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $module->name) }}" 
                                                   placeholder="Ej. Sistema de Autenticación"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="priority" class="form-label fw-bold">
                                                Prioridad <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('priority') is-invalid @enderror" 
                                                    id="priority" 
                                                    name="priority" 
                                                    required>
                                                <option value="URGENT" {{ old('priority', $module->priority) == 'URGENT' ? 'selected' : '' }}>🚨 Urgente</option>
                                                <option value="HIGH" {{ old('priority', $module->priority) == 'HIGH' ? 'selected' : '' }}>⚡ Alta</option>
                                                <option value="MEDIUM" {{ old('priority', $module->priority) == 'MEDIUM' ? 'selected' : '' }}>📋 Media</option>
                                                <option value="LOW" {{ old('priority', $module->priority) == 'LOW' ? 'selected' : '' }}>📝 Baja</option>
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label fw-bold">
                                                Estado <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status" 
                                                    required>
                                                <option value="PENDING" {{ old('status', $module->status) == 'PENDING' ? 'selected' : '' }}>⏳ Pendiente</option>
                                                <option value="ACTIVE" {{ old('status', $module->status) == 'ACTIVE' ? 'selected' : '' }}>✅ Activo</option>
                                                <option value="DONE" {{ old('status', $module->status) == 'DONE' ? 'selected' : '' }}>🎉 Completado</option>
                                                <option value="PAUSED" {{ old('status', $module->status) == 'PAUSED' ? 'selected' : '' }}>⏸️ Pausado</option>
                                                <option value="CANCELLED" {{ old('status', $module->status) == 'CANCELLED' ? 'selected' : '' }}>❌ Cancelado</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Información del Módulo</label>
                                            <div class="d-flex align-items-center">
                                                <div class="me-4">
                                                    <small class="text-muted">Creado:</small><br>
                                                    <span class="fw-bold">{{ $module->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <div class="me-4">
                                                    <small class="text-muted">Tareas:</small><br>
                                                    <span class="fw-bold">{{ $module->tasks->count() }}</span>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Equipos:</small><br>
                                                    <span class="fw-bold">{{ $module->teams->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Describe el propósito y funcionalidades del módulo...">{{ old('description', $module->description) }}</textarea>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Proporciona una descripción clara del módulo y sus responsabilidades.
                                    </div>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tipo de módulo -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tipo de Módulo</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-card">
                                                <input class="form-check-input" type="radio" name="is_core" id="module_core" value="1" {{ old('is_core', $module->is_core) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="module_core">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-star-fill text-warning me-3" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Módulo Core</strong>
                                                            <div class="text-muted small">Componente esencial del proyecto</div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-card">
                                                <input class="form-check-input" type="radio" name="is_core" id="module_standard" value="0" {{ old('is_core', $module->is_core) == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="module_standard">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-circle text-secondary me-3" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <strong>Módulo Estándar</strong>
                                                            <div class="text-muted small">Componente opcional del proyecto</div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Equipos asignados -->
                                @if(Auth::user()->id === $project->created_by || Auth::user()->role === 'ADMIN')
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Equipos Asignados <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <div class="form-text mb-3">
                                        <i class="bi bi-people me-1"></i>
                                        Gestiona los equipos asignados a este módulo. Los cambios se aplicarán al actualizar el módulo.
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-people-fill me-2"></i>Gestión de equipos del módulo
                                                </h6>
                                                <span class="badge bg-primary" id="selected-teams-count">{{ $module->teams->count() }} equipo(s)</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Equipos actualmente asignados -->
                                            <div class="mb-3">
                                                <h6 class="fw-bold text-primary mb-2">Equipos Actualmente Asignados:</h6>
                                                <div id="current-assigned-teams" class="mb-3">
                                                    @if($module->teams->count() > 0)
                                                        <div class="row g-2">
                                                            @foreach($module->teams as $team)
                                                                <div class="col-md-6">
                                                                    <div class="card border-primary" data-current-team-id="{{ $team->id }}">
                                                                        <div class="card-body p-2">
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <div class="flex-grow-1">
                                                                                    <div class="d-flex align-items-center mb-1">
                                                                                        <i class="bi bi-people me-2 text-primary"></i>
                                                                                        <strong>{{ $team->name }}</strong>
                                                                                        @if($team->is_general)
                                                                                            <span class="badge bg-warning ms-2">General</span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <small class="text-muted">{{ $team->description ?: 'Sin descripción' }}</small><br>
                                                                                    <small class="text-muted">
                                                                                        <i class="bi bi-person-check me-1"></i>
                                                                                        {{ $team->users->where('pivot.is_active', true)->count() }} miembro(s)
                                                                                    </small>
                                                                                </div>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTeamFromSelection({{ $team->id }})">
                                                                                    <i class="bi bi-x"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center text-muted py-3">
                                                            <i class="bi bi-people-plus me-2"></i>
                                                            No hay equipos asignados actualmente
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Búsqueda y asignación de nuevos equipos -->
                                            <div class="border-top pt-3">
                                                <div class="mb-3">
                                                    <input type="text" class="form-control" id="team-search-form" placeholder="Buscar equipos del proyecto para asignar...">
                                                </div>
                                                
                                                <div id="available-teams-form" class="row g-2">
                                                    <div class="col-12 text-center text-muted py-3">
                                                        <i class="bi bi-search me-2"></i>
                                                        Usa el buscador para encontrar equipos disponibles
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Equipos seleccionados para asignar -->
                                            <div id="teams-to-assign" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-success mb-3">Equipos pendientes de asignar:</h6>
                                                <div id="teams-to-assign-list">
                                                    <!-- Equipos seleccionados aparecerán aquí -->
                                                </div>
                                            </div>

                                            <!-- Equipos seleccionados para desasignar -->
                                            <div id="teams-to-remove" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-danger mb-3">Equipos pendientes de desasignar:</h6>
                                                <div id="teams-to-remove-list">
                                                    <!-- Equipos para remover aparecerán aquí -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('module.show', ['project' => $project, 'module' => $module]) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check me-2"></i>Actualizar Módulo
                                    </button>
                                </div>

                                <!-- Inputs ocultos para equipos -->
                                <div id="teams-inputs">
                                    @foreach($module->teams as $team)
                                        <input type="hidden" name="teams[]" value="{{ $team->id }}" data-team-input="{{ $team->id }}">
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// GESTIÓN DE EQUIPOS EN EL FORMULARIO
let teamSearchTimeout;
let currentTeams = @json($module->teams->pluck('id')->toArray()); // IDs de equipos actualmente asignados
let selectedTeams = [...currentTeams]; // Copia para manejar selecciones
let teamsToAdd = []; // Equipos nuevos para agregar
let teamsToRemove = []; // Equipos para remover

@if(Auth::user()->id === $project->created_by || Auth::user()->role === 'ADMIN')
// Búsqueda de equipos para asignar en el formulario
document.getElementById('team-search-form').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    
    clearTimeout(teamSearchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('available-teams-form').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-search me-2"></i>
                Escribe al menos 2 caracteres para buscar equipos
            </div>
        `;
        return;
    }
    
    teamSearchTimeout = setTimeout(() => {
        searchAvailableTeamsForForm(searchTerm);
    }, 300);
});

function searchAvailableTeamsForForm(term) {
    document.getElementById('available-teams-form').innerHTML = `
        <div class="col-12 text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <span class="ms-2">Buscando equipos...</span>
        </div>
    `;
    
    const url = `/project/{{ $project->id }}/modules/{{ $module->id }}/available-teams?search=${encodeURIComponent(term)}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(teams => {
        displayAvailableTeamsForForm(teams);
    })
    .catch(error => {
        console.error('Error búsqueda equipos:', error);
        document.getElementById('available-teams-form').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al buscar equipos: ${error.message}
            </div>
        `;
    });
}

function displayAvailableTeamsForForm(teams) {
    const container = document.getElementById('available-teams-form');
    
    // Filtrar equipos que no están ya seleccionados
    const availableTeams = teams.filter(team => !selectedTeams.includes(team.id));
    
    if (availableTeams.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-people-plus me-2"></i>
                No se encontraron equipos disponibles para asignar
            </div>
        `;
        return;
    }
    
    container.innerHTML = availableTeams.map(team => `
        <div class="col-md-6 mb-2">
            <div class="card border">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <strong>${escapeHtml(team.name)}</strong>
                                ${team.is_general ? '<span class="badge bg-warning ms-2">General</span>' : ''}
                            </div>
                            <small class="text-muted">${escapeHtml(team.description || 'Sin descripción')}</small><br>
                            <small class="text-muted">
                                <i class="bi bi-people me-1"></i>
                                ${team.members_count} miembro(s)
                            </small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="addTeamToSelection(${team.id}, '${escapeHtml(team.name)}', '${escapeHtml(team.description || '')}', ${team.members_count}, ${team.is_general})">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Función para escapar HTML
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Agregar equipo a la selección (pendiente de asignar)
function addTeamToSelection(teamId, teamName, teamDescription, membersCount, isGeneral) {
    // Agregar a la lista de seleccionados
    selectedTeams.push(teamId);
    
    // Si estaba marcado para remover, quitarlo de esa lista
    teamsToRemove = teamsToRemove.filter(id => id !== teamId);
    
    // Si no estaba en los equipos originales, agregarlo a la lista de nuevos
    if (!currentTeams.includes(teamId)) {
        teamsToAdd.push(teamId);
    }
    
    updateTeamsDisplay();
    updateHiddenInputs();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('team-search-form').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchAvailableTeamsForForm(searchTerm);
    }
}

// Remover equipo de la selección
function removeTeamFromSelection(teamId) {
    // Quitar de la lista de seleccionados
    selectedTeams = selectedTeams.filter(id => id !== teamId);
    
    // Si estaba marcado para agregar, quitarlo de esa lista
    teamsToAdd = teamsToAdd.filter(id => id !== teamId);
    
    // Si estaba en los equipos originales, agregarlo a la lista de removidos
    if (currentTeams.includes(teamId)) {
        teamsToRemove.push(teamId);
    }
    
    updateTeamsDisplay();
    updateHiddenInputs();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('team-search-form').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchAvailableTeamsForForm(searchTerm);
    }
}

// Actualizar la visualización de equipos
function updateTeamsDisplay() {
    updateTeamsToAssignDisplay();
    updateTeamsToRemoveDisplay();
    updateSelectedTeamsCount();
}

// Mostrar equipos pendientes de asignar
function updateTeamsToAssignDisplay() {
    const container = document.getElementById('teams-to-assign');
    const listContainer = document.getElementById('teams-to-assign-list');
    
    if (teamsToAdd.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    // Aquí deberías obtener la información de los equipos por sus IDs
    // Para simplificar, mostraré solo los IDs
    listContainer.innerHTML = teamsToAdd.map(teamId => `
        <div class="badge bg-success me-2 mb-2">
            <i class="bi bi-plus-circle me-1"></i>
            Equipo ID: ${teamId}
            <button type="button" class="btn-close btn-close-white ms-2" onclick="removeTeamFromSelection(${teamId})" style="font-size: 0.7em;"></button>
        </div>
    `).join('');
}

// Mostrar equipos pendientes de desasignar
function updateTeamsToRemoveDisplay() {
    const container = document.getElementById('teams-to-remove');
    const listContainer = document.getElementById('teams-to-remove-list');
    
    if (teamsToRemove.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = teamsToRemove.map(teamId => `
        <div class="badge bg-danger me-2 mb-2">
            <i class="bi bi-dash-circle me-1"></i>
            Equipo ID: ${teamId}
            <button type="button" class="btn-close btn-close-white ms-2" onclick="addTeamToSelection(${teamId}, 'Equipo ${teamId}', '', 0, false)" style="font-size: 0.7em;"></button>
        </div>
    `).join('');
}

// Actualizar contador de equipos seleccionados
function updateSelectedTeamsCount() {
    const badge = document.getElementById('selected-teams-count');
    badge.textContent = `${selectedTeams.length} equipo(s)`;
}

// Actualizar inputs ocultos para el formulario
function updateHiddenInputs() {
    const container = document.getElementById('teams-inputs');
    
    // Limpiar inputs existentes
    container.innerHTML = '';
    
    // Crear inputs para todos los equipos seleccionados
    selectedTeams.forEach(teamId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'teams[]';
        input.value = teamId;
        input.setAttribute('data-team-input', teamId);
        container.appendChild(input);
    });
}

@endif
</script>
@endpush

<style>
.form-check-card {
    padding: 1rem;
    border: 2px solid #e3e6f0;
    border-radius: 0.5rem;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
}

.form-check-card:hover {
    background-color: #f8f9fc;
    border-color: #4e73df;
}

.form-check-card .form-check-input:checked ~ .form-check-label {
    color: #4e73df;
}

.form-check-card .form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.form-check-card:has(.form-check-input:checked) {
    background-color: rgba(78, 115, 223, 0.1);
    border-color: #4e73df;
}

.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.card {
    border: 1px solid #e3e6f0;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.feature-card {
    transition: all 0.3s;
}

.feature-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.team-assigned-card {
    transition: all 0.3s ease;
}

.team-assigned-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.15rem 1rem 0 rgba(58, 59, 69, 0.15);
}
</style>
@endsection