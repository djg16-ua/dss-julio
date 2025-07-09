@extends('layouts.app')

@section('title', 'Crear Proyecto')

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
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-plus-circle me-3"></i>Crear Nuevo Proyecto
                    </h1>
                    <p class="lead text-muted">
                        Completa la información para crear un nuevo proyecto
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('project.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Proyectos
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle text-primary me-2"></i>Información del Proyecto
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

                    <form method="POST" action="{{ route('project.store') }}">
                        @csrf
                        
                        <!-- Información básica -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">
                                        Título del Proyecto <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="Ej. E-commerce Platform"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="public" class="form-label fw-bold">Privacidad</label>
                                    <select class="form-select @error('public') is-invalid @enderror" 
                                            id="public" 
                                            name="public">
                                        <option value="0" {{ old('public') == '0' ? 'selected' : '' }}>
                                            🔒 Privado (Solo equipos asignados)
                                        </option>
                                        <option value="1" {{ old('public') == '1' ? 'selected' : '' }}>
                                            🌍 Público (Visible para todos)
                                        </option>
                                    </select>
                                    @error('public')
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
                                      placeholder="Describe el objetivo y alcance del proyecto...">{{ old('description') }}</textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Proporciona una descripción clara que ayude a los miembros del equipo a entender el proyecto.
                            </div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fechas -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label fw-bold">Fecha de Inicio</label>
                                    <input type="date" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label fw-bold">Fecha de Finalización</label>
                                    <input type="date" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Miembros del proyecto -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Miembros del Proyecto <span class="text-muted">(Opcional)</span>
                            </label>
                            <div class="form-text mb-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Se creará automáticamente un equipo "General" que incluirá a todos los miembros del proyecto. Puedes añadir miembros adicionales ahora o invitarlos más tarde.
                            </div>
                            
                            <!-- Lista de usuarios disponibles para invitar -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-person-plus me-2"></i>Añadir miembros al proyecto
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="member-search" placeholder="Buscar usuarios por nombre o email...">
                                    </div>
                                    
                                    <div id="available-users" class="row g-2">
                                        <!-- Aquí se cargarían los usuarios disponibles via AJAX -->
                                        <div class="col-12 text-center text-muted py-3">
                                            <i class="bi bi-search me-2"></i>
                                            Usa el buscador para encontrar usuarios y añadirlos al proyecto
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

                        <!-- Equipos adicionales -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Equipos Adicionales <span class="text-muted">(Opcional)</span>
                            </label>
                            <div class="form-text mb-3">
                                <i class="bi bi-people me-1"></i>
                                Crea equipos adicionales para organizar mejor el trabajo. Estos equipos se crearán vacíos y podrás asignar miembros más tarde.
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-plus-square me-2"></i>Crear equipos para el proyecto
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="teams-container">
                                        <!-- Los equipos aparecerán aquí -->
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-team-btn">
                                        <i class="bi bi-plus me-2"></i>Añadir Equipo
                                    </button>
                                    
                                    <div class="form-text mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Los equipos se crearán vacíos. Podrás asignar miembros después de crear el proyecto.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('project.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>Crear Proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedMembers = [];
let teamCounter = 0;

// Opciones de roles disponibles
const roleOptions = {
    'SENIOR_DEV': { label: '🚀 Senior Developer', description: 'Desarrollador senior' },
    'DEVELOPER': { label: '💻 Developer', description: 'Desarrollador' },
    'JUNIOR_DEV': { label: '🌱 Junior Developer', description: 'Desarrollador junior' },
    'DESIGNER': { label: '🎨 Designer', description: 'Diseñador' },
    'TESTER': { label: '🧪 Tester', description: 'Tester/QA' },
    'ANALYST': { label: '📊 Analyst', description: 'Analista' },
    'OBSERVER': { label: '👀 Observer', description: 'Observador' }
};

// Validación de fechas
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    
    if (startDate) {
        endDateInput.min = startDate;
        if (endDateInput.value && endDateInput.value < startDate) {
            endDateInput.value = '';
        }
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = this.value;
    const startDateInput = document.getElementById('start_date');
    
    if (endDate) {
        startDateInput.max = endDate;
        if (startDateInput.value && startDateInput.value > endDate) {
            startDateInput.value = '';
        }
    }
});

// Búsqueda de usuarios
let searchTimeout;
document.getElementById('member-search').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    
    clearTimeout(searchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('available-users').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-search me-2"></i>
                Escribe al menos 2 caracteres para buscar usuarios
            </div>
        `;
        return;
    }
    
    searchTimeout = setTimeout(() => {
        searchUsers(searchTerm);
    }, 300);
});

function searchUsers(term) {
    document.getElementById('available-users').innerHTML = `
        <div class="col-12 text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <span class="ms-2">Buscando usuarios...</span>
        </div>
    `;
    
    // Petición AJAX real a la base de datos
    fetch(`/project-search-users?term=${encodeURIComponent(term)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(users => {
        displayUsers(users.filter(user => !selectedMembers.find(member => member.id === user.id)));
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('available-users').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al buscar usuarios
            </div>
        `;
    });
}

function displayUsers(users) {
    const container = document.getElementById('available-users');
    
    if (users.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-person-x me-2"></i>
                No se encontraron usuarios disponibles
            </div>
        `;
        return;
    }
    
    container.innerHTML = users.map(user => `
        <div class="col-md-6 mb-2">
            <div class="card border">
                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                    <div>
                        <strong>${user.name}</strong><br>
                        <small class="text-muted">${user.email}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addMember(${user.id}, '${user.name}', '${user.email}')">
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
    if (searchTerm) {
        searchUsers(searchTerm);
    }
}

function removeMember(id) {
    selectedMembers = selectedMembers.filter(member => member.id !== id);
    updateSelectedMembers();
    
    // Refrescar búsqueda si hay término
    const searchTerm = document.getElementById('member-search').value;
    if (searchTerm) {
        searchUsers(searchTerm);
    }
}

function updateMemberRole(id, newRole) {
    const member = selectedMembers.find(m => m.id === id);
    if (member) {
        member.role = newRole;
        
        // Actualizar el input hidden correspondiente
        const memberCard = document.querySelector(`[data-member-id="${id}"]`);
        if (memberCard) {
            const hiddenInput = memberCard.querySelector('.member-combined');
            if (hiddenInput) {
                hiddenInput.value = `${id}|${newRole}`;
            }
        }
        
        // Actualizar descripción del rol
        const roleSelect = memberCard.querySelector('.member-role-select');
        const description = memberCard.querySelector('.text-muted');
        if (description) {
            description.textContent = roleOptions[newRole].description;
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
    
    // Actualizar vista - cada miembro tendrá su propio input hidden
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
                            <select class="form-select form-select-sm member-role-select" onchange="updateMemberRole(${member.id}, this.value)">
                                ${roleOptionsHtml}
                            </select>
                            <small class="text-muted">${roleOptions[member.role].description}</small>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMember(${member.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Input hidden para este miembro específico -->
                    <input type="hidden" class="member-combined" name="additional_members[]" value="${member.id}|${member.role}">
                </div>
            </div>
        `;
    }).join('');
}

// Gestión de equipos adicionales
document.getElementById('add-team-btn').addEventListener('click', function() {
    addTeamInput();
});

function addTeamInput() {
    teamCounter++;
    const container = document.getElementById('teams-container');
    
    const teamDiv = document.createElement('div');
    teamDiv.className = 'mb-3 team-input';
    teamDiv.innerHTML = `
        <div class="row g-2">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-people"></i>
                    </span>
                    <input type="text" class="form-control team-name" placeholder="Nombre del equipo" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-card-text"></i>
                    </span>
                    <input type="text" class="form-control team-description" placeholder="Descripción (opcional)">
                    <button type="button" class="btn btn-outline-danger" onclick="removeTeamInput(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" class="team-combined" name="additional_teams[]" value="">
    `;
    
    container.appendChild(teamDiv);
    
    // Añadir event listeners para combinar nombre y descripción
    const nameInput = teamDiv.querySelector('.team-name');
    const descInput = teamDiv.querySelector('.team-description');
    const hiddenInput = teamDiv.querySelector('.team-combined');
    
    function updateHiddenInput() {
        const name = nameInput.value.trim();
        const description = descInput.value.trim();
        
        if (name) {
            // Combinar nombre y descripción en un string separado por "|"
            hiddenInput.value = description ? `${name}|${description}` : name;
        } else {
            hiddenInput.value = '';
        }
    }
    
    nameInput.addEventListener('input', updateHiddenInput);
    descInput.addEventListener('input', updateHiddenInput);
}

function removeTeamInput(button) {
    button.closest('.team-input').remove();
}
</script>
@endpush

<style>
.form-check {
    padding: 0.75rem;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    transition: all 0.15s ease-in-out;
}

.form-check:hover {
    background-color: #f8f9fc;
    border-color: #4e73df;
}

.form-check-input:checked ~ .form-check-label {
    color: #4e73df;
    font-weight: 600;
}

.card {
    border: 1px solid #e3e6f0;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
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
</style>
@endsection