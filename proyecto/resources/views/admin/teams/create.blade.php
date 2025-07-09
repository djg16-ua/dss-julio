@extends('layouts.app')

@section('title', 'Crear Equipo - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-warning">
                        <i class="bi bi-people-plus me-3"></i>Crear Nuevo Equipo
                    </h1>
                    <p class="lead text-muted">
                        Configura un nuevo equipo para el sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.teams') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Equipos
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
                                Información del Equipo
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.teams.store') }}">
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-bold">
                                            <i class="bi bi-tag me-1"></i>Nombre del Equipo
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required
                                            placeholder="Ej: Equipo Frontend, Backend Team...">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">El nombre debe ser único dentro del proyecto</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="project_id" class="form-label fw-bold">
                                            <i class="bi bi-folder me-1"></i>Proyecto <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required onchange="updateProjectInfo()">
                                            <option value="">Seleccionar proyecto...</option>
                                            @foreach($projects as $project)
                                            <option value="{{ $project->id }}"
                                                data-title="{{ $project->title }}"
                                                data-status="{{ $project->status }}"
                                                data-description="{{ $project->description }}"
                                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->title }} ({{ $project->status }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">El equipo pertenecerá a este proyecto</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label fw-bold">
                                            <i class="bi bi-text-paragraph me-1"></i>Descripción
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="4"
                                            placeholder="Describe el propósito, objetivos y responsabilidades del equipo...">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Una buena descripción ayuda a los miembros a entender su rol</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="lead_user" class="form-label fw-bold">
                                            <i class="bi bi-person-badge me-1"></i>Líder del Equipo (Opcional)
                                        </label>
                                        <select class="form-select @error('lead_user') is-invalid @enderror" id="lead_user" name="lead_user">
                                            <option value="">Seleccionar líder...</option>
                                            @foreach($availableUsers as $user)
                                            <option value="{{ $user->id }}" {{ old('lead_user') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('lead_user')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">El líder será agregado automáticamente con rol LEAD</div>
                                    </div>

                                    <!-- Información del proyecto seleccionado -->
                                    <div class="col-md-6">
                                        <div id="project-info" class="d-none">
                                            <h6 class="fw-bold text-info mb-2">
                                                <i class="bi bi-info-circle me-1"></i>Información del Proyecto
                                            </h6>
                                            <div class="card bg-light border-info">
                                                <div class="card-body p-3">
                                                    <div class="small">
                                                        <div><strong>Título:</strong> <span id="project-title">-</span></div>
                                                        <div><strong>Estado:</strong> <span id="project-status" class="badge">-</span></div>
                                                        <div><strong>Descripción:</strong> <span id="project-description" class="text-muted">-</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Miembros iniciales -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-warning mb-3">
                                            <i class="bi bi-people me-2"></i>Miembros Iniciales (Opcional)
                                        </h6>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Puedes agregar miembros ahora o hacerlo después desde la página de edición del equipo.
                                            <strong>Nota:</strong> Los miembros también serán agregados automáticamente al equipo general del proyecto.
                                        </div>

                                        <div id="team-members">
                                            <!-- Miembro 1 -->
                                            <div class="row g-3 mb-3 team-member-row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Usuario</label>
                                                    <select class="form-select" name="members[0][user_id]">
                                                        <option value="">Seleccionar usuario...</option>
                                                        @foreach($availableUsers as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->name }} ({{ $user->email }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Rol</label>
                                                    <select class="form-select" name="members[0][role]">
                                                        @foreach($teamRoles as $value => $label)
                                                        <option value="{{ $value }}" {{ $value === 'DEVELOPER' ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeMember(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-outline-warning" onclick="addMember()">
                                            <i class="bi bi-person-plus me-2"></i>Agregar Otro Miembro
                                        </button>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-check-lg me-2"></i>Crear Equipo
                                            </button>
                                            <a href="{{ route('admin.teams') }}" class="btn btn-secondary">
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
                                    <i class="bi bi-1-circle me-2"></i>Seleccionar Proyecto
                                </h6>
                                <p class="small text-muted mb-0">
                                    <strong>Requerido:</strong> Cada equipo debe pertenecer a un proyecto específico. Selecciona el proyecto al que estará asociado este equipo.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-2-circle me-2"></i>Nombre del Equipo
                                </h6>
                                <p class="small text-muted mb-0">
                                    Elige un nombre claro y descriptivo. Debe ser único dentro del proyecto seleccionado.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-3-circle me-2"></i>Líder del Equipo
                                </h6>
                                <p class="small text-muted mb-0">
                                    El líder coordinará las actividades y será responsable de la comunicación del equipo.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-4-circle me-2"></i>Descripción
                                </h6>
                                <p class="small text-muted mb-0">
                                    Explica claramente el propósito, objetivos y responsabilidades del equipo dentro del proyecto.
                                </p>
                            </div>

                            <div>
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-5-circle me-2"></i>Miembros
                                </h6>
                                <p class="small text-muted mb-0">
                                    Los miembros del equipo serán agregados automáticamente al equipo general del proyecto.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tipos de equipos -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-warning text-dark py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Tipos de Equipos
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="badge bg-info mb-2">Equipo General</span>
                                <p class="small text-muted mb-0">
                                    Se crea automáticamente con cada proyecto. Incluye todos los miembros del proyecto.
                                </p>
                            </div>
                            <div>
                                <span class="badge bg-warning mb-2">Equipo Personalizado</span>
                                <p class="small text-muted mb-0">
                                    Como el que estás creando. Permite organizar miembros por especialidad o función específica.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Roles disponibles -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-secondary text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-person-badge me-2"></i>
                                Roles Disponibles
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($teamRoles as $value => $label)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary">{{ $value }}</span>
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
                                        <div class="fw-bold h6 text-primary">{{ $stats['total_teams'] ?? 0 }}</div>
                                        <small class="text-muted">Equipos Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-success">{{ $stats['total_users'] ?? 0 }}</div>
                                        <small class="text-muted">Usuarios Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-warning">{{ $stats['active_members'] ?? 0 }}</div>
                                        <small class="text-muted">Miembros Activos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-info">{{ $stats['total_projects'] ?? 0 }}</div>
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
    let memberIndex = 1;

    // Preparar los datos de usuarios y roles para JavaScript
    const availableUsers = @json($availableUsers);
    const teamRoles = @json($teamRoles);

    function updateProjectInfo() {
        const select = document.getElementById('project_id');
        const projectInfo = document.getElementById('project-info');
        const option = select.options[select.selectedIndex];

        if (select.value && option.dataset.title) {
            // Mostrar información del proyecto
            projectInfo.classList.remove('d-none');

            document.getElementById('project-title').textContent = option.dataset.title;
            document.getElementById('project-description').textContent = option.dataset.description || 'Sin descripción';

            // Actualizar badge de estado
            const statusBadge = document.getElementById('project-status');
            statusBadge.textContent = option.dataset.status;
            statusBadge.className = 'badge ' + getStatusBadgeClass(option.dataset.status);
        } else {
            // Ocultar información
            projectInfo.classList.add('d-none');
        }
    }

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'ACTIVE':
                return 'bg-success';
            case 'PENDING':
                return 'bg-warning';
            case 'DONE':
                return 'bg-primary';
            case 'PAUSED':
                return 'bg-secondary';
            case 'CANCELLED':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    function addMember() {
        const container = document.getElementById('team-members');

        // Generar opciones de usuarios
        let userOptions = '<option value="">Seleccionar usuario...</option>';
        availableUsers.forEach(user => {
            userOptions += `<option value="${user.id}">${user.name} (${user.email})</option>`;
        });

        // Generar opciones de roles
        let roleOptions = '';
        Object.entries(teamRoles).forEach(([value, label]) => {
            const selected = value === 'DEVELOPER' ? 'selected' : '';
            roleOptions += `<option value="${value}" ${selected}>${label}</option>`;
        });

        const newMemberHtml = `
        <div class="row g-3 mb-3 team-member-row">
            <div class="col-md-6">
                <label class="form-label">Usuario</label>
                <select class="form-select" name="members[${memberIndex}][user_id]">
                    ${userOptions}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Rol</label>
                <select class="form-select" name="members[${memberIndex}][role]">
                    ${roleOptions}
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger w-100" onclick="removeMember(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', newMemberHtml);
        memberIndex++;
    }

    function removeMember(button) {
        const memberRow = button.closest('.team-member-row');
        memberRow.remove();
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

        // Actualizar información del proyecto si hay uno preseleccionado
        updateProjectInfo();
    });
</script>
@endpush
@endsection