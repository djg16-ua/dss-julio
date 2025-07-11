@extends('layouts.app')

@section('title', 'Editar Equipo - ' . $team->name . ' - TaskFlow')

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
                        <a href="{{ route('team.show', ['project' => $project, 'team' => $team]) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary">
                                <i class="bi bi-pencil-square me-3"></i>Editar Equipo
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-kanban me-1"></i>
                                <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                    {{ $project->title }}
                                </a>
                                <span class="mx-2">→</span>
                                <span class="fw-bold">{{ $team->name }}</span>
                            </p>
                        </div>
                    </div>
                    <p class="lead text-muted">
                        Modifica la información del equipo y gestiona sus miembros y módulos asignados
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('team.show', ['project' => $project, 'team' => $team]) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Equipo
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

                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('team.update', ['project' => $project, 'team' => $team]) }}">
                                @csrf
                                @method('PUT')
                                
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
                                                   value="{{ old('name', $team->name) }}" 
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
                                                   value="{{ old('description', $team->description) }}" 
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

                                <!-- Información del equipo -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Información del Equipo</label>
                                            <div class="d-flex align-items-center">
                                                <div class="me-4">
                                                    <small class="text-muted">Creado:</small><br>
                                                    <span class="fw-bold">{{ $team->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <div class="me-4">
                                                    <small class="text-muted">Miembros:</small><br>
                                                    <span class="fw-bold">{{ $team->users->count() }}</span>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Módulos:</small><br>
                                                    <span class="fw-bold">{{ $team->modules->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Miembros del equipo -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Miembros del Equipo <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <div class="form-text mb-3">
                                        <i class="bi bi-people me-1"></i>
                                        Gestiona los miembros y roles de este equipo. Los cambios se aplicarán al actualizar el equipo.
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-person-plus me-2"></i>Gestión de miembros del equipo
                                                </h6>
                                                <span class="badge bg-primary" id="selected-members-count">{{ $team->users->count() }} miembro(s)</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Miembros actualmente asignados -->
                                            <div class="mb-3">
                                                <h6 class="fw-bold text-primary mb-2">Miembros Actuales:</h6>
                                                <div id="current-assigned-members" class="mb-3">
                                                    @if($team->users->count() > 0)
                                                        <div class="row g-2">
                                                            @foreach($team->users as $member)
                                                                <div class="col-md-6">
                                                                    <div class="card border-primary" data-current-member-id="{{ $member->id }}">
                                                                        <div class="card-body p-2">
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <div class="flex-grow-1">
                                                                                    <div class="d-flex align-items-center mb-1">
                                                                                        <div class="avatar-circle me-2">
                                                                                            {{ $member->name[0] }}
                                                                                        </div>
                                                                                        <div>
                                                                                            <strong>{{ $member->name }}</strong><br>
                                                                                            <small class="text-muted">{{ $member->email }}</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <small class="text-primary fw-bold">
                                                                                        {{ $member->pivot->role ?? 'DEVELOPER' }}
                                                                                    </small>
                                                                                </div>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMemberFromSelection({{ $member->id }})">
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
                                                            <i class="bi bi-person-plus me-2"></i>
                                                            No hay miembros asignados actualmente
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Búsqueda y asignación de nuevos miembros -->
                                            <div class="border-top pt-3">
                                                <div class="mb-3">
                                                    <input type="text" class="form-control" id="member-search-form" placeholder="Buscar miembros del proyecto para asignar...">
                                                </div>
                                                
                                                <div id="available-members-form" class="row g-2">
                                                    <div class="col-12 text-center text-muted py-3">
                                                        <i class="bi bi-search me-2"></i>
                                                        Usa el buscador para encontrar miembros disponibles
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Miembros seleccionados para asignar -->
                                            <div id="members-to-assign" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-success mb-3">Miembros pendientes de asignar:</h6>
                                                <div id="members-to-assign-list">
                                                    <!-- Miembros seleccionados aparecerán aquí -->
                                                </div>
                                            </div>

                                            <!-- Miembros seleccionados para desasignar -->
                                            <div id="members-to-remove" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-danger mb-3">Miembros pendientes de desasignar:</h6>
                                                <div id="members-to-remove-list">
                                                    <!-- Miembros para remover aparecerán aquí -->
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
                                        Gestiona los módulos asignados a este equipo. Los cambios se aplicarán al actualizar el equipo.
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-collection-plus me-2"></i>Gestión de módulos del equipo
                                                </h6>
                                                <span class="badge bg-primary" id="selected-modules-count">{{ $team->modules->count() }} módulo(s)</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Módulos actualmente asignados -->
                                            <div class="mb-3">
                                                <h6 class="fw-bold text-primary mb-2">Módulos Actuales:</h6>
                                                <div id="current-assigned-modules" class="mb-3">
                                                    @if($team->modules->count() > 0)
                                                        <div class="row g-2">
                                                            @foreach($team->modules as $module)
                                                                <div class="col-md-6">
                                                                    <div class="card border-primary" data-current-module-id="{{ $module->id }}">
                                                                        <div class="card-body p-2">
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <div class="flex-grow-1">
                                                                                    <div class="d-flex align-items-center mb-1">
                                                                                        <i class="bi bi-collection me-2 text-primary"></i>
                                                                                        <strong>{{ $module->name }}</strong>
                                                                                    </div>
                                                                                    <small class="text-muted">{{ $module->description ?: 'Sin descripción' }}</small><br>
                                                                                    <span class="badge bg-{{ $module->status === 'ACTIVE' ? 'success' : ($module->status === 'PENDING' ? 'warning' : 'secondary') }}">
                                                                                        {{ $module->status }}
                                                                                    </span>
                                                                                </div>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeModuleFromSelection({{ $module->id }})">
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
                                                            <i class="bi bi-collection-plus me-2"></i>
                                                            No hay módulos asignados actualmente
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Búsqueda y asignación de nuevos módulos -->
                                            <div class="border-top pt-3">
                                                <div class="mb-3">
                                                    <input type="text" class="form-control" id="module-search-form" placeholder="Buscar módulos del proyecto para asignar...">
                                                </div>
                                                
                                                <div id="available-modules-form" class="row g-2">
                                                    <div class="col-12 text-center text-muted py-3">
                                                        <i class="bi bi-search me-2"></i>
                                                        Usa el buscador para encontrar módulos disponibles
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Módulos seleccionados para asignar -->
                                            <div id="modules-to-assign" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-success mb-3">Módulos pendientes de asignar:</h6>
                                                <div id="modules-to-assign-list">
                                                    <!-- Módulos seleccionados aparecerán aquí -->
                                                </div>
                                            </div>

                                            <!-- Módulos seleccionados para desasignar -->
                                            <div id="modules-to-remove" class="mt-3" style="display: none;">
                                                <h6 class="fw-bold text-danger mb-3">Módulos pendientes de desasignar:</h6>
                                                <div id="modules-to-remove-list">
                                                    <!-- Módulos para remover aparecerán aquí -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('team.show', ['project' => $project, 'team' => $team]) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check me-2"></i>Actualizar Equipo
                                    </button>
                                </div>

                                <!-- Inputs ocultos para miembros -->
                                <div id="members-inputs">
                                    @foreach($team->users as $member)
                                        <input type="hidden" name="members[]" value="{{ $member->id }}" data-member-input="{{ $member->id }}">
                                        <input type="hidden" name="roles[]" value="{{ $member->pivot->role ?? 'DEVELOPER' }}" data-role-input="{{ $member->id }}">
                                    @endforeach
                                </div>

                                <!-- Inputs ocultos para módulos -->
                                <div id="modules-inputs">
                                    @foreach($team->modules as $module)
                                        <input type="hidden" name="modules[]" value="{{ $module->id }}" data-module-input="{{ $module->id }}">
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
// GESTIÓN DE MIEMBROS Y MÓDULOS EN EL FORMULARIO
let memberSearchTimeout;
let moduleSearchTimeout;

// Estados iniciales
let currentMembers = @json($team->users->pluck('id')->toArray());
let currentMemberRoles = @json($team->users->mapWithKeys(function($user) { return [$user->id => $user->pivot->role ?? 'DEVELOPER']; }));
let currentModules = @json($team->modules->pluck('id')->toArray());

// Estados de selección
let selectedMembers = [...currentMembers];
let selectedMemberRoles = {...currentMemberRoles};
let selectedModules = [...currentModules];

// Cambios pendientes
let membersToAdd = [];
let membersToRemove = [];
let modulesToAdd = [];
let modulesToRemove = [];

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

// BÚSQUEDA DE MIEMBROS
document.getElementById('member-search-form').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    
    clearTimeout(memberSearchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('available-members-form').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-search me-2"></i>
                Escribe al menos 2 caracteres para buscar miembros
            </div>
        `;
        return;
    }
    
    memberSearchTimeout = setTimeout(() => {
        searchAvailableMembersForForm(searchTerm);
    }, 300);
});

function searchAvailableMembersForForm(term) {
    document.getElementById('available-members-form').innerHTML = `
        <div class="col-12 text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <span class="ms-2">Buscando miembros...</span>
        </div>
    `;
    
    const url = `/project/{{ $project->id }}/team/{{ $team->id }}/edit/available-members?search=${encodeURIComponent(term)}`;
    
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
        displayAvailableMembersForForm(members);
    })
    .catch(error => {
        console.error('Error búsqueda miembros:', error);
        document.getElementById('available-members-form').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al buscar miembros: ${error.message}
            </div>
        `;
    });
}

function displayAvailableMembersForForm(members) {
    const container = document.getElementById('available-members-form');
    
    // Filtrar miembros que no están ya seleccionados
    const availableMembers = members.filter(member => !selectedMembers.includes(member.id));
    
    if (availableMembers.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-person-plus me-2"></i>
                No se encontraron miembros disponibles para asignar
            </div>
        `;
        return;
    }
    
    container.innerHTML = availableMembers.map(member => `
        <div class="col-md-6 mb-2">
            <div class="card border">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2">
                                    ${escapeHtml(member.name[0])}
                                </div>
                                <div>
                                    <strong>${escapeHtml(member.name)}</strong><br>
                                    <small class="text-muted">${escapeHtml(member.email)}</small>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="addMemberToSelection(${member.id}, '${escapeHtml(member.name)}', '${escapeHtml(member.email)}')">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// BÚSQUEDA DE MÓDULOS
document.getElementById('module-search-form').addEventListener('input', function() {
    const searchTerm = this.value.trim();
    
    clearTimeout(moduleSearchTimeout);
    
    if (searchTerm.length < 2) {
        document.getElementById('available-modules-form').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-search me-2"></i>
                Escribe al menos 2 caracteres para buscar módulos
            </div>
        `;
        return;
    }
    
    moduleSearchTimeout = setTimeout(() => {
        searchAvailableModulesForForm(searchTerm);
    }, 300);
});

function searchAvailableModulesForForm(term) {
    document.getElementById('available-modules-form').innerHTML = `
        <div class="col-12 text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <span class="ms-2">Buscando módulos...</span>
        </div>
    `;
    
    const url = `/project/{{ $project->id }}/team/{{ $team->id }}/edit/available-modules?search=${encodeURIComponent(term)}`;
    
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
        displayAvailableModulesForForm(modules);
    })
    .catch(error => {
        console.error('Error búsqueda módulos:', error);
        document.getElementById('available-modules-form').innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al buscar módulos: ${error.message}
            </div>
        `;
    });
}

function displayAvailableModulesForForm(modules) {
    const container = document.getElementById('available-modules-form');
    
    // Filtrar módulos que no están ya seleccionados
    const availableModules = modules.filter(module => !selectedModules.includes(module.id));
    
    if (availableModules.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-3">
                <i class="bi bi-collection-plus me-2"></i>
                No se encontraron módulos disponibles para asignar
            </div>
        `;
        return;
    }
    
    container.innerHTML = availableModules.map(module => `
        <div class="col-md-6 mb-2">
            <div class="card border">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-collection me-2 text-primary"></i>
                                <strong>${escapeHtml(module.name)}</strong>
                            </div>
                            <small class="text-muted">${escapeHtml(module.description || 'Sin descripción')}</small><br>
                            <span class="badge bg-${module.status === 'ACTIVE' ? 'success' : (module.status === 'PENDING' ? 'warning' : 'secondary')}">${module.status}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="addModuleToSelection(${module.id}, '${escapeHtml(module.name)}', '${escapeHtml(module.description || '')}', '${module.status}')">
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

// GESTIÓN DE MIEMBROS
function addMemberToSelection(memberId, memberName, memberEmail) {
    // Agregar a la lista de seleccionados
    selectedMembers.push(memberId);
    selectedMemberRoles[memberId] = 'DEVELOPER'; // Rol por defecto
    
    // Si estaba marcado para remover, quitarlo de esa lista
    membersToRemove = membersToRemove.filter(id => id !== memberId);
    
    // Si no estaba en los miembros originales, agregarlo a la lista de nuevos
    if (!currentMembers.includes(memberId)) {
        membersToAdd.push(memberId);
    }
    
    updateMembersDisplay();
    updateHiddenInputs();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('member-search-form').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchAvailableMembersForForm(searchTerm);
    }
}

function removeMemberFromSelection(memberId) {
    // Quitar de la lista de seleccionados
    selectedMembers = selectedMembers.filter(id => id !== memberId);
    delete selectedMemberRoles[memberId];
    
    // Si estaba marcado para agregar, quitarlo de esa lista
    membersToAdd = membersToAdd.filter(id => id !== memberId);
    
    // Si estaba en los miembros originales, agregarlo a la lista de removidos
    if (currentMembers.includes(memberId)) {
        membersToRemove.push(memberId);
    }
    
    updateMembersDisplay();
    updateHiddenInputs();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('member-search-form').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchAvailableMembersForForm(searchTerm);
    }
}

// GESTIÓN DE MÓDULOS
function addModuleToSelection(moduleId, moduleName, moduleDescription, moduleStatus) {
    // Agregar a la lista de seleccionados
    selectedModules.push(moduleId);
    
    // Si estaba marcado para remover, quitarlo de esa lista
    modulesToRemove = modulesToRemove.filter(id => id !== moduleId);
    
    // Si no estaba en los módulos originales, agregarlo a la lista de nuevos
    if (!currentModules.includes(moduleId)) {
        modulesToAdd.push(moduleId);
    }
    
    updateModulesDisplay();
    updateHiddenInputs();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('module-search-form').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchAvailableModulesForForm(searchTerm);
    }
}

function removeModuleFromSelection(moduleId) {
    // Quitar de la lista de seleccionados
    selectedModules = selectedModules.filter(id => id !== moduleId);
    
    // Si estaba marcado para agregar, quitarlo de esa lista
    modulesToAdd = modulesToAdd.filter(id => id !== moduleId);
    
    // Si estaba en los módulos originales, agregarlo a la lista de removidos
    if (currentModules.includes(moduleId)) {
        modulesToRemove.push(moduleId);
    }
    
    updateModulesDisplay();
    updateHiddenInputs();
    
    // Refrescar búsqueda
    const searchTerm = document.getElementById('module-search-form').value;
    if (searchTerm && searchTerm.length >= 2) {
        searchAvailableModulesForForm(searchTerm);
    }
}

// ACTUALIZAR DISPLAYS
function updateMembersDisplay() {
    updateMembersToAssignDisplay();
    updateMembersToRemoveDisplay();
    updateSelectedMembersCount();
}

function updateModulesDisplay() {
    updateModulesToAssignDisplay();
    updateModulesToRemoveDisplay();
    updateSelectedModulesCount();
}

function updateMembersToAssignDisplay() {
    const container = document.getElementById('members-to-assign');
    const listContainer = document.getElementById('members-to-assign-list');
    
    if (membersToAdd.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = membersToAdd.map(memberId => `
        <div class="badge bg-success me-2 mb-2">
            <i class="bi bi-plus-circle me-1"></i>
            Miembro ID: ${memberId}
            <button type="button" class="btn-close btn-close-white ms-2" onclick="removeMemberFromSelection(${memberId})" style="font-size: 0.7em;"></button>
        </div>
    `).join('');
}

function updateMembersToRemoveDisplay() {
    const container = document.getElementById('members-to-remove');
    const listContainer = document.getElementById('members-to-remove-list');
    
    if (membersToRemove.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = membersToRemove.map(memberId => `
        <div class="badge bg-danger me-2 mb-2">
            <i class="bi bi-dash-circle me-1"></i>
            Miembro ID: ${memberId}
            <button type="button" class="btn-close btn-close-white ms-2" onclick="addMemberToSelection(${memberId}, 'Miembro ${memberId}', 'email@example.com')" style="font-size: 0.7em;"></button>
        </div>
    `).join('');
}

function updateModulesToAssignDisplay() {
    const container = document.getElementById('modules-to-assign');
    const listContainer = document.getElementById('modules-to-assign-list');
    
    if (modulesToAdd.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = modulesToAdd.map(moduleId => `
        <div class="badge bg-success me-2 mb-2">
            <i class="bi bi-plus-circle me-1"></i>
            Módulo ID: ${moduleId}
            <button type="button" class="btn-close btn-close-white ms-2" onclick="removeModuleFromSelection(${moduleId})" style="font-size: 0.7em;"></button>
        </div>
    `).join('');
}

function updateModulesToRemoveDisplay() {
    const container = document.getElementById('modules-to-remove');
    const listContainer = document.getElementById('modules-to-remove-list');
    
    if (modulesToRemove.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    listContainer.innerHTML = modulesToRemove.map(moduleId => `
        <div class="badge bg-danger me-2 mb-2">
            <i class="bi bi-dash-circle me-1"></i>
            Módulo ID: ${moduleId}
            <button type="button" class="btn-close btn-close-white ms-2" onclick="addModuleToSelection(${moduleId}, 'Módulo ${moduleId}', '', 'PENDING')" style="font-size: 0.7em;"></button>
        </div>
    `).join('');
}

function updateSelectedMembersCount() {
    const badge = document.getElementById('selected-members-count');
    badge.textContent = `${selectedMembers.length} miembro(s)`;
}

function updateSelectedModulesCount() {
    const badge = document.getElementById('selected-modules-count');
    badge.textContent = `${selectedModules.length} módulo(s)`;
}

// Actualizar inputs ocultos para el formulario
function updateHiddenInputs() {
    const membersContainer = document.getElementById('members-inputs');
    const modulesContainer = document.getElementById('modules-inputs');
    
    // Limpiar inputs existentes
    membersContainer.innerHTML = '';
    modulesContainer.innerHTML = '';
    
    // Crear inputs para todos los miembros seleccionados
    selectedMembers.forEach(memberId => {
        const memberInput = document.createElement('input');
        memberInput.type = 'hidden';
        memberInput.name = 'members[]';
        memberInput.value = memberId;
        memberInput.setAttribute('data-member-input', memberId);
        membersContainer.appendChild(memberInput);
        
        const roleInput = document.createElement('input');
        roleInput.type = 'hidden';
        roleInput.name = 'roles[]';
        roleInput.value = selectedMemberRoles[memberId] || 'DEVELOPER';
        roleInput.setAttribute('data-role-input', memberId);
        membersContainer.appendChild(roleInput);
    });
    
    // Crear inputs para todos los módulos seleccionados
    selectedModules.forEach(moduleId => {
        const moduleInput = document.createElement('input');
        moduleInput.type = 'hidden';
        moduleInput.name = 'modules[]';
        moduleInput.value = moduleId;
        moduleInput.setAttribute('data-module-input', moduleId);
        modulesContainer.appendChild(moduleInput);
    });
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

.member-card,
.module-card {
    transition: all 0.3s ease;
}

.member-card:hover,
.module-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.15rem 1rem 0 rgba(58, 59, 69, 0.15);
}
</style>
@endsection