@extends('layouts.app')

@section('title', 'Crear Proyecto - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-success">
                        <i class="bi bi-plus-circle me-3"></i>Crear Nuevo Proyecto
                    </h1>
                    <p class="lead text-muted">
                        Configura un nuevo proyecto para el sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.projects') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Proyectos
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
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información del Proyecto
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.projects.store') }}">
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label fw-bold">
                                            <i class="bi bi-tag me-1"></i>Título del Proyecto
                                        </label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}" required
                                            placeholder="Ej: Sistema de Gestión de Inventarios">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">El título debe ser único y descriptivo</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label fw-bold">
                                            <i class="bi bi-flag me-1"></i>Estado Inicial
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            @foreach($projectStatuses as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', 'PENDING') === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Generalmente se inicia como "Pendiente"</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">
                                            <i class="bi bi-text-paragraph me-1"></i>Descripción
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="4"
                                            placeholder="Describe el objetivo, alcance y características principales del proyecto...">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Una buena descripción ayuda a todos a entender el proyecto</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="start_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-event me-1"></i>Fecha de Inicio
                                        </label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                            id="start_date" name="start_date" value="{{ old('start_date') }}">
                                        @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Opcional - fecha planificada de inicio</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="end_date" class="form-label fw-bold">
                                            <i class="bi bi-calendar-check me-1"></i>Fecha de Finalización
                                        </label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date" value="{{ old('end_date') }}">
                                        @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Opcional - fecha planificada de finalización</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="public" class="form-label fw-bold">
                                            <i class="bi bi-eye me-1"></i>Visibilidad
                                        </label>
                                        <select class="form-select @error('public') is-invalid @enderror" id="public" name="public" required>
                                            <option value="0" {{ old('public', '0') == '0' ? 'selected' : '' }}>
                                                <i class="bi bi-lock me-1"></i>Privado
                                            </option>
                                            <option value="1" {{ old('public') == '1' ? 'selected' : '' }}>
                                                <i class="bi bi-globe me-1"></i>Público
                                            </option>
                                        </select>
                                        @error('public')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Los proyectos públicos son visibles para todos los usuarios</div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Equipos iniciales -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-success mb-3">
                                            <i class="bi bi-people me-2"></i>Equipos Iniciales (Opcional)
                                        </h6>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Puedes asignar equipos ahora o hacerlo después desde la página de edición del proyecto.
                                        </div>

                                        @if($availableTeams->count() > 0)
                                        <div id="project-teams">
                                            <!-- Equipo 1 -->
                                            <div class="row g-3 mb-3 team-assignment-row">
                                                <div class="col-md-10">
                                                    <label class="form-label">Equipo</label>
                                                    <select class="form-select" name="teams[0][team_id]">
                                                        <option value="">Seleccionar equipo...</option>
                                                        @foreach($availableTeams as $team)
                                                        <option value="{{ $team->id }}">
                                                            {{ $team->name }}
                                                            <small>({{ $team->users->where('pivot.is_active', true)->count() }} miembros activos)</small>
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeTeam(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-outline-success" onclick="addTeam()">
                                            <i class="bi bi-people-plus me-2"></i>Agregar Otro Equipo
                                        </button>
                                        @else
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            No hay equipos disponibles. Puedes crear equipos desde la sección de gestión de equipos.
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check-lg me-2"></i>Crear Proyecto
                                            </button>
                                            <a href="{{ route('admin.projects') }}" class="btn btn-secondary">
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
                                    <i class="bi bi-1-circle me-2"></i>Título del Proyecto
                                </h6>
                                <p class="small text-muted mb-0">
                                    Elige un nombre claro y descriptivo que refleje el objetivo principal del proyecto.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-2-circle me-2"></i>Descripción Detallada
                                </h6>
                                <p class="small text-muted mb-0">
                                    Explica el alcance, objetivos y características principales del proyecto para que todos entiendan su propósito.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-3-circle me-2"></i>Fechas del Proyecto
                                </h6>
                                <p class="small text-muted mb-0">
                                    Define las fechas de inicio y fin para establecer un marco temporal claro.
                                </p>
                            </div>

                            <div>
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-4-circle me-2"></i>Equipos y Módulos
                                </h6>
                                <p class="small text-muted mb-0">
                                    Después de crear el proyecto, podrás asignar equipos específicos y crear módulos para organizar el trabajo.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estados disponibles -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-secondary text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-flag me-2"></i>
                                Estados de Proyecto
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($projectStatuses as $value => $label)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $value === 'ACTIVE' ? 'success' : ($value === 'DONE' ? 'primary' : ($value === 'PAUSED' ? 'warning' : ($value === 'CANCELLED' ? 'danger' : 'secondary'))) }}">
                                    {{ $value }}
                                </span>
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
                                        <div class="fw-bold h6 text-primary">{{ $stats['total_projects'] ?? 0 }}</div>
                                        <small class="text-muted">Proyectos Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-success">{{ $stats['active_projects'] ?? 0 }}</div>
                                        <small class="text-muted">Proyectos Activos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-warning">{{ $stats['total_teams'] ?? 0 }}</div>
                                        <small class="text-muted">Equipos Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-info">{{ $stats['total_modules'] ?? 0 }}</div>
                                        <small class="text-muted">Módulos Totales</small>
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

    // Preparar los datos de equipos para JavaScript
    const availableTeams = @json($availableTeams);

    function addTeam() {
        const container = document.getElementById('project-teams');

        // Generar opciones de equipos
        let teamOptions = '<option value="">Seleccionar equipo...</option>';
        availableTeams.forEach(team => {
            const activeMembers = team.users.filter(user => user.pivot.is_active).length;
            teamOptions += `<option value="${team.id}">${team.name} (${activeMembers} miembros activos)</option>`;
        });

        const newTeamHtml = `
        <div class="row g-3 mb-3 team-assignment-row">
            <div class="col-md-10">
                <label class="form-label">Equipo</label>
                <select class="form-select" name="teams[${teamIndex}][team_id]">
                    ${teamOptions}
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

        // Validación de fechas
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        function validateDates() {
            if (startDateInput.value && endDateInput.value) {
                if (new Date(startDateInput.value) >= new Date(endDateInput.value)) {
                    endDateInput.setCustomValidity('La fecha de fin debe ser posterior a la fecha de inicio');
                } else {
                    endDateInput.setCustomValidity('');
                }
            } else {
                endDateInput.setCustomValidity('');
            }
        }

        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);
    });
</script>
@endpush
@endsection