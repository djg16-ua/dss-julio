{{-- ============================================================================ --}}
{{-- PÁGINA SOBRE NOSOTROS REDISEÑADA - resources/views/about.blade.php --}}
{{-- ============================================================================ --}}

@extends('layouts.app')

@section('title', 'Sobre Nosotros - TaskFlow')

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(45deg, #f8fafc 0%, #e2e8f0 100%);
        position: relative;
        overflow: hidden;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234f46e5' fill-opacity='0.05'%3E%3Ccircle cx='9' cy='9' r='5'/%3E%3Ccircle cx='51' cy='51' r='5'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .story-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .story-card:hover {
        transform: translateY(-5px);
    }

    .number-badge {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin: 0 auto 1.5rem;
    }

    .team-member {
        text-align: center;
        padding: 2rem;
        border-radius: 16px;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .team-member:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .team-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        position: relative;
        overflow: hidden;
    }

    .team-avatar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .value-item {
        text-align: center;
        padding: 2rem 1rem;
    }

    .value-icon {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 2rem;
        position: relative;
    }

    .timeline-container {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }

    .timeline-line {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 3rem;
        position: relative;
    }

    .timeline-item:nth-child(odd) {
        flex-direction: row;
    }

    .timeline-item:nth-child(even) {
        flex-direction: row-reverse;
    }

    .timeline-content {
        flex: 1;
        max-width: 45%;
        padding: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .timeline-content::before {
        content: '';
        position: absolute;
        top: 50%;
        width: 20px;
        height: 20px;
        background: white;
        border: 4px solid var(--primary-color);
        border-radius: 50%;
        transform: translateY(-50%);
    }

    .timeline-item:nth-child(odd) .timeline-content::before {
        right: -60px;
    }

    .timeline-item:nth-child(even) .timeline-content::before {
        left: -60px;
    }

    .stats-counter {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .culture-item {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        height: 100%;
        border: 1px solid #e2e8f0;
    }

    .quote-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .quote-section::before {
        content: '"';
        position: absolute;
        top: -50px;
        left: 50px;
        font-size: 200px;
        opacity: 0.1;
        font-family: serif;
        line-height: 1;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="about-hero py-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6 order-lg-1 order-2">
                <div class="badge bg-primary text-white px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-people-fill me-2"></i>Conoce al Equipo
                </div>
                <h1 class="display-4 fw-bold text-dark mb-4">
                    La historia detrás de <span class="text-primary">TaskFlow</span>
                </h1>
                <p class="lead text-muted mb-4">
                    Somos más que desarrolladores. Somos solucionadores de problemas, innovadores y
                    apasionados por crear herramientas que realmente marquen la diferencia en la vida laboral de las personas.
                </p>
                <div class="d-flex gap-3">
                    <a href="#team" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-down me-2"></i>Conocer al Equipo
                    </a>
                    <a href="#story" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-clock-history me-2"></i>Nuestra Historia
                    </a>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1">
                <div class="text-center position-relative">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-primary rounded-4 p-4 text-white text-center">
                                <i class="bi bi-lightbulb-fill fs-1"></i>
                                <h6 class="mt-2 mb-0">Innovación</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-secondary rounded-4 p-4 text-white text-center">
                                <i class="bi bi-heart-fill fs-1"></i>
                                <h6 class="mt-2 mb-0">Pasión</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success rounded-4 p-4 text-white text-center">
                                <i class="bi bi-people-fill fs-1"></i>
                                <h6 class="mt-2 mb-0">Equipo</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-warning rounded-4 p-4 text-dark text-center">
                                <i class="bi bi-trophy-fill fs-1"></i>
                                <h6 class="mt-2 mb-0">Excelencia</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="story-card h-100">
                    <div class="number-badge bg-primary">01</div>
                    <h3 class="fw-bold text-center mb-4">Nuestra Misión</h3>
                    <p class="text-center text-muted mb-4">
                        Democratizar la gestión de proyectos profesional, haciendo que herramientas de nivel empresarial
                        sean accesibles y fáciles de usar para equipos de todos los tamaños.
                    </p>
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center bg-light rounded-pill px-4 py-2">
                            <i class="bi bi-target text-primary me-2"></i>
                            <span class="fw-semibold">Enfoque en el Usuario</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="story-card h-100">
                    <div class="number-badge bg-secondary">02</div>
                    <h3 class="fw-bold text-center mb-4">Nuestra Visión</h3>
                    <p class="text-center text-muted mb-4">
                        Convertirnos en la plataforma de gestión de proyectos más querida del mundo,
                        siendo reconocidos por nuestra simplicidad, potencia y el impacto positivo en la productividad.
                    </p>
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center bg-light rounded-pill px-4 py-2">
                            <i class="bi bi-globe text-secondary me-2"></i>
                            <span class="fw-semibold">Impacto Global</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="stats-counter">3</div>
                <h5 class="fw-bold text-dark">Años de Experiencia</h5>
                <p class="text-muted">Perfeccionando nuestra solución</p>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="stats-counter">50+</div>
                <h5 class="fw-bold text-dark">Equipos Satisfechos</h5>
                <p class="text-muted">Confiando en TaskFlow</p>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="stats-counter">10k+</div>
                <h5 class="fw-bold text-dark">Tareas Completadas</h5>
                <p class="text-muted">A través de nuestra plataforma</p>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="stats-counter">99%</div>
                <h5 class="fw-bold text-dark">Satisfacción Cliente</h5>
                <p class="text-muted">Comprometidos con la calidad</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-4">Nuestros Valores Fundamentales</h2>
                <p class="lead text-muted">
                    Estos principios guían cada decisión que tomamos y cada línea de código que escribimos
                </p>
            </div>
        </div>

        <div class="values-grid">
            <div class="value-item">
                <div class="value-icon bg-primary text-white">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <h4 class="fw-bold mb-3">Simplicidad Poderosa</h4>
                <p class="text-muted">
                    Creemos que la tecnología más avanzada debe ser invisible para el usuario.
                    Complejidad en el backend, simplicidad en el frontend.
                </p>
            </div>

            <div class="value-item">
                <div class="value-icon bg-success text-white">
                    <i class="bi bi-shield-check-fill"></i>
                </div>
                <h4 class="fw-bold mb-3">Transparencia Total</h4>
                <p class="text-muted">
                    Sin letra pequeña, sin costos ocultos, sin complicaciones.
                    Lo que ves es lo que obtienes, siempre.
                </p>
            </div>

            <div class="value-item">
                <div class="value-icon bg-warning text-dark">
                    <i class="bi bi-rocket-takeoff-fill"></i>
                </div>
                <h4 class="fw-bold mb-3">Mejora Continua</h4>
                <p class="text-muted">
                    Cada día es una oportunidad para hacer TaskFlow un poco mejor.
                    Escuchamos, aprendemos y evolucionamos constantemente.
                </p>
            </div>

            <div class="value-item">
                <div class="value-icon bg-info text-white">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h4 class="fw-bold mb-3">Colaboración Auténtica</h4>
                <p class="text-muted">
                    No solo facilitamos la colaboración, la vivimos.
                    Nuestro equipo diverso es nuestro mayor activo.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Quote Section -->
<section class="quote-section py-5 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <blockquote class="blockquote mb-4">
                    <p class="fs-3 fw-light mb-4">
                        "TaskFlow nació de nuestra propia frustración. Después de probar docenas de herramientas,
                        decidimos crear la que siempre habíamos deseado usar."
                    </p>
                </blockquote>
                <div class="d-flex align-items-center justify-content-center">
                    <div class="bg-white rounded-circle p-3 me-3">
                        <i class="bi bi-person-fill text-primary fs-4"></i>
                    </div>
                    <div class="text-start">
                        <div class="fw-bold">Ana García</div>
                        <div class="small opacity-75">CEO & Fundadora</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section id="team" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-4">Conoce a Nuestro Equipo</h2>
                <p class="lead text-muted">
                    Un grupo diverso de profesionales unidos por la pasión de crear algo extraordinario
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="team-member">
                    <div class="team-avatar bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="bi bi-person-fill text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Ana García</h5>
                    <p class="text-primary fw-semibold mb-3">CEO & Fundadora</p>
                    <p class="text-muted small mb-3">
                        Ex-directora de producto en startups tecnológicas. MBA por IESE.
                        Especialista en transformación digital y liderazgo de equipos remotos.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="bi bi-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="team-member">
                    <div class="team-avatar bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="bi bi-person-fill text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Carlos Ruiz</h5>
                    <p class="text-secondary fw-semibold mb-3">CTO & Co-fundador</p>
                    <p class="text-muted small mb-3">
                        15 años desarrollando software escalable. Ingeniero Senior en Google.
                        Experto en arquitecturas cloud y experiencia de usuario.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="team-member">
                    <div class="team-avatar bg-gradient" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="bi bi-person-fill text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-1">María López</h5>
                    <p class="text-info fw-semibold mb-3">Head of Design</p>
                    <p class="text-muted small mb-3">
                        Diseñadora UX/UI con alma de psicóloga. Especialista en research y
                        en crear interfaces que realmente resuelven problemas humanos.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="#" class="btn btn-outline-info btn-sm rounded-pill">
                            <i class="bi bi-dribbble"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm rounded-pill">
                            <i class="bi bi-behance"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story Timeline -->
<section id="story" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-4">Nuestra Historia</h2>
                <p class="lead text-muted">
                    Cada gran historia tiene un comienzo humilde. Esta es la nuestra.
                </p>
            </div>
        </div>

        <div class="timeline-container">
            <div class="timeline-line"></div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="badge bg-primary text-white mb-3">2022</div>
                    <h5 class="fw-bold">El Problema Era Real</h5>
                    <p class="text-muted mb-0">
                        Ana y Carlos, trabajando en empresas diferentes, compartían la misma frustración:
                        las herramientas de gestión de proyectos eran demasiado complejas o demasiado simples.
                        Nada en el punto medio perfecto.
                    </p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="badge bg-secondary text-white mb-3">2023</div>
                    <h5 class="fw-bold">Los Primeros Bocetos</h5>
                    <p class="text-muted mb-0">
                        Durante 6 meses de noches y fines de semana, desarrollamos el primer prototipo.
                        María se unió al equipo aportando su experiencia en UX. El feedback de los primeros
                        usuarios fue brutalmente honesto y tremendamente valioso.
                    </p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="badge bg-success text-white mb-3">2024</div>
                    <h5 class="fw-bold">El Gran Salto</h5>
                    <p class="text-muted mb-0">
                        Decidimos apostar todo por TaskFlow. Dejamos nuestros trabajos, conseguimos
                        financiación inicial y nos mudamos a una pequeña oficina en Alicante.
                        Era aterrador y emocionante a partes iguales.
                    </p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="badge bg-warning text-dark mb-3">2025</div>
                    <h5 class="fw-bold">El Presente</h5>
                    <p class="text-muted mb-0">
                        Hoy, TaskFlow está ayudando a equipos en más de 20 países. Hemos aprendido que
                        la tecnología es importante, pero las personas son lo que realmente importa.
                        Y esto es solo el comienzo.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Culture -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-4">Nuestra Cultura</h2>
                <p class="lead text-muted">
                    Cómo trabajamos y por qué nos levantamos cada mañana con ganas de mejorar TaskFlow
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="culture-item">
                    <i class="bi bi-clock-fill text-primary fs-1 mb-3"></i>
                    <h5 class="fw-bold mb-3">Flexibilidad Real</h5>
                    <p class="text-muted">
                        Trabajo remoto, horarios flexibles y confianza mutua.
                        Porque la creatividad no funciona de 9 a 5.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="culture-item">
                    <i class="bi bi-book-fill text-success fs-1 mb-3"></i>
                    <h5 class="fw-bold mb-3">Aprendizaje Continuo</h5>
                    <p class="text-muted">
                        Budget anual para formación, conferencias y libros.
                        Porque quien deja de aprender, deja de crecer.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="culture-item">
                    <i class="bi bi-heart-fill text-danger fs-1 mb-3"></i>
                    <h5 class="fw-bold mb-3">Impacto Positivo</h5>
                    <p class="text-muted">
                        No solo queremos crear software, queremos mejorar
                        la vida laboral de las personas que lo usan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join Us -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">¿Quieres formar parte de nuestra historia?</h3>
                <p class="lead mb-0">
                    Siempre estamos buscando personas talentosas y apasionadas que quieran
                    ayudarnos a construir el futuro de la gestión de proyectos.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ url('/contact') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-envelope-heart me-2"></i>Contáctanos
                </a>
            </div>
        </div>
    </div>
</section>
@endsection