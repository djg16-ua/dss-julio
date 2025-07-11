@extends('layouts.app')

@section('title', 'Crear Módulo - ' . $project->title . ' - TaskFlow')

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
                        <a href="{{ route('module.index', $project) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary">
                                <i class="bi bi-plus-circle me-3"></i>Crear Nuevo Módulo
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
                        Completa la información para crear un nuevo módulo en el proyecto
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('module.index', $project) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Módulos
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

                            <form method="POST" action="{{ route('module.store', $project) }}">
                                @csrf
                                
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
                                                   value="{{ old('name') }}" 
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
                                                <option value="MEDIUM" {{ old('priority') == 'MEDIUM' ? 'selected' : '' }}>📋 Media</option>
                                                <option value="URGENT" {{ old('priority') == 'URGENT' ? 'selected' : '' }}>🚨 Urgente</option>
                                                <option value="HIGH" {{ old('priority') == 'HIGH' ? 'selected' : '' }}>⚡ Alta</option>
                                                <option value="LOW" {{ old('priority') == 'LOW' ? 'selected' : '' }}>📝 Baja</option>
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                              placeholder="Describe el propósito y funcionalidades del módulo...">{{ old('description') }}</textarea>
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
                                                <input class="form-check-input" type="radio" name="is_core" id="module_core" value="1" {{ old('is_core') == '1' ? 'checked' : '' }}>
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
                                                <input class="form-check-input" type="radio" name="is_core" id="module_standard" value="0" {{ old('is_core', '0') == '0' ? 'checked' : '' }}>
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
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Equipos Asignados <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <div class="form-text mb-3">
                                        <i class="bi bi-people me-1"></i>
                                        Asigna equipos específicos a este módulo. Solo se mostrarán equipos disponibles del proyecto.
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-people-fill me-2"></i>Asignar equipos al módulo
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="team-search" placeholder="Buscar equipos del proyecto...">
                                            </div>
                                            
                                            <div id="available-teams" class="row g-2">
                                                <!-- Aquí se cargarían los equipos disponibles via AJAX -->
                                                <div class="col-12 text-center text-muted py-3">
                                                    <i class="bi bi-search me-2"></i>
                                                    Usa el buscador para encontrar equipos del proyecto
                                                </div>
                                            </div>
                                            
                                            <div id="selected-teams" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-primary mb-3">Equipos seleccionados:</h6>
                                                <div id="selected-teams-list">
                                                    <!-- Equipos seleccionados aparecerán aquí -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tareas iniciales -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Tareas Iniciales <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <div class="form-text mb-3">
                                        <i class="bi bi-check2-square me-1"></i>
                                        Crea tareas iniciales para el módulo. Podrás asignar usuarios después de crear el módulo.
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-plus-square me-2"></i>Crear tareas para el módulo
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="tasks-container">
                                                <!-- Las tareas aparecerán aquí -->
                                            </div>
                                            
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-task-btn">
                                                <i class="bi bi-plus me-2"></i>Añadir Tarea
                                            </button>
                                            
                                            <div class="form-text mt-2">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Las tareas se crearán después de crear el módulo.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('module.index', $project) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check me-2"></i>Crear Módulo
                                    </button>
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
let selectedTeams = [];
let taskCounter = 0;
let moduleTasks = [];

// Estados y prioridades
const taskPriorities = {
    'URGENT': { label: '🚨 Urgente', class: 'bg-danger' },
    'HIGH': { label: '⚡ Alta', class: 'bg-warning' },
    'MEDIUM': { label: '📋 Media', class: 'bg-info' },
    'LOW': { label: '📝 Baja', class: 'bg-secondary' }
};

// BÚSQUEDA DE EQUIPOS
let teamSearchTimeout;
document.getElementById('team-search').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    
    clearTimeout(teamSearchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('available-teams').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-search me-2"></i>
                Escribe al menos 2 caracteres para buscar equipos
            </div>
        `;
        return;
    }
    
    teamSearchTimeout = setTimeout(() => {
        searchTeams(searchTerm);
    }, 300);
});

function searchTeams(term) {
    document.getElementById('available-teams').innerHTML = `
        <div class="col-12 text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <span class="ms-2">Buscando equipos...</span>
        </div>
    `;
    
    // RUTA CORREGIDA: Usar la nueva ruta específica para crear módulos
    const url = `/project/{{ $project->id }}/modules/create/available-teams?search=${encodeURIComponent(term)}`;
    
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
        displayTeams(teams.filter(team => !selectedTeams.find(selected => selected.id === team.id)));
    })
    .catch(error => {
        console.error('Error búsqueda equipos:', error);
        document.getElementById('available-teams').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al buscar equipos: ${error.message}
            </div>
        `;
    });
}

function displayTeams(teams) {
    const container = document.getElementById('available-teams');
    
    if (teams.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-people-plus me-2"></i>
                No se encontraron equipos disponibles
            </div>
        `;
        return;
    }
    
    container.innerHTML = teams.map(team => `
        <div class="col-md-6 mb-2">
            <div class="card border">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <strong>${team.name}</strong>
                                ${team.is_general ? '<span class="badge bg-warning ms-2">General</span>' : ''}
                            </div>
                            <small class="text-muted">${team.description || 'Sin descripción'}</small><br>
                            <small class="text-muted">
                                <i class="bi bi-people me-1"></i>
                                ${team.members_count} miembro(s)
                            </small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="addTeam(${team.id}, '${team.name}', '${team.description || ''}', ${team.members_count}, ${team.is_general})">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function addTeam(id, name, description, membersCount, isGeneral) {
    if (selectedTeams.find(team => team.id === id)) return;
    
    selectedTeams.push({ id, name, description, membersCount, isGeneral });
    updateSelectedTeams();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('team-search').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchTeams(searchTerm);
    }
}

function removeTeam(id) {
    selectedTeams = selectedTeams.filter(team => team.id !== id);
    updateSelectedTeams();
    
    // Refrescar búsqueda si hay término
    const searchTerm = document.getElementById('team-search').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchTeams(searchTerm);
    }
}

function updateSelectedTeams() {
    const container = document.getElementById('selected-teams');
    const listContainer = document.getElementById('selected-teams-list');
    
    if (selectedTeams.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = selectedTeams.map(team => `
        <div class="card mb-2 team-card" data-team-id="${team.id}">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-people me-2 text-primary"></i>
                            <strong>${team.name}</strong>
                            ${team.isGeneral ? '<span class="badge bg-warning ms-2">General</span>' : ''}
                        </div>
                        <small class="text-muted">${team.description || 'Sin descripción'}</small><br>
                        <small class="text-muted">
                            <i class="bi bi-person-check me-1"></i>
                            ${team.membersCount} miembro(s)
                        </small>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTeam(${team.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="teams[]" value="${team.id}">
            </div>
        </div>
    `).join('');
}

// GESTIÓN DE TAREAS
document.getElementById('add-task-btn').addEventListener('click', function() {
    addTaskInput();
});

function addTaskInput() {
    taskCounter++;
    const container = document.getElementById('tasks-container');
    
    const taskDiv = document.createElement('div');
    taskDiv.className = 'mb-3 task-input';
    taskDiv.dataset.taskId = taskCounter;
    taskDiv.innerHTML = `
        <div class="card border">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Título de la tarea</label>
                        <input type="text" class="form-control task-title" placeholder="Ej. Configurar autenticación" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Prioridad</label>
                        <select class="form-select task-priority">
                            <option value="MEDIUM">📋 Media</option>
                            <option value="URGENT">🚨 Urgente</option>
                            <option value="HIGH">⚡ Alta</option>
                            <option value="LOW">📝 Baja</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="removeTaskInput(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <label class="form-label fw-bold small">Descripción</label>
                        <textarea class="form-control task-description" rows="2" placeholder="Describe los detalles de la tarea..."></textarea>
                    </div>
                </div>
                <input type="hidden" class="task-combined" name="tasks[]" value="">
            </div>
        </div>
    `;
    
    container.appendChild(taskDiv);
    
    // Añadir event listeners para combinar los datos
    const titleInput = taskDiv.querySelector('.task-title');
    const prioritySelect = taskDiv.querySelector('.task-priority');
    const descriptionTextarea = taskDiv.querySelector('.task-description');
    const hiddenInput = taskDiv.querySelector('.task-combined');
    
    function updateHiddenInput() {
        const title = titleInput.value.trim();
        const priority = prioritySelect.value;
        const description = descriptionTextarea.value.trim();
        
        if (title) {
            // Combinar datos en JSON string
            const taskData = {
                title: title,
                priority: priority,
                description: description
            };
            hiddenInput.value = JSON.stringify(taskData);
        } else {
            hiddenInput.value = '';
        }
    }
    
    titleInput.addEventListener('input', updateHiddenInput);
    prioritySelect.addEventListener('change', updateHiddenInput);
    descriptionTextarea.addEventListener('input', updateHiddenInput);
}

function removeTaskInput(button) {
    button.closest('.task-input').remove();
}

// Validación del formulario antes de enviar
document.querySelector('form').addEventListener('submit', function(e) {
    // Validar que al menos un equipo esté seleccionado si hay tareas
    const taskInputs = document.querySelectorAll('.task-input');
    const hasValidTasks = Array.from(taskInputs).some(taskDiv => {
        const title = taskDiv.querySelector('.task-title').value.trim();
        return title.length > 0;
    });
    
    if (hasValidTasks && selectedTeams.length === 0) {
        e.preventDefault();
        alert('Si vas a crear tareas, debes asignar al menos un equipo al módulo para poder asignar usuarios a las tareas.');
        return false;
    }
    
    // Limpiar tareas vacías
    taskInputs.forEach(taskDiv => {
        const title = taskDiv.querySelector('.task-title').value.trim();
        if (!title) {
            taskDiv.remove();
        }
    });
});
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

.task-input {
    transition: all 0.3s ease;
}

.task-input:hover {
    transform: translateY(-1px);
}
</style>
@endsection