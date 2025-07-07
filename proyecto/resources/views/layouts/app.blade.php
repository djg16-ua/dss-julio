<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TaskFlow - Gestión de Proyectos')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-gray: #f8fafc;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #374151;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 0;
        }

        .feature-card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-icon.primary {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }

        .feature-icon.secondary {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--secondary-color);
        }

        .feature-icon.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .section-title {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 2rem;
        }

        .section-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 3rem;
        }

        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 3rem 0 2rem;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary-color) !important;
        }

        .contact-form {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .stats-section {
            background-color: var(--light-gray);
            padding: 4rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: #6b7280;
            font-weight: 500;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-kanban me-2"></i>
                TaskFlow
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">
                            <i class="bi bi-info-circle me-1"></i>Sobre Nosotros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">
                            <i class="bi bi-envelope me-1"></i>Contacto
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @guest
                    <!-- Mostrar solo si NO está autenticado -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i>Registro
                        </a>
                    </li>
                    @else
                    <!-- Mostrar solo si SÍ está autenticado -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-danger ms-2">ADMIN</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person me-2"></i>Mi Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear me-2"></i>Configuración
                                </a>
                            </li>
                            
                            @if(Auth::user()->isAdmin())
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-primary fw-bold" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-gear me-2"></i>Panel de Administración
                                </a>
                            </li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Formulario oculto para logout -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-kanban me-2"></i>TaskFlow
                    </h5>
                    <p class="text-light">
                        La herramienta definitiva para gestionar proyectos de forma eficiente y colaborativa.
                        Organiza tu trabajo y potencia tu productividad.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Producto</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Funcionalidades</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Precios</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Integraciones</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Documentación</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-light text-decoration-none">Contacto</a></li>
                        <li><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">Conecta con nosotros</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light">
                            <i class="bi bi-twitter fs-5"></i>
                        </a>
                        <a href="#" class="text-light">
                            <i class="bi bi-linkedin fs-5"></i>
                        </a>
                        <a href="#" class="text-light">
                            <i class="bi bi-github fs-5"></i>
                        </a>
                        <a href="#" class="text-light">
                            <i class="bi bi-envelope fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="my-4 text-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light">&copy; {{ date('Y') }} TaskFlow. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-light">
                        Desarrollado con <i class="bi bi-heart-fill text-danger"></i> por el equipo TaskFlow
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>