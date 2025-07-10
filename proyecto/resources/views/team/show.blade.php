@extends('layouts.app')

@section('title', $team->name . ' - ' . $project->title . ' - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header del equipo -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('team.index', $project) }}" class="btn btn-outline-secondary me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-bold text-primary mb-0">
                                <i class="bi bi-people me-3"></i>{{ $team->name }}
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-kanban me-1"></i>
                                <a href="{{ route('project.show', $project) }}" class="text-decoration-none">
                                    {{ $project->title }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
                        @if(!$team->is_general)
                            <a href="{{ route('team.edit', [$project, $team]) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Editar
                            </a>
                            @php
                                $currentUser = auth()->user();
                                $isProjectCreator = $project->created_by === $currentUser->id;
                                $isAdmin = $currentUser->role === 'ADMIN';
                            @endphp
                            @if($isProjectCreator || $isAdmin)
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="bi bi-trash me-2"></i>Eliminar
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del equipo -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(78, 115, 223, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary">Información del Equipo</h4>
                            </div>
                            
                            <!-- Líder del equipo -->
                            @php
                                $teamLead = $team->users->where('pivot.role', 'LEAD')->first();
                            @endphp
                            @if($teamLead)
                                <div class="mb-3">
                                    <h6 class="fw-bold text-primary">
                                        <i class="bi bi-star me-2"></i>Líder del Equipo
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($teamLead->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $teamLead->name }}</strong>
                                            @if($teamLead->id === auth()->id())
                                                <small class="text-primary">(Tú)</small>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ $teamLead->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Estadísticas del equipo - 2 cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-collection text-success mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $team->modules->count() }}</h5>
                                    <p class="text-muted mb-0 small">Módulos Asignados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card feature-card">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-people text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="fw-bold text-primary mb-1">{{ $team->users->where('pivot.is_active', true)->count() }}</h5>
                                    <p class="text-muted mb-0 small">Miembros del Equipo</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del equipo -->
                    @if($team->description)
                        <div class="card feature-card" style="max-height: 140px; overflow: hidden;">
                            <div class="card-body py-2 px-3">
                                <h6 class="fw-bold text-primary mb-1">
                                    <i class="bi bi-card-text me-2"></i>Descripción
                                </h6>
                                <div class="description-content">
                                    <p class="text-muted mb-0" style="line-height: 1.4;">
                                        {{ Str::limit($team->description, 324) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gestión de miembros del equipo -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-people-fill text-primary me-2"></i>
                                    Miembros del Equipo (<span id="members-count">{{ $team->users->where('pivot.is_active', true)->count() }}</span>)
                                </h5>
                                @php
                                    $currentUser = auth()->user();
                                    $isProjectCreator = $project->created_by === $currentUser->id;
                                    $isAdmin = $currentUser->role === 'ADMIN';
                                    $canManageMembers = $isProjectCreator || $isAdmin;
                                @endphp
                                @if($canManageMembers)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignMemberModal">
                                        <i class="bi bi-person-plus me-1"></i>Asignar Miembro
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="members-container">
                                @php
                                    $teamMembers = $team->users()->where('team_user.is_active', true)->get();
                                    
                                    // Orden jerárquico de roles
                                    $roleOrder = ['LEAD', 'SENIOR_DEV', 'DEVELOPER', 'JUNIOR_DEV', 'DESIGNER', 'TESTER', 'ANALYST', 'OBSERVER'];
                                    $sortedMembers = $teamMembers->sortBy(function($member) use ($roleOrder) {
                                        return array_search($member->pivot->role, $roleOrder);
                                    });
                                @endphp
                                
                                @if($sortedMembers->count() > 0)
                                    <div class="row g-3">
                                        @foreach($sortedMembers as $member)
                                            <div class="col-lg-4 col-md-6" data-member-id="{{ $member->id }}">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                                    <div class="d-flex align-items-center flex-grow-1">
                                                        <div class="avatar-circle me-3">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">{{ $member->name }}</h6>
                                                            <small class="text-muted">{{ $member->email }}</small>
                                                            <br>
                                                            @php
                                                                $teamLead = $team->users->where('pivot.role', 'LEAD')->first();
                                                                $isTeamLead = $teamLead && $teamLead->id === $currentUser->id;
                                                                $canEditRole = $isProjectCreator || $isAdmin || $isTeamLead;
                                                            @endphp
                                                            
                                                            @if($canEditRole && $member->pivot->role !== 'LEAD')
                                                                <select class="form-select form-select-sm role-select" 
                                                                        data-member-id="{{ $member->id }}" 
                                                                        style="max-width: 150px;">
                                                                    <option value="SENIOR_DEV" {{ $member->pivot->role === 'SENIOR_DEV' ? 'selected' : '' }}>Senior Dev</option>
                                                                    <option value="DEVELOPER" {{ $member->pivot->role === 'DEVELOPER' ? 'selected' : '' }}>Desarrollador</option>
                                                                    <option value="JUNIOR_DEV" {{ $member->pivot->role === 'JUNIOR_DEV' ? 'selected' : '' }}>Junior Dev</option>
                                                                    <option value="DESIGNER" {{ $member->pivot->role === 'DESIGNER' ? 'selected' : '' }}>Diseñador</option>
                                                                    <option value="TESTER" {{ $member->pivot->role === 'TESTER' ? 'selected' : '' }}>Tester</option>
                                                                    <option value="ANALYST" {{ $member->pivot->role === 'ANALYST' ? 'selected' : '' }}>Analista</option>
                                                                    <option value="OBSERVER" {{ $member->pivot->role === 'OBSERVER' ? 'selected' : '' }}>Observador</option>
                                                                </select>
                                                            @else
                                                                <span class="badge bg-{{ $member->pivot->role === 'LEAD' ? 'warning' : 'info' }}">
                                                                    {{ $member->pivot->role }}
                                                                </span>
                                                            @endif
                                                            
                                                            @if($member->id === auth()->id())
                                                                <span class="badge bg-success">Tú</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($canManageMembers && $member->pivot->role !== 'LEAD')
                                                        <button type="button" class="btn btn-outline-danger btn-sm ms-2" 
                                                                onclick="removeMember({{ $member->id }}, '{{ $member->name }}')"
                                                                title="Desasignar del equipo">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4" id="no-members-message">
                                        <i class="bi bi-people display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay miembros en el equipo</h6>
                                        <p class="text-muted mb-3">Asigna miembros para empezar a trabajar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gestión de módulos asignados -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-collection text-primary me-2"></i>
                                    Módulos Asignados (<span id="modules-count">{{ $team->modules->count() }}</span>)
                                </h5>
                                @if($canManageMembers)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignModuleModal">
                                        <i class="bi bi-plus-circle me-1"></i>Asignar Módulo
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="modules-container">
                                @if($team->modules->count() > 0)
                                    <div class="row g-3">
                                        @foreach($team->modules as $module)
                                            <div class="col-lg-4 col-md-6" data-module-id="{{ $module->id }}">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                                    <div class="d-flex align-items-center flex-grow-1">
                                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi bi-collection text-secondary" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">{{ $module->name }}</h6>
                                                            @if($module->description)
                                                                <small class="text-muted">{{ Str::limit($module->description, 50) }}</small>
                                                                <br>
                                                            @endif
                                                            
                                                            @php
                                                                $teamLead = $team->users->where('pivot.role', 'LEAD')->first();
                                                                $isTeamLead = $teamLead && $teamLead->id === $currentUser->id;
                                                                $canEditStatus = $isProjectCreator || $isAdmin || $isTeamLead;
                                                            @endphp
                                                            
                                                            @if($canEditStatus)
                                                                <select class="form-select form-select-sm status-select" 
                                                                        data-module-id="{{ $module->id }}" 
                                                                        style="max-width: 150px;">
                                                                    <option value="PENDING" {{ $module->status === 'PENDING' ? 'selected' : '' }}>⏳ Pendiente</option>
                                                                    <option value="ACTIVE" {{ $module->status === 'ACTIVE' ? 'selected' : '' }}>✅ Activo</option>
                                                                    <option value="DONE" {{ $module->status === 'DONE' ? 'selected' : '' }}>🎉 Completado</option>
                                                                    <option value="PAUSED" {{ $module->status === 'PAUSED' ? 'selected' : '' }}>⏸️ Pausado</option>
                                                                    <option value="CANCELLED" {{ $module->status === 'CANCELLED' ? 'selected' : '' }}>❌ Cancelado</option>
                                                                </select>
                                                            @else
                                                                @switch($module->status)
                                                                    @case('ACTIVE')
                                                                        <span class="badge bg-success">✅ Activo</span>
                                                                        @break
                                                                    @case('PENDING')
                                                                        <span class="badge bg-warning">⏳ Pendiente</span>
                                                                        @break
                                                                    @case('DONE')
                                                                        <span class="badge bg-info">🎉 Completado</span>
                                                                        @break
                                                                    @case('PAUSED')
                                                                        <span class="badge bg-secondary">⏸️ Pausado</span>
                                                                        @break
                                                                    @case('CANCELLED')
                                                                        <span class="badge bg-danger">❌ Cancelado</span>
                                                                        @break
                                                                    @default
                                                                        <span class="badge bg-light text-dark">{{ $module->status }}</span>
                                                                @endswitch
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($canManageMembers)
                                                        <button type="button" class="btn btn-outline-danger btn-sm ms-2" 
                                                                onclick="removeModule({{ $module->id }}, '{{ $module->name }}')"
                                                                title="Desasignar módulo">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4" id="no-modules-message">
                                        <i class="bi bi-collection display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay módulos asignados</h6>
                                        <p class="text-muted mb-3">Asigna módulos para organizar el trabajo del equipo</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actividad reciente -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                Actividad Reciente
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="d-flex mb-3">
                                    <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-plus-circle text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Equipo creado</h6>
                                        <p class="text-muted small mb-1">Se creó el equipo en el proyecto</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $team->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if($team->updated_at != $team->created_at)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-pencil text-secondary" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Equipo actualizado</h6>
                                            <p class="text-muted small mb-1">Se realizaron cambios en el equipo</p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $team->updated_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($team->users->count() > 0)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-people text-success" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Miembros asignados</h6>
                                            <p class="text-muted small mb-1">{{ $team->users->count() }} miembro(s) forman parte del equipo</p>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>
                                                Miembros: {{ $team->users->pluck('name')->join(', ') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif

                                @if($team->modules->count() > 0)
                                    <div class="d-flex mb-3">
                                        <div class="me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-collection text-info" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">Módulos asignados</h6>
                                            <p class="text-muted small mb-1">{{ $team->modules->count() }} módulo(s) asignado(s) al equipo</p>
                                            <small class="text-muted">
                                                <i class="bi bi-collection me-1"></i>
                                                Módulos: {{ $team->modules->pluck('name')->join(', ') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar miembro -->
@if($canManageMembers)
<div class="modal fade" id="assignMemberModal" tabindex="-1" aria-labelledby="assignMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignMemberModalLabel">Asignar Miembro al Equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignMemberForm">
                    @csrf
                    <div class="mb-3">
                        <label for="member-search" class="form-label">Buscar Miembro del Proyecto</label>
                        <input type="text" class="form-control" id="member-search" placeholder="Escribe nombre o email...">
                        <div class="form-text">Escribe al menos 2 caracteres para buscar entre los miembros del proyecto</div>
                    </div>
                    
                    <div id="search-results" class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        <!-- Resultados de búsqueda aparecerán aquí -->
                    </div>
                    
                    <div class="mb-3" id="selected-user" style="display: none;">
                        <label for="user-role" class="form-label">Rol en el equipo</label>
                        <select class="form-select" id="user-role">
                            <option value="DEVELOPER">💻 Desarrollador</option>
                            <option value="SENIOR_DEV">🚀 Desarrollador Senior</option>
                            <option value="JUNIOR_DEV">🌱 Desarrollador Junior</option>
                            <option value="DESIGNER">🎨 Diseñador</option>
                            <option value="TESTER">🧪 Tester</option>
                            <option value="ANALYST">📊 Analista</option>
                            <option value="OBSERVER">👀 Observador</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAssignMember" disabled>
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Asignar al Equipo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar módulo -->
<div class="modal fade" id="assignModuleModal" tabindex="-1" aria-labelledby="assignModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModuleModalLabel">Asignar Módulo al Equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignModuleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="module-select" class="form-label">Seleccionar Módulo</label>
                        <select class="form-select" id="module-select">
                            <option value="">Selecciona un módulo...</option>
                            @php
                                $availableModules = $project->modules->whereNotIn('id', $team->modules->pluck('id'));
                            @endphp
                            @foreach($availableModules as $module)
                                <option value="{{ $module->id }}">
                                    {{ $module->name }}
                                    @if($module->description)
                                        - {{ Str::limit($module->description, 60) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @if($availableModules->count() === 0)
                            <div class="form-text text-warning">
                                <i class="bi bi-info-circle me-1"></i>
                                Todos los módulos del proyecto ya están asignados a este equipo
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAssignModule" 
                        {{ $availableModules->count() === 0 ? 'disabled' : '' }}>
                    <span class="spinner-border spinner-border-sm me-2" role="status" style="display: none;"></span>
                    Asignar Módulo
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal de confirmación para eliminar equipo -->
@if(!$team->is_general && ($isProjectCreator || $isAdmin))
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>¿Estás seguro de que deseas eliminar el equipo "{{ $team->name }}"?</strong>
                </div>
                <p class="mb-3">Esta acción no se puede deshacer y se eliminarán:</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todos los miembros del equipo</li>
                    <li><i class="bi bi-x-circle text-danger me-2"></i>Todas las asignaciones de módulos</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('team.destroy', [$project, $team]) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Eliminar Equipo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
let selectedUserId = null;
let selectedModuleId = null;

// Variables de permisos desde PHP (CORREGIDAS)
const canManageMembers = @json($canManageMembers ?? false);
const canEditStatus = @json(($isProjectCreator ?? false) || ($isAdmin ?? false) || ($isTeamLead ?? false));

// Debug: verificar permisos
console.log('Permisos:', {canManageMembers, canEditStatus});

function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function removeMember(userId, userName) {
    console.log('Intentando remover miembro:', userId, userName);
    
    if (confirm(`¿Estás seguro de que quieres desasignar a ${userName} del equipo?`)) {
        const memberCard = document.querySelector(`[data-member-id="${userId}"]`);
        const button = memberCard.querySelector('.btn-outline-danger');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        button.disabled = true;
        
        // URL CORREGIDA
        const url = `{{ route('team.remove-member', [$project, $team, ':userId']) }}`.replace(':userId', userId);
        console.log('URL de eliminación:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            if (data.success) {
                memberCard.style.transition = 'all 0.3s ease';
                memberCard.style.opacity = '0';
                memberCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    memberCard.remove();
                    updateMembersCount();
                    checkIfNoMembers();
                }, 300);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            alert('Error al desasignar el miembro: ' + error.message);
        });
    }
}

function removeModule(moduleId, moduleName) {
    console.log('Intentando remover módulo:', moduleId, moduleName);
    
    if (confirm(`¿Estás seguro de que quieres desasignar el módulo "${moduleName}" del equipo?`)) {
        const moduleCard = document.querySelector(`[data-module-id="${moduleId}"]`);
        const button = moduleCard.querySelector('.btn-outline-danger');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        button.disabled = true;
        
        // URL CORREGIDA
        const url = `{{ route('team.remove-module', [$project, $team, ':moduleId']) }}`.replace(':moduleId', moduleId);
        console.log('URL de eliminación módulo:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta módulo:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos módulo:', data);
            if (data.success) {
                moduleCard.style.transition = 'all 0.3s ease';
                moduleCard.style.opacity = '0';
                moduleCard.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    moduleCard.remove();
                    updateModulesCount();
                    checkIfNoModules();
                }, 300);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error completo módulo:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            alert('Error al desasignar el módulo: ' + error.message);
        });
    }
}

function updateMembersCount() {
    const memberCards = document.querySelectorAll('[data-member-id]');
    const count = memberCards.length;
    const countElement = document.getElementById('members-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function updateModulesCount() {
    const moduleCards = document.querySelectorAll('[data-module-id]');
    const count = moduleCards.length;
    const countElement = document.getElementById('modules-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function checkIfNoMembers() {
    const memberCards = document.querySelectorAll('[data-member-id]');
    const container = document.getElementById('members-container');
    
    if (memberCards.length === 0 && container) {
        container.innerHTML = `
            <div class="text-center py-4" id="no-members-message">
                <i class="bi bi-people display-4 text-muted mb-3"></i>
                <h6 class="text-muted">No hay miembros en el equipo</h6>
                <p class="text-muted mb-3">Asigna miembros para empezar a trabajar</p>
            </div>
        `;
    }
}

function checkIfNoModules() {
    const moduleCards = document.querySelectorAll('[data-module-id]');
    const container = document.getElementById('modules-container');
    
    if (moduleCards.length === 0 && container) {
        container.innerHTML = `
            <div class="text-center py-4" id="no-modules-message">
                <i class="bi bi-collection display-4 text-muted mb-3"></i>
                <h6 class="text-muted">No hay módulos asignados</h6>
                <p class="text-muted mb-3">Asigna módulos para organizar el trabajo del equipo</p>
            </div>
        `;
    }
}

// Búsqueda de miembros para asignar
let searchTimeout;
const memberSearchInput = document.getElementById('member-search');
if (memberSearchInput) {
    memberSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        clearTimeout(searchTimeout);
        
        if (searchTerm.length < 2) {
            document.getElementById('search-results').innerHTML = '';
            document.getElementById('selected-user').style.display = 'none';
            document.getElementById('confirmAssignMember').disabled = true;
            selectedUserId = null;
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchMembers(searchTerm);
        }, 300);
    });
}

function searchMembers(term) {
    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer) return;
    
    resultsContainer.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <span class="ms-2">Buscando miembros...</span>
        </div>
    `;
    
    const url = `{{ route('team.available-members', [$project, $team]) }}?search=${encodeURIComponent(term)}`;
    console.log('URL búsqueda:', url);
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Respuesta búsqueda:', response);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(users => {
        console.log('Usuarios encontrados:', users);
        displaySearchResults(users);
    })
    .catch(error => {
        console.error('Error búsqueda:', error);
        resultsContainer.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-exclamation-triangle me-2"></i>Error al buscar miembros: ${error.message}
            </div>
        `;
    });
}

function displaySearchResults(users) {
    const container = document.getElementById('search-results');
    if (!container) return;
    
    if (users.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-person-x me-2"></i>No se encontraron miembros disponibles
            </div>
        `;
        return;
    }
    
    container.innerHTML = users.map(user => `
        <div class="border rounded p-2 mb-2 user-result" data-user-id="${user.id}" style="cursor: pointer;">
            <div class="d-flex align-items-center">
                <div class="avatar-circle me-2">
                    ${user.name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <strong>${user.name}</strong><br>
                    <small class="text-muted">${user.email}</small>
                </div>
            </div>
        </div>
    `).join('');
    
    // Añadir event listeners
    document.querySelectorAll('.user-result').forEach(element => {
        element.addEventListener('click', function() {
            selectUser(this.dataset.userId, users.find(u => u.id == this.dataset.userId));
        });
    });
}

function selectUser(userId, user) {
    selectedUserId = userId;
    console.log('Usuario seleccionado:', userId, user);
    
    // Resaltar usuario seleccionado
    document.querySelectorAll('.user-result').forEach(el => el.classList.remove('border-primary'));
    const selectedElement = document.querySelector(`[data-user-id="${userId}"]`);
    if (selectedElement) {
        selectedElement.classList.add('border-primary');
    }
    
    // Mostrar selector de rol
    const selectedUserDiv = document.getElementById('selected-user');
    const confirmButton = document.getElementById('confirmAssignMember');
    
    if (selectedUserDiv) {
        selectedUserDiv.style.display = 'block';
    }
    if (confirmButton) {
        confirmButton.disabled = false;
    }
}

// Confirmar asignar miembro
const confirmAssignButton = document.getElementById('confirmAssignMember');
if (confirmAssignButton) {
    confirmAssignButton.addEventListener('click', function() {
        if (!selectedUserId) return;
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        const roleSelect = document.getElementById('user-role');
        const role = roleSelect ? roleSelect.value : 'DEVELOPER';
        
        console.log('Asignando miembro:', selectedUserId, 'con rol:', role);
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('team.add-member', [$project, $team]) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: selectedUserId,
                role: role
            })
        })
        .then(response => {
            console.log('Respuesta asignar:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos asignar:', data);
            if (data.success) {
                const modal = document.getElementById('assignMemberModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                location.reload(); // Recargar para mostrar el nuevo miembro
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error asignar:', error);
            alert('Error al asignar el miembro: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Confirmar asignar módulo
const confirmAssignModuleButton = document.getElementById('confirmAssignModule');
if (confirmAssignModuleButton) {
    confirmAssignModuleButton.addEventListener('click', function() {
        const moduleSelect = document.getElementById('module-select');
        const moduleId = moduleSelect ? moduleSelect.value : null;
        
        if (!moduleId) {
            alert('Por favor selecciona un módulo');
            return;
        }
        
        console.log('Asignando módulo:', moduleId);
        
        const button = this;
        const spinner = button.querySelector('.spinner-border');
        
        // Mostrar loading
        if (spinner) spinner.style.display = 'inline-block';
        button.disabled = true;
        
        fetch(`{{ route('team.assign-module', [$project, $team]) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                module_id: moduleId
            })
        })
        .then(response => {
            console.log('Respuesta asignar módulo:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos asignar módulo:', data);
            if (data.success) {
                const modal = document.getElementById('assignModuleModal');
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
                location.reload(); // Recargar para mostrar el nuevo módulo
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error asignar módulo:', error);
            alert('Error al asignar el módulo: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.style.display = 'none';
            button.disabled = false;
        });
    });
}

// Cambio de rol de miembro
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('role-select')) {
        const memberId = e.target.dataset.memberId;
        const newRole = e.target.value;
        const originalValue = e.target.dataset.originalValue || 'DEVELOPER';
        
        console.log('Cambiando rol:', memberId, 'a', newRole);
        
        const url = `{{ route('team.update-member-role', [$project, $team, ':memberId']) }}`.replace(':memberId', memberId);
        console.log('URL cambio rol:', url);
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                role: newRole
            })
        })
        .then(response => {
            console.log('Respuesta cambio rol:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos cambio rol:', data);
            if (!data.success) {
                e.target.value = originalValue;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error cambio rol:', error);
            e.target.value = originalValue;
            alert('Error al actualizar el rol: ' + error.message);
        });
    }
});

// Cambio de estado de módulo
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('status-select')) {
        const moduleId = e.target.dataset.moduleId;
        const newStatus = e.target.value;
        const originalValue = e.target.dataset.originalValue || 'PENDING';
        
        console.log('Cambiando estado:', moduleId, 'a', newStatus);
        
        const url = `{{ route('team.update-module-status', [$project, $team, ':moduleId']) }}`.replace(':moduleId', moduleId);
        console.log('URL cambio estado:', url);
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => {
            console.log('Respuesta cambio estado:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos cambio estado:', data);
            if (!data.success) {
                e.target.value = originalValue;
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error cambio estado:', error);
            e.target.value = originalValue;
            alert('Error al actualizar el estado: ' + error.message);
        });
    }
});

// Guardar valores originales para poder revertir en caso de error
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando...');
    
    document.querySelectorAll('.role-select').forEach(select => {
        select.dataset.originalValue = select.value;
    });
    
    document.querySelectorAll('.status-select').forEach(select => {
        select.dataset.originalValue = select.value;
    });
});

// Limpiar modales al cerrarse
const assignMemberModal = document.getElementById('assignMemberModal');
if (assignMemberModal) {
    assignMemberModal.addEventListener('hidden.bs.modal', function() {
        const memberSearch = document.getElementById('member-search');
        const searchResults = document.getElementById('search-results');
        const selectedUser = document.getElementById('selected-user');
        const confirmButton = document.getElementById('confirmAssignMember');
        
        if (memberSearch) memberSearch.value = '';
        if (searchResults) searchResults.innerHTML = '';
        if (selectedUser) selectedUser.style.display = 'none';
        if (confirmButton) confirmButton.disabled = true;
        selectedUserId = null;
    });
}

const assignModuleModal = document.getElementById('assignModuleModal');
if (assignModuleModal) {
    assignModuleModal.addEventListener('hidden.bs.modal', function() {
        const moduleSelect = document.getElementById('module-select');
        const confirmButton = document.getElementById('confirmAssignModule');
        
        if (moduleSelect) moduleSelect.value = '';
        if (confirmButton) confirmButton.disabled = false;
    });
}

// Habilitar/deshabilitar botón de asignar módulo
const moduleSelect = document.getElementById('module-select');
if (moduleSelect) {
    moduleSelect.addEventListener('change', function() {
        const button = document.getElementById('confirmAssignModule');
        if (button) {
            button.disabled = !this.value;
        }
    });
}
</script>
@endpush

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #4e73df;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    flex-shrink: 0;
}

.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-title {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.avatar-sm .avatar-title {
    width: 30px;
    height: 30px;
    font-size: 12px;
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

.user-result:hover {
    background-color: #f8f9fc;
}

.user-result.border-primary {
    background-color: #e3f2fd;
}

.role-select, .status-select {
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.role-select:focus, .status-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}
</style>
@endsection