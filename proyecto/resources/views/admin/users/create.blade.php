@extends('layouts.app')

@section('title', 'Crear Usuario - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-person-plus me-3"></i>Crear Nuevo Usuario
                    </h1>
                    <p class="lead text-muted">
                        Agrega un nuevo usuario al sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Usuarios
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
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información del Usuario
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.users.store') }}">
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-bold">
                                            <i class="bi bi-person me-1"></i>Nombre Completo
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required
                                            placeholder="Ej: Juan Pérez García">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Nombre y apellidos del usuario</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-bold">
                                            <i class="bi bi-envelope me-1"></i>Correo Electrónico
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required
                                            placeholder="usuario@ejemplo.com">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Debe ser único en el sistema</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-bold">
                                            <i class="bi bi-lock me-1"></i>Contraseña
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" required
                                                placeholder="Mínimo 8 caracteres">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Mínimo 8 caracteres, recomendable incluir números y símbolos</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-bold">
                                            <i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control"
                                                id="password_confirmation" name="password_confirmation" required
                                                placeholder="Repite la contraseña">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                                <i class="bi bi-eye" id="togglePasswordConfirmationIcon"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Debe coincidir con la contraseña anterior</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="role" class="form-label fw-bold">
                                            <i class="bi bi-shield me-1"></i>Rol del Usuario
                                        </label>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                            <option value="USER" {{ old('role', 'USER') === 'USER' ? 'selected' : '' }}>
                                                <i class="bi bi-person me-1"></i>Usuario Regular
                                            </option>
                                            <option value="ADMIN" {{ old('role') === 'ADMIN' ? 'selected' : '' }}>
                                                <i class="bi bi-shield-check me-1"></i>Administrador
                                            </option>
                                        </select>
                                        @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Los administradores tienen acceso completo al sistema</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email_verified" class="form-label fw-bold">
                                            <i class="bi bi-check-circle me-1"></i>Estado de Verificación
                                        </label>
                                        <select class="form-select @error('email_verified') is-invalid @enderror" id="email_verified" name="email_verified" required>
                                            <option value="1" {{ old('email_verified', '1') === '1' ? 'selected' : '' }}>
                                                <i class="bi bi-check-circle me-1"></i>Verificado
                                            </option>
                                            <option value="0" {{ old('email_verified') === '0' ? 'selected' : '' }}>
                                                <i class="bi bi-exclamation-circle me-1"></i>Pendiente de verificación
                                            </option>
                                        </select>
                                        @error('email_verified')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Usuarios verificados pueden acceder inmediatamente</div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Asignación a proyectos (equipos generales) -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bi bi-folder me-2"></i>Asignación a Proyectos (Opcional)
                                        </h6>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Puedes asignar el usuario a proyectos ahora (se agregará al equipo general) o hacerlo después desde la edición del usuario.
                                        </div>

                                        @if($projects->count() > 0)
                                        <div id="user-projects">
                                            <!-- Proyecto 1 -->
                                            <div class="row g-3 mb-3 project-assignment-row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Proyecto</label>
                                                    <select class="form-select" name="projects[0][project_id]">
                                                        <option value="">Seleccionar proyecto...</option>
                                                        @foreach($projects as $project)
                                                        <option value="{{ $project->id }}">
                                                            {{ $project->title }}
                                                            @if($project->teams->count() > 0)
                                                            <small>({{ $project->teams->first()->users->where('pivot.is_active', true)->count() }} miembros)</small>
                                                            @endif
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Rol en el proyecto</label>
                                                    <select class="form-select" name="projects[0][role]">
                                                        @foreach($teamRoles as $value => $label)
                                                        <option value="{{ $value }}" {{ $value === 'DEVELOPER' ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeProject(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary" onclick="addProject()">
                                            <i class="bi bi-folder-plus me-2"></i>Agregar a Otro Proyecto
                                        </button>
                                        @else
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            No hay proyectos disponibles. Puedes crear proyectos desde la sección de gestión de proyectos.
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Asignación a equipos personalizados (opcional) -->
                                @if($projects->count() > 0 && $projects->sum(function($project) { return $project->teams->count(); }) > 0)
                                <hr class="my-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-secondary mb-3">
                                            <i class="bi bi-people me-2"></i>Asignación a Equipos Personalizados (Opcional)
                                        </h6>
                                        <div class="alert alert-secondary">
                                            <i class="bi bi-info-circle me-2"></i>
                                            También puedes asignar el usuario directamente a equipos personalizados específicos.
                                        </div>

                                        <div id="user-teams">
                                            <!-- Equipo 1 -->
                                            <div class="row g-3 mb-3 team-assignment-row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Equipo Personalizado</label>
                                                    <select class="form-select" name="teams[0][team_id]">
                                                        <option value="">Seleccionar equipo...</option>
                                                        @foreach($projects as $project)
                                                        @foreach($project->teams->where('is_general', false) as $team)
                                                        <option value="{{ $team->id }}">
                                                            {{ $project->title }} - {{ $team->name }}
                                                            <small>({{ $team->users->where('pivot.is_active', true)->count() }} miembros)</small>
                                                        </option>
                                                        @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Rol en el equipo</label>
                                                    <select class="form-select" name="teams[0][role]">
                                                        @foreach($teamRoles as $value => $label)
                                                        <option value="{{ $value }}" {{ $value === 'DEVELOPER' ? 'selected' : '' }}>
                                                            {{ $label }}
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

                                        <button type="button" class="btn btn-outline-secondary" onclick="addTeam()">
                                            <i class="bi bi-people-plus me-2"></i>Agregar a Otro Equipo
                                        </button>
                                    </div>
                                </div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-lg me-2"></i>Crear Usuario
                                            </button>
                                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
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
                                    <i class="bi bi-1-circle me-2"></i>Información Personal
                                </h6>
                                <p class="small text-muted mb-0">
                                    Proporciona el nombre completo y un email válido. El email debe ser único en el sistema.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-2-circle me-2"></i>Contraseña Segura
                                </h6>
                                <p class="small text-muted mb-0">
                                    Crea una contraseña fuerte con al menos 8 caracteres. Incluye números y símbolos para mayor seguridad.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-3-circle me-2"></i>Proyectos y Equipos
                                </h6>
                                <p class="small text-muted mb-0">
                                    Asignar a proyectos agrega al equipo general. Los equipos personalizados son para roles específicos.
                                </p>
                            </div>

                            <div>
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-4-circle me-2"></i>Verificación
                                </h6>
                                <p class="small text-muted mb-0">
                                    Usuarios verificados pueden acceder inmediatamente. Los no verificados necesitarán confirmar su email.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Roles disponibles -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-secondary text-white py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-shield me-2"></i>
                                Roles del Sistema
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary">USER</span>
                                <small class="text-muted">Usuario Regular</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-danger">ADMIN</span>
                                <small class="text-muted">Administrador</small>
                            </div>
                        </div>
                    </div>

                    <!-- Roles de equipo disponibles -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-warning text-dark py-3">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-person-badge me-2"></i>
                                Roles en Equipos
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($teamRoles as $value => $label)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-dark">{{ $value }}</span>
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
                                        <div class="fw-bold h6 text-primary">{{ $stats['total_users'] ?? 0 }}</div>
                                        <small class="text-muted">Usuarios Totales</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-danger">{{ $stats['admin_users'] ?? 0 }}</div>
                                        <small class="text-muted">Administradores</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-success">{{ $stats['verified_users'] ?? 0 }}</div>
                                        <small class="text-muted">Verificados</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="fw-bold h6 text-warning">{{ $stats['total_teams'] ?? 0 }}</div>
                                        <small class="text-muted">Equipos</small>
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
    let projectIndex = 1;
    let teamIndex = 1;

    // Preparar los datos para JavaScript
    const projects = @json($projects);
    const teamRoles = @json($teamRoles);

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById('toggle' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1) + 'Icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function addProject() {
        const container = document.getElementById('user-projects');

        // Generar opciones de proyectos
        let projectOptions = '<option value="">Seleccionar proyecto...</option>';
        projects.forEach(project => {
            const membersCount = project.teams.length > 0 ? project.teams[0].users.filter(user => user.pivot.is_active).length : 0;
            projectOptions += `<option value="${project.id}">${project.title} (${membersCount} miembros)</option>`;
        });

        // Generar opciones de roles
        let roleOptions = '';
        Object.entries(teamRoles).forEach(([value, label]) => {
            const selected = value === 'DEVELOPER' ? 'selected' : '';
            roleOptions += `<option value="${value}" ${selected}>${label}</option>`;
        });

        const newProjectHtml = `
        <div class="row g-3 mb-3 project-assignment-row">
            <div class="col-md-6">
                <label class="form-label">Proyecto</label>
                <select class="form-select" name="projects[${projectIndex}][project_id]">
                    ${projectOptions}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Rol en el proyecto</label>
                <select class="form-select" name="projects[${projectIndex}][role]">
                    ${roleOptions}
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger w-100" onclick="removeProject(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', newProjectHtml);
        projectIndex++;
    }

    function removeProject(button) {
        const projectRow = button.closest('.project-assignment-row');
        projectRow.remove();
    }

    function addTeam() {
        const container = document.getElementById('user-teams');

        // Generar opciones de equipos personalizados
        let teamOptions = '<option value="">Seleccionar equipo...</option>';
        projects.forEach(project => {
            project.teams.forEach(team => {
                if (!team.is_general) {
                    const activeMembers = team.users.filter(user => user.pivot.is_active).length;
                    teamOptions += `<option value="${team.id}">${project.title} - ${team.name} (${activeMembers} miembros)</option>`;
                }
            });
        });

        // Generar opciones de roles
        let roleOptions = '';
        Object.entries(teamRoles).forEach(([value, label]) => {
            const selected = value === 'DEVELOPER' ? 'selected' : '';
            roleOptions += `<option value="${value}" ${selected}>${label}</option>`;
        });

        const newTeamHtml = `
        <div class="row g-3 mb-3 team-assignment-row">
            <div class="col-md-6">
                <label class="form-label">Equipo Personalizado</label>
                <select class="form-select" name="teams[${teamIndex}][team_id]">
                    ${teamOptions}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Rol en el equipo</label>
                <select class="form-select" name="teams[${teamIndex}][role]">
                    ${roleOptions}
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

        // Validación de contraseñas
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        function validatePasswords() {
            if (password.value && passwordConfirmation.value) {
                if (password.value !== passwordConfirmation.value) {
                    passwordConfirmation.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    passwordConfirmation.setCustomValidity('');
                }
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        }

        password.addEventListener('input', validatePasswords);
        passwordConfirmation.addEventListener('input', validatePasswords);
    });
</script>
@endpush
@endsection