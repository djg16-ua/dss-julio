@extends('layouts.app')

@section('title', 'Tareas - ' . $project->title . ' - TaskFlow')

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
                        <a href="{{ route('project.show', $project) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary">
                                <i class="bi bi-check2-square me-3"></i>Tareas
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
                        Gestiona todas las tareas del proyecto
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Tarea
                    </button>
                </div>
            </div>

            <!-- Estadísticas -->
            @php
                $taskStats = [
                    'total_tasks' => $tasks->count(),
                    'pending_tasks' => $tasks->where('status', 'PENDING')->count(),
                    'active_tasks' => $tasks->where('status', 'ACTIVE')->count(),
                    'completed_tasks' => $tasks->where('status', 'DONE')->count(),
                    'urgent_priority' => $tasks->where('priority', 'URGENT')->count(),
                    'high_priority' => $tasks->where('priority', 'HIGH')->count(),
                ];
            @endphp
            
            <div class="row g-4 mb-5">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon info mx-auto mb-3">
                                <i class="bi bi-check2-square"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $taskStats['total_tasks'] }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon light mx-auto mb-3">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $taskStats['pending_tasks'] }}</h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon info mx-auto mb-3">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $taskStats['active_tasks'] }}</h3>
                            <p class="text-muted mb-0">Activas</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon info mx-auto mb-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $taskStats['completed_tasks'] }}</h3>
                            <p class="text-muted mb-0">Completadas</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon danger mx-auto mb-3">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $taskStats['urgent_priority'] }}</h3>
                            <p class="text-muted mb-0">Urgentes</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon warning mx-auto mb-3">
                                <i class="bi bi-lightning"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $taskStats['high_priority'] }}</h3>
                            <p class="text-muted mb-0">Alta Prioridad</p>
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
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-bold">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Título o descripción..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label fw-bold">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos los estados</option>
                                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>✅ Activa</option>
                                <option value="DONE" {{ request('status') == 'DONE' ? 'selected' : '' }}>🎉 Completada</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="priority" class="form-label fw-bold">Prioridad</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="">Todas las prioridades</option>
                                <option value="URGENT" {{ request('priority') == 'URGENT' ? 'selected' : '' }}>🚨 Urgente</option>
                                <option value="HIGH" {{ request('priority') == 'HIGH' ? 'selected' : '' }}>⚡ Alta</option>
                                <option value="MEDIUM" {{ request('priority') == 'MEDIUM' ? 'selected' : '' }}>📋 Media</option>
                                <option value="LOW" {{ request('priority') == 'LOW' ? 'selected' : '' }}>📝 Baja</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="module" class="form-label fw-bold">Módulo</label>
                            <select class="form-select" id="module" name="module">
                                <option value="">Todos los módulos</option>
                                @foreach($project->modules as $module)
                                    <option value="{{ $module->id }}" {{ request('module') == $module->id ? 'selected' : '' }}>
                                        {{ $module->name }}
                                    </option>
                                @endforeach
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

            <!-- Lista de tareas -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul text-primary me-2"></i>
                        Lista de Tareas (<span id="task-count">{{ $tasks->count() }}</span> tareas)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="tasks-container">
                        @include('task.partials.tasks-list', ['tasks' => $tasks, 'project' => $project])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear tarea -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Crear Nueva Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createTaskForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="task-title" class="form-label">Título de la tarea <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="task-title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="task-priority" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select" id="task-priority" required>
                                    <option value="MEDIUM" selected>📋 Media</option>
                                    <option value="URGENT">🚨 Urgente</option>
                                    <option value="HIGH">⚡ Alta</option>
                                    <option value="LOW">📝 Baja</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="task-module" class="form-label">Módulo <span class="text-danger">*</span></label>
                                <select class="form-select" id="task-module" required>
                                    <option value="">Selecciona un módulo...</option>
                                    @foreach($project->modules as $module)
                                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Solo podrás asignar usuarios de los equipos del módulo seleccionado
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="task-description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="task-description" rows="4" placeholder="Describe los detalles de la tarea..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="task-assigned-to" class="form-label">Asignar a</label>
                        <select class="form-select" id="task-assigned-to" multiple size="5">
                            <option value="">Selecciona primero un módulo</option>
                        </select>
                        <div id="members-loading" class="form-text text-muted" style="display: none;">
                            <i class="bi bi-arrow-repeat me-1"></i>Cargando miembros de los equipos asignados...
                        </div>
                        <div id="no-members-available" class="form-text text-warning" style="display: none;">
                            <i class="bi bi-info-circle me-1"></i>
                            No hay miembros en los equipos asignados al módulo
                        </div>
                        <div class="form-text">Mantén Ctrl presionado para seleccionar múltiples miembros</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmCreateTask">
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Crear Tarea
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Función para cargar tareas con AJAX
function loadTasks(filters = {}) {
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
    document.getElementById('tasks-container').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-3">Buscando tareas...</p>
        </div>
    `;
    
    // Realizar petición AJAX
    fetch(`{{ route('task.index', $project) }}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('tasks-container').innerHTML = data.html;
        document.getElementById('task-count').textContent = data.count;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('tasks-container').innerHTML = `
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                </div>
                <h5 class="text-danger">Error al cargar tareas</h5>
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
    
    loadTasks(filters);
});

// Filtros automáticos al cambiar los selects
document.getElementById('status').addEventListener('change', function() {
    document.getElementById('filter-form').dispatchEvent(new Event('submit'));
});

document.getElementById('priority').addEventListener('change', function() {
    document.getElementById('filter-form').dispatchEvent(new Event('submit'));
});

document.getElementById('module').addEventListener('change', function() {
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
    document.getElementById('module').value = '';
    loadTasks(); // Cargar todas las tareas
});

// MODAL DE CREAR TAREA
const createTaskModal = document.getElementById('createTaskModal');
const taskModuleSelect = document.getElementById('task-module');
const taskAssignedToSelect = document.getElementById('task-assigned-to');

// Cargar miembros cuando cambia el módulo seleccionado
taskModuleSelect.addEventListener('change', function() {
    const moduleId = this.value;
    
    if (!moduleId) {
        taskAssignedToSelect.innerHTML = '<option value="">Selecciona primero un módulo</option>';
        taskAssignedToSelect.disabled = true;
        return;
    }
    
    loadModuleTeamMembers(moduleId);
});

function loadModuleTeamMembers(moduleId) {
    const membersSelect = document.getElementById('task-assigned-to');
    const loadingText = document.getElementById('members-loading');
    const noMembersText = document.getElementById('no-members-available');
    
    // Mostrar estado de carga
    membersSelect.innerHTML = '<option value="">Cargando miembros...</option>';
    membersSelect.disabled = true;
    if (loadingText) loadingText.style.display = 'block';
    if (noMembersText) noMembersText.style.display = 'none';
    
    // Usar la ruta correcta para obtener miembros del módulo
    fetch(`/project/{{ $project->id }}/modules/${moduleId}/team-members`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(members => {
            // Limpiar select
            membersSelect.innerHTML = '';
            
            if (members.length > 0) {
                members.forEach(member => {
                    const option = document.createElement('option');
                    option.value = member.id;
                    option.textContent = member.name;
                    if (member.is_current_user) {
                        option.textContent += ' (Tú)';
                    }
                    if (member.team_names) {
                        option.textContent += ` - ${member.team_names}`;
                    }
                    membersSelect.appendChild(option);
                });
                
                if (noMembersText) noMembersText.style.display = 'none';
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay miembros disponibles';
                option.disabled = true;
                membersSelect.appendChild(option);
                
                if (noMembersText) noMembersText.style.display = 'block';
            }
            
            membersSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error cargando miembros:', error);
            membersSelect.innerHTML = '<option value="" disabled>Error al cargar miembros</option>';
            membersSelect.disabled = false;
        })
        .finally(() => {
            if (loadingText) loadingText.style.display = 'none';
        });
}

// Confirmar crear tarea
const confirmCreateTaskButton = document.getElementById('confirmCreateTask');
if (confirmCreateTaskButton) {
    confirmCreateTaskButton.addEventListener('click', function() {
        const titleInput = document.getElementById('task-title');
        const descriptionInput = document.getElementById('task-description');
        const prioritySelect = document.getElementById('task-priority');
        const moduleSelect = document.getElementById('task-module');
        const assignedToSelect = document.getElementById('task-assigned-to');
        
        const title = titleInput ? titleInput.value.trim() : '';
        const description = descriptionInput ? descriptionInput.value.trim() : '';
        const priority = prioritySelect ? prioritySelect.value : 'MEDIUM';
        const moduleId = moduleSelect ? moduleSelect.value : '';
        const assignedTo = assignedToSelect ? Array.from(assignedToSelect.selectedOptions).map(option => option.value).filter(value => value !== '') : [];
        
        if (!title) {
            alert('Por favor ingresa un título para la tarea');
            return;
        }
        
        if (!moduleId) {
            alert('Por favor selecciona un módulo para la tarea');
            return;
        }
        
        console.log('Creando tarea:', {title, description, priority, moduleId, assignedTo});
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('task.store', $project) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                description: description,
                priority: priority,
                module_id: moduleId,
                assigned_users: assignedTo
            })
        })
        .then(response => {
            console.log('Respuesta crear tarea:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos crear tarea:', data);
            if (data.success) {
                const modal = document.getElementById('createTaskModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                
                // Recargar tareas para mostrar la nueva
                const currentFilters = {};
                const formData = new FormData(document.getElementById('filter-form'));
                for (let [key, value] of formData.entries()) {
                    if (value.trim() !== '' && key !== '_token') {
                        currentFilters[key] = value;
                    }
                }
                loadTasks(currentFilters);
                
                // Mostrar mensaje de éxito
                showSuccessMessage('Tarea creada exitosamente');
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error crear tarea:', error);
            alert('Error al crear la tarea: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Limpiar modal al cerrarse
if (createTaskModal) {
    createTaskModal.addEventListener('hidden.bs.modal', function() {
        const titleInput = document.getElementById('task-title');
        const descriptionInput = document.getElementById('task-description');
        const prioritySelect = document.getElementById('task-priority');
        const moduleSelect = document.getElementById('task-module');
        const assignedToSelect = document.getElementById('task-assigned-to');
        
        if (titleInput) titleInput.value = '';
        if (descriptionInput) descriptionInput.value = '';
        if (prioritySelect) prioritySelect.value = 'MEDIUM';
        if (moduleSelect) moduleSelect.value = '';
        if (assignedToSelect) {
            assignedToSelect.innerHTML = '<option value="">Selecciona primero un módulo</option>';
            assignedToSelect.disabled = true;
        }
    });
}

// Función para mostrar mensaje de éxito
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
    alertDiv.innerHTML = `
        <i class="bi bi-check-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-dismiss después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Efectos hover para las cards
const style = document.createElement('style');
style.textContent = `
    .task-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .task-card {
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

    .feature-icon.info {
        background: linear-gradient(45deg, #36b9cc, #258391);
    }

    .feature-icon.light {
        background: linear-gradient(45deg, #f8f9fc, #e3e6f0);
        color: #5a5c69 !important;
    }

    .feature-icon.danger {
        background: linear-gradient(45deg, #e74a3b, #c23321);
    }

    .feature-icon.warning {
        background: linear-gradient(45deg, #f6c23e, #dda20a);
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
document.head.appendChild(style);
</script>
@endpush
@endsection