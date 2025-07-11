@extends('layouts.app')

@section('title', 'Crear Equipo - ' . $project->title . ' - TaskFlow')

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
                        <a href="{{ route('team.index', $project) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary">
                                <i class="bi bi-plus-circle me-3"></i>Crear Nuevo Equipo
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
                        Completa la información para crear un nuevo equipo en el proyecto
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('team.index', $project) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Equipos
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle text-primary me-2"></i>Información del Equipo
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

                            <form method="POST" action="{{ route('team.store', $project) }}">
                                @csrf
                                
                                <!-- Información básica -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label fw-bold">
                                                Nombre del Equipo <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name') }}" 
                                                   placeholder="Ej. Frontend Team"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="description" class="form-label fw-bold">Descripción</label>
                                            <input type="text" 
                                                   class="form-control @error('description') is-invalid @enderror" 
                                                   id="description" 
                                                   name="description" 
                                                   value="{{ old('description') }}" 
                                                   placeholder="Descripción breve del equipo"
                                                   maxlength="255">
                                            <div class="form-text">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Describe brevemente el propósito y responsabilidades del equipo.
                                            </div>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Miembros del equipo -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Miembros del Equipo <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <div class="form-text mb-3">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Solo puedes añadir usuarios que ya están en el proyecto. Puedes añadir miembros ahora o hacerlo más tarde.
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-person-plus me-2"></i>Añadir miembros al equipo
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="member-search" placeholder="Buscar miembros del proyecto...">
                                            </div>
                                            
                                            <div id="available-members" class="row g-2">
                                                <!-- Aquí se cargarían los miembros disponibles via AJAX -->
                                                <div class="col-12 text-center text-muted py-3">
                                                    <i class="bi bi-search me-2"></i>
                                                    Usa el buscador para encontrar miembros del proyecto
                                                </div>
                                            </div>
                                            
                                            <div id="selected-members" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-primary mb-3">Miembros seleccionados:</h6>
                                                <div id="selected-members-list">
                                                    <!-- Miembros seleccionados aparecerán aquí con sus roles -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Módulos asignados -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Módulos Asignados <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <div class="form-text mb-3">
                                        <i class="bi bi-collection me-1"></i>
                                        Asigna módulos específicos a este equipo. Solo se mostrarán módulos disponibles del proyecto.
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-collection-plus me-2"></i>Asignar módulos al equipo
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="module-search" placeholder="Buscar módulos del proyecto...">
                                            </div>
                                            
                                            <div id="available-modules" class="row g-2">
                                                <!-- Aquí se cargarían los módulos disponibles via AJAX -->
                                                <div class="col-12 text-center text-muted py-3">
                                                    <i class="bi bi-search me-2"></i>
                                                    Usa el buscador para encontrar módulos del proyecto
                                                </div>
                                            </div>
                                            
                                            <div id="selected-modules" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-primary mb-3">Módulos seleccionados:</h6>
                                                <div id="selected-modules-list">
                                                    <!-- Módulos seleccionados aparecerán aquí -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('team.index', $project) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check me-2"></i>Crear Equipo
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
let selectedMembers = [];
let selectedModules = [];

// Opciones de roles disponibles (sin LEAD ya que solo puede haberlo en el equipo general)
const roleOptions = {
    'SENIOR_DEV': { label: '🚀 Senior Developer', description: 'Desarrollador senior' },
    'DEVELOPER': { label: '💻 Developer', description: 'Desarrollador' },
    'JUNIOR_DEV': { label: '🌱 Junior Developer', description: 'Desarrollador junior' },
    'DESIGNER': { label: '🎨 Designer', description: 'Diseñador' },
    'TESTER': { label: '🧪 Tester', description: 'Tester/QA' },
    'ANALYST': { label: '📊 Analyst', description: 'Analista' },
    'OBSERVER': { label: '👀 Observer', description: 'Observador' }
};

// Estados y prioridades de módulos
const moduleStatuses = {
    'PENDING': { label: '⏳ Pendiente', class: 'bg-warning' },
    'ACTIVE': { label: '✅ Activo', class: 'bg-success' },
    'DONE': { label: '🎉 Completado', class: 'bg-info' },
    'PAUSED': { label: '⏸️ Pausado', class: 'bg-secondary' },
    'CANCELLED': { label: '❌ Cancelado', class: 'bg-danger' }
};

const modulePriorities = {
    'URGENT': { label: '🚨 Urgente', class: 'bg-danger' },
    'HIGH': { label: '⚡ Alta', class: 'bg-warning' },
    'MEDIUM': { label: '📋 Media', class: 'bg-info' },
    'LOW': { label: '📝 Baja', class: 'bg-secondary' }
};

// BÚSQUEDA DE MIEMBROS
let memberSearchTimeout;
document.getElementById('member-search').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    
    clearTimeout(memberSearchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('available-members').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-search me-2"></i>
                Escribe al menos 2 caracteres para buscar miembros
            </div>
        `;
        return;
    }
    
    memberSearchTimeout = setTimeout(() => {
        searchMembers(searchTerm);
    }, 300);
});

function searchMembers(term) {
    document.getElementById('available-members').innerHTML = `
        <div class="col-12 text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <span class="ms-2">Buscando miembros...</span>
        </div>
    `;
    
    const url = `{{ route('project.available-members', $project) }}?search=${encodeURIComponent(term)}`;
    
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
    .then(members => {
        displayMembers(members.filter(member => !selectedMembers.find(selected => selected.id === member.id)));
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('available-members').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al buscar miembros: ${error.message}
            </div>
        `;
    });
}

function displayMembers(members) {
    const container = document.getElementById('available-members');
    
    if (members.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-person-x me-2"></i>
                No se encontraron miembros disponibles
            </div>
        `;
        return;
    }
    
    container.innerHTML = members.map(member => `
        <div class="col-md-6 mb-2">
            <div class="card border">
                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                    <div>
                        <strong>${member.name}</strong><br>
                        <small class="text-muted">${member.email}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addMember(${member.id}, '${member.name}', '${member.email}')">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function addMember(id, name, email) {
    if (selectedMembers.find(member => member.id === id)) return;
    
    selectedMembers.push({ 
        id, 
        name, 
        email, 
        role: 'DEVELOPER' // Rol por defecto
    });
    updateSelectedMembers();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('member-search').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchMembers(searchTerm);
    }
}

function removeMember(id) {
    selectedMembers = selectedMembers.filter(member => member.id !== id);
    updateSelectedMembers();
    
    // Refrescar búsqueda si hay término
    const searchTerm = document.getElementById('member-search').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchMembers(searchTerm);
    }
}

function updateMemberRole(id, newRole) {
    const member = selectedMembers.find(m => m.id === id);
    if (member) {
        member.role = newRole;
        
        // Actualizar descripción del rol
        const memberCard = document.querySelector(`[data-member-id="${id}"]`);
        if (memberCard) {
            const description = memberCard.querySelector('.role-description');
            if (description) {
                description.textContent = roleOptions[newRole].description;
            }
        }
    }
}

function updateSelectedMembers() {
    const container = document.getElementById('selected-members');
    const listContainer = document.getElementById('selected-members-list');
    
    if (selectedMembers.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = selectedMembers.map((member, index) => {
        const roleOptionsHtml = Object.entries(roleOptions).map(([value, data]) => 
            `<option value="${value}" ${member.role === value ? 'selected' : ''}>${data.label}</option>`
        ).join('');
        
        return `
            <div class="card mb-2 member-card" data-member-id="${member.id}">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2">
                                    ${member.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <strong>${member.name}</strong><br>
                                    <small class="text-muted">${member.email}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select form-select-sm" onchange="updateMemberRole(${member.id}, this.value)">
                                ${roleOptionsHtml}
                            </select>
                            <small class="text-muted role-description">${roleOptions[member.role].description}</small>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMember(${member.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="members[]" value="${member.id}">
                    <input type="hidden" name="roles[]" value="${member.role}">
                </div>
            </div>
        `;
    }).join('');
}

// BÚSQUEDA DE MÓDULOS
let moduleSearchTimeout;
document.getElementById('module-search').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    
    clearTimeout(moduleSearchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('available-modules').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-search me-2"></i>
                Escribe al menos 2 caracteres para buscar módulos
            </div>
        `;
        return;
    }
    
    moduleSearchTimeout = setTimeout(() => {
        searchModules(searchTerm);
    }, 300);
});

function searchModules(term) {
    document.getElementById('available-modules').innerHTML = `
        <div class="col-12 text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <span class="ms-2">Buscando módulos...</span>
        </div>
    `;
    
    // Crear un equipo temporal con ID 0 para la búsqueda
    const url = `/project/{{ $project->id }}/team/0/available-modules?search=${encodeURIComponent(term)}`;
    
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
    .then(modules => {
        displayModules(modules.filter(module => !selectedModules.find(selected => selected.id === module.id)));
    })
    .catch(error => {
        console.error('Error búsqueda módulos:', error);
        document.getElementById('available-modules').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al buscar módulos: ${error.message}
            </div>
        `;
    });
}

function displayModules(modules) {
    const container = document.getElementById('available-modules');
    
    if (modules.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-collection-plus me-2"></i>
                No se encontraron módulos disponibles
            </div>
        `;
        return;
    }
    
    container.innerHTML = modules.map(module => `
        <div class="col-md-6 mb-2">
            <div class="card border">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <strong>${module.name}</strong>
                                <span class="badge ${moduleStatuses[module.status].class} ms-2">${moduleStatuses[module.status].label}</span>
                            </div>
                            <small class="text-muted">${module.description || 'Sin descripción'}</small><br>
                            <span class="badge ${modulePriorities[module.priority].class}">${modulePriorities[module.priority].label}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="addModule(${module.id}, '${module.name}', '${module.description || ''}', '${module.status}', '${module.priority}')">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function addModule(id, name, description, status, priority) {
    if (selectedModules.find(module => module.id === id)) return;
    
    selectedModules.push({ id, name, description, status, priority });
    updateSelectedModules();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('module-search').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchModules(searchTerm);
    }
}

function removeModule(id) {
    selectedModules = selectedModules.filter(module => module.id !== id);
    updateSelectedModules();
    
    // Refrescar búsqueda si hay término
    const searchTerm = document.getElementById('module-search').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchModules(searchTerm);
    }
}

function updateSelectedModules() {
    const container = document.getElementById('selected-modules');
    const listContainer = document.getElementById('selected-modules-list');
    
    if (selectedModules.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = selectedModules.map(module => `
        <div class="card mb-2 module-card" data-module-id="${module.id}">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-collection me-2 text-primary"></i>
                            <strong>${module.name}</strong>
                            <span class="badge ${moduleStatuses[module.status].class} ms-2">${moduleStatuses[module.status].label}</span>
                        </div>
                        <small class="text-muted">${module.description || 'Sin descripción'}</small><br>
                        <span class="badge ${modulePriorities[module.priority].class}">${modulePriorities[module.priority].label}</span>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeModule(${module.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="modules[]" value="${module.id}">
            </div>
        </div>
    `).join('');
}
</script>
@endpush

<style>
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

.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #4e73df;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
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
</style>
@endsection