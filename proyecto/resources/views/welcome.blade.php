@extends('layouts.app')

@section('title', 'Bienvenido a TaskFlow - Gestión de Proyectos')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Gestiona tus proyectos con <span class="text-warning">TaskFlow</span>
                </h1>
                <p class="lead mb-4">
                    La plataforma definitiva para organizar equipos, gestionar tareas y hacer seguimiento del progreso de tus proyectos de forma eficiente y colaborativa.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#" class="btn btn-light btn-lg">
                        <i class="bi bi-rocket-takeoff me-2"></i>Comenzar Gratis
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="mt-5 mt-lg-0">
                    <i class="bi bi-kanban-fill display-1 text-warning"></i>
                    <div class="mt-3">
                        <i class="bi bi-people-fill fs-1 text-light me-3"></i>
                        <i class="bi bi-graph-up-arrow fs-1 text-warning me-3"></i>
                        <i class="bi bi-clock-fill fs-1 text-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Proyectos Completados</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Equipos Activos</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Tiempo de Actividad</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Soporte Disponible</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title display-5">¿Por qué elegir TaskFlow?</h2>
                <p class="section-subtitle">
                    Descubre las funcionalidades que hacen de TaskFlow la mejor opción para gestionar tus proyectos
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon primary mx-auto">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="card-title fw-bold">Colaboración en Tiempo Real</h5>
                        <p class="card-text">
                            Trabaja con tu equipo de forma sincronizada. Asigna tareas, comenta avances y mantén a todos informados.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon secondary mx-auto">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="card-title fw-bold">Seguimiento del Progreso</h5>
                        <p class="card-text">
                            Visualiza el avance de tus proyectos con dashboards intuitivos y métricas en tiempo real.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon success mx-auto">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h5 class="card-title fw-bold">Automatización Inteligente</h5>
                        <p class="card-text">
                            Automatiza tareas repetitivas y optimiza tu flujo de trabajo con reglas personalizables.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon primary mx-auto">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="card-title fw-bold">Seguridad Avanzada</h5>
                        <p class="card-text">
                            Tus datos están protegidos con encriptación de nivel empresarial y backups automáticos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon secondary mx-auto">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h5 class="card-title fw-bold">Acceso Móvil</h5>
                        <p class="card-text">
                            Gestiona tus proyectos desde cualquier dispositivo con nuestra interfaz totalmente responsive.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon success mx-auto">
                            <i class="bi bi-puzzle"></i>
                        </div>
                        <h5 class="card-title fw-bold">Integraciones</h5>
                        <p class="card-text">
                            Conecta TaskFlow con tus herramientas favoritas: Slack, GitHub, Google Drive y más.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">¿Listo para transformar tu gestión de proyectos?</h2>
                <p class="section-subtitle">
                    Únete a miles de equipos que ya confían en TaskFlow para alcanzar sus objetivos
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="#" class="btn btn-primary btn-lg">
                        <i class="bi bi-rocket-takeoff me-2"></i>Comenzar Gratis
                    </a>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-calendar-check me-2"></i>Solicitar Demo
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection