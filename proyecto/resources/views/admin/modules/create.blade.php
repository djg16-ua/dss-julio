@extends('layouts.app')

@section('title', 'Crear Módulo - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-warning">
                        <i class="bi bi-plus-square me-3"></i>Crear Nuevo Módulo
                    </h1>
                    <p class="lead text-muted">
                        Agrega un nuevo módulo al sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.modules') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Módulos
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            <!-- Formulario principal -->
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información del Módulo
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.modules.store') }}">
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-bold">
                                            <i class="bi bi-tag me-1"></i>Nombre del Módulo
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required
                                            placeholder="Ej: Sistema de Autenticación">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Nombre único y descriptivo del módulo</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="project_id" class="form-label fw-bold">
                                            <i class="bi bi-kanban me-1"></i>Proyecto
                                        </label>
                                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                                            <option value="">Seleccionar proyecto...</option>
                                            @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->title }}
                                                <small>({{ $project->status }})</small>
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Proyecto al que pertenecerá este módulo</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">
                                            <i class="bi bi-text-paragraph me-1"></i>Descripción
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="3"
                                            placeholder="Describe la funcionalidad y objetivos del módulo...">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Explica qué hace este módulo y su importancia</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="category" class="form-label fw-bold">
                                            <i class="bi bi-collection me-1"></i>Categoría
                                        </label>
                                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                            @foreach($moduleCategories as $value => $label)
                                            <option value="{{ $value }}" {{ old('category', 'DEVELOPMENT') === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Tipo de trabajo que representa</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="priority" class="form-label fw-bold">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Prioridad
                                        </label>
                                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                            @foreach($priorities as $value => $label)
                                            <option value="{{ $value }}" {{ old('priority', 'MEDIUM') === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Nivel de importancia del módulo</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label fw-bold">
                                            <i class="bi bi-flag me-1"></i>Estado Inicial
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="PENDING" {{ old('status', 'PENDING') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="ACTIVE" {{ old('status') === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                            <option value="DONE" {{ old('status') === 'DONE' ? 'selected' : '' }}>Completado</option>
                                            <option value="PAUSED" {{ old('status') === 'PAUSED' ? 'selected' : '' }}>Pausado</option>
                                            <option value="CANCELLED" {{ old('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Estado inicial del módulo</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="depends_on" class="form-label fw-bold">
                                            <i class="bi bi-arrow-right-circle me-1"></i>Depende de (Opcional)
                                        </label>
                                        <select class="form-select @error('depends_on') is-invalid @enderror" id="depends_on" name="depends_on">
                                            <option value="">Sin dependencias</option>
                                            <!-- Se llenará dinámicamente con JavaScript según el proyecto seleccionado -->
                                        </select>
                                        @error('depends_on')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Módulo del que depende este para poder iniciarse</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-star me-1"></i>Tipo de Módulo
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_core" value="1"
                                                id="is_core" {{ old('is_core') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-danger" for="is_core">
                                                Módulo Principal (CORE)
                                            </label>
                                        </div>
                                        <div class="form-text">Los módulos CORE son fundamentales para el proyecto</div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Asignación a equipos inicial (opcional) -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-warning mb-3">
                                            <i class="bi bi-people me-2"></i>Asignación a Equipos (Opcional)
                                        </h6>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Puedes asignar equipos al módulo ahora o hacerlo después desde la edición.
                                        </div>

                                        <div id="module-teams">
                                            <!-- Equipo 1 -->
                                            <div class="row g-3 mb-3 team-assignment-row">
                                                <div class="col-md-10">
                                                    <label class="form-label">Equipo</label>
                                                    <select class="form-select team-select" name="teams[0][team_id]">
                                                        <option value="">Seleccionar equipo...</option>
                                                        <!-- Se llenará dinámicamente -->
                                                    </select>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeTeam(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-outline-warning" onclick="addTeam()">
                                            <i class="bi bi-people-plus me-2"></i>Agregar Otro Equipo
                                        </button>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-check-lg me-2"></i>Crear Módulo
                                            </button>
                                            <a href="{{ route('admin.modules') }}" class="btn btn-secondary">
                                                <i class="bi bi-x-lg me-2"></i>Cancelar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar con información -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightbulb me-2"></i>
                                Guía de Creación
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-1-circle me-2"></i>Información Básica
                                </h6>
                                <p class="small text-muted mb-0">
                                    Proporciona un nombre claro y una descripción detallada de lo que hará el módulo.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-2-circle me-2"></i>Clasificación
                                </h6>
                                <p class="small text-muted mb-0">
                                    Selecciona la categoría y prioridad adecuadas para organizar mejor el trabajo.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-3-circle me-2"></i>Dependencias
                                </h6>
                                <p class="small text-muted mb-0">
                                    Si este módulo requiere que otro esté completo primero, establece la dependencia.
                                </p>
                            </div>

                            <div>
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-4-circle me-2"></i>Módulos CORE
                                </h6>
                                <p class="small text-muted mb-0">
                                    Marca como CORE solo los módulos fundamentales sin los cuales el proyecto no puede funcionar.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Categorías disponibles -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-secondary text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-collection me-2"></i>
                                Categorías de Módulos
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($moduleCategories as $value => $label)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-dark">{{ $value }}</span>
                                <small class="text-muted">{{ $label }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Prioridades -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-warning text-dark py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Niveles de Prioridad
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($priorities as $value => $label)
                            @php
                            $color = $value === 'URGENT' ? 'danger' : ($value === 'HIGH' ? 'warning' : ($value === 'MEDIUM' ? 'info' : 'secondary'));
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $color }}">{{ $value }}</span>
                                <small class="text-muted">{{ $label }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Estadísticas del sistema -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-success text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Estadísticas del Sistema
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-warning">{{ $stats['total_modules'] ?? 0 }}</div>
                                        <small class="text-muted">Módulos Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-success">{{ $stats['active_modules'] ?? 0 }}</div>
                                        <small class="text-muted">Módulos Activos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-danger">{{ $stats['core_modules'] ?? 0 }}</div>
                                        <small class="text-muted">Módulos CORE</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-primary">{{ $stats['total_projects'] ?? 0 }}</div>
                                        <small class="text-muted">Proyectos</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast notifications -->
@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Éxito</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header bg-danger text-white">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header bg-warning text-dark">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong class="me-auto">Errores de Validación</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    let teamIndex = 1;
    
    // Datos para JavaScript - cargar todos los equipos del sistema
    const allProjects = @json($projects);
    const allTeams = @json(\App\Models\Team::with(['users' => function($query) { $query->where('is_active', true); }])->orderBy('name')->get());

    // Cargar módulos del proyecto seleccionado para dependencias
    function loadProjectModules(projectId) {
        const dependsOnSelect = document.getElementById('depends_on');
        dependsOnSelect.innerHTML = '<option value="">Sin dependencias</option>';

        if (projectId) {
            // Buscar proyecto específico y simular módulos vacíos (ya que es creación)
            const project = allProjects.find(p => p.id == projectId);
            if (project) {
                // En creación, no hay módulos previos para mostrar como dependencias
                // El select queda solo con "Sin dependencias"
                console.log(`Proyecto seleccionado: ${project.title}`);
            }
        }
    }

    // Cargar equipos del proyecto seleccionado
    function loadProjectTeams(projectId) {
        const teamsSection = document.getElementById('teams-section');
        const noTeamsMessage = document.getElementById('no-teams-message');
        const infoAlert = document.querySelector('.alert-info');
        
        if (!projectId) {
            // No hay proyecto seleccionado
            teamsSection.style.display = 'none';
            noTeamsMessage.style.display = 'none';
            if (infoAlert) {
                infoAlert.style.display = 'block';
                infoAlert.innerHTML = '<i class="bi bi-info-circle me-2"></i><strong>Selecciona primero un proyecto</strong> para ver los equipos disponibles.';
            }
            return;
        }

        // Mostrar loading temporal
        if (infoAlert) {
            infoAlert.style.display = 'block';
            infoAlert.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cargando equipos del proyecto...';
        }

        // Filtrar equipos por proyecto (sin AJAX)
        setTimeout(() => {
            const projectTeams = allTeams.filter(team => team.project_id == projectId);
            updateTeamsInterface(projectTeams);
        }, 300); // Pequeño delay para mostrar el loading
    }

    function updateTeamsInterface(teams) {
        const teamsSection = document.getElementById('teams-section');
        const noTeamsMessage = document.getElementById('no-teams-message');
        const infoAlert = document.querySelector('.alert-info');
        
        if (teams.length > 0) {
            // Hay equipos disponibles
            updateTeamSelects(teams);
            teamsSection.style.display = 'block';
            noTeamsMessage.style.display = 'none';
            if (infoAlert) infoAlert.style.display = 'none';
        } else {
            // No hay equipos disponibles
            teamsSection.style.display = 'none';
            noTeamsMessage.style.display = 'block';
            if (infoAlert) infoAlert.style.display = 'none';
        }
    }

    function updateTeamSelects(teams) {
        const teamSelects = document.querySelectorAll('.team-select');
        
        teamSelects.forEach(select => {
            const currentValue = select.value;
            select.innerHTML = '<option value="">Seleccionar equipo...</option>';
            
            teams.forEach(team => {
                const option = document.createElement('option');
                option.value = team.id;
                const activeUsersCount = team.users ? team.users.filter(user => user.pivot?.is_active !== false).length : 0;
                option.textContent = `${team.name} (${activeUsersCount} miembros${team.is_general ? ' - General' : ''})`;
                select.appendChild(option);
            });
            
            // Restaurar valor si existía
            if (currentValue) {
                select.value = currentValue;
            }
        });
    }

    function addTeam() {
        const container = document.getElementById('module-teams');

        const newTeamHtml = `
        <div class="row g-3 mb-3 team-assignment-row">
            <div class="col-md-10">
                <label class="form-label">Equipo</label>
                <select class="form-select team-select" name="teams[${teamIndex}][team_id]">
                    <option value="">Seleccionar equipo...</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger w-100" onclick="removeTeam(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', newTeamHtml);
        teamIndex++;
        
        // Cargar equipos para el nuevo select si hay proyecto seleccionado
        const projectId = document.getElementById('project_id').value;
        if (projectId) {
            const projectTeams = allTeams.filter(team => team.project_id == projectId);
            updateTeamSelects(projectTeams);
        }
    }

    function removeTeam(button) {
        const teamRow = button.closest('.team-assignment-row');
        teamRow.remove();
    }

    // Auto-hide toasts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(function(toast) {
            setTimeout(function() {
                const bsToast = new bootstrap.Toast(toast);
                bsToast.hide();
            }, 5000);
        });

        // Listener para cambios en el proyecto
        document.getElementById('project_id').addEventListener('change', function() {
            const projectId = this.value;
            loadProjectModules(projectId);
            loadProjectTeams(projectId);
        });
    });
</script>
@endpush
@endsection