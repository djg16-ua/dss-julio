@extends('layouts.app')

@section('title', 'Gestionar Equipos - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-warning">
                        <i class="bi bi-diagram-3 me-3"></i>Gestión de Equipos
                    </h1>
                    <p class="lead text-muted">
                        Administra todos los equipos del sistema TaskFlow
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                    </a>
                </div>
            </div>

            <!-- Estadísticas de equipos -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-warning">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_teams'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Equipos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-info">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['active_members'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Miembros Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card border-success">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $stats['total_assignments'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Asignaciones</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de equipos -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-table text-warning me-2"></i>
                                Lista de Equipos
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if($teams->count() > 0)
                                <div class="row g-4 p-4">
                                    @foreach($teams as $team)
                                    <div class="col-lg-6">
                                        <div class="card feature-card h-100">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-start justify-content-between mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="feature-icon secondary me-3">
                                                            <i class="bi bi-people-fill"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="fw-bold mb-1">{{ $team->name }}</h5>
                                                            <small class="text-muted">ID: {{ $team->id }}</small>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-outline-warning btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#teamModal{{ $team->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>

                                                <p class="text-muted mb-3">
                                                    {{ $team->description ?: 'Sin descripción disponible' }}
                                                </p>

                                                <!-- Estadísticas del equipo -->
                                                <div class="row g-2 mb-3">
                                                    <div class="col-4">
                                                        <div class="text-center p-2 bg-light rounded">
                                                            <h6 class="mb-0 text-primary">{{ $team->users_count }}</h6>
                                                            <small class="text-muted">Miembros</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="text-center p-2 bg-light rounded">
                                                            <h6 class="mb-0 text-success">{{ $team->projects_count }}</h6>
                                                            <small class="text-muted">Proyectos</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="text-center p-2 bg-light rounded">
                                                            <h6 class="mb-0 text-info">{{ $team->modules_count }}</h6>
                                                            <small class="text-muted">Módulos</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Miembros del equipo (preview) -->
                                                @if($team->users->count() > 0)
                                                <div class="mb-3">
                                                    <h6 class="fw-bold small mb-2">Miembros:</h6>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($team->users->take(3) as $user)
                                                        <span class="badge bg-primary">{{ $user->name }}</span>
                                                        @endforeach
                                                        @if($team->users->count() > 3)
                                                        <span class="badge bg-secondary">+{{ $team->users->count() - 3 }} más</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="text-muted small">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    Creado {{ $team->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de detalles del equipo -->
                                    <div class="modal fade" id="teamModal{{ $team->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-diagram-3 me-2"></i>
                                                        Equipo: {{ $team->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold">Información del Equipo</h6>
                                                            <table class="table table-sm">
                                                                <tr>
                                                                    <td><strong>Nombre:</strong></td>
                                                                    <td>{{ $team->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Descripción:</strong></td>
                                                                    <td>{{ $team->description ?: 'Sin descripción' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Miembros:</strong></td>
                                                                    <td>{{ $team->users_count }} usuarios</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Proyectos:</strong></td>
                                                                    <td>{{ $team->projects_count }} asignados</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Módulos:</strong></td>
                                                                    <td>{{ $team->modules_count }} trabajando</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Creado:</strong></td>
                                                                    <td>{{ $team->created_at->format('d/m/Y H:i') }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold">Miembros del Equipo</h6>
                                                            @if($team->users->count() > 0)
                                                                <div class="list-group list-group-flush">
                                                                    @foreach($team->users as $user)
                                                                    <div class="list-group-item border-0 px-0 py-2">
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="feature-icon primary me-2" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                                                                    <i class="bi bi-person-fill"></i>
                                                                                </div>
                                                                                <div>
                                                                                    <small class="fw-bold">{{ $user->name }}</small>
                                                                                    <br><small class="text-muted">{{ $user->email }}</small>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <span class="badge bg-primary">{{ $user->pivot->role }}</span>
                                                                                @if($user->pivot->is_active)
                                                                                    <span class="badge bg-success">Activo</span>
                                                                                @else
                                                                                    <span class="badge bg-secondary">Inactivo</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p class="text-muted">Este equipo no tiene miembros asignados</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Paginación -->
                                <div class="card-footer bg-white">
                                    {{ $teams->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-diagram-3 display-1 text-muted mb-3"></i>
                                    <h5 class="text-muted">No se encontraron equipos</h5>
                                    <p class="text-muted">Los equipos aparecerán aquí cuando sean creados</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightning-fill me-2"></i>
                                Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <button class="btn btn-outline-warning w-100">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Crear Nuevo Equipo
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-info w-100">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Asignar Miembros
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-success w-100">
                                        <i class="bi bi-kanban me-2"></i>
                                        Asignar Proyectos
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-graph-up me-2"></i>
                                        Ver Estadísticas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection