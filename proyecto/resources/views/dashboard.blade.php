@extends('layouts.app')

@section('title', 'Dashboard - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header de bienvenida -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-speedometer2 me-3"></i>Dashboard
                    </h1>
                    <p class="lead text-muted">
                        Bienvenido de vuelta, <strong>{{ Auth::user()->name }}</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="badge bg-{{ Auth::user()->isAdmin() ? 'primary' : 'secondary' }} fs-6 px-3 py-2">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>

            <!-- Cards de estadísticas -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="fw-bold text-primary">12</h3>
                            <p class="text-muted mb-0">Proyectos Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-check-square"></i>
                            </div>
                            <h3 class="fw-bold text-primary">34</h3>
                            <p class="text-muted mb-0">Tareas Completadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="fw-bold text-primary">8</h3>
                            <p class="text-muted mb-0">Miembros del Equipo</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h3 class="fw-bold text-primary">156h</h3>
                            <p class="text-muted mb-0">Horas Trabajadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección principal -->
            <div class="row g-4">
                <!-- Proyectos recientes -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-folder2-open text-primary me-2"></i>
                                Proyectos Recientes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>¡Bienvenido a TaskFlow!</strong> 
                                Aquí aparecerán tus proyectos cuando comiences a trabajar.
                            </div>
                            
                            <!-- Placeholder para proyectos -->
                            <div class="text-center py-4">
                                <i class="bi bi-folder-plus display-1 text-muted mb-3"></i>
                                <h6 class="text-muted">No tienes proyectos aún</h6>
                                <p class="text-muted mb-3">Crea tu primer proyecto para comenzar</p>
                                <button class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Crear Proyecto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-task text-primary me-2"></i>
                                Tareas Pendientes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-4">
                                <i class="bi bi-check2-all display-4 text-success mb-3"></i>
                                <h6 class="text-muted">¡Todo al día!</h6>
                                <p class="text-muted mb-0">No tienes tareas pendientes</p>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="card mt-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightning text-primary me-2"></i>
                                Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Nuevo Proyecto
                                </button>
                                <button class="btn btn-outline-secondary">
                                    <i class="bi bi-person-plus me-2"></i>Invitar Usuario
                                </button>
                                <button class="btn btn-outline-success">
                                    <i class="bi bi-gear me-2"></i>Configuración
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection