@extends('layouts.app')

@section('title', 'Sobre Nosotros - TaskFlow')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold mb-4">Sobre TaskFlow</h1>
                <p class="lead">
                    Somos un equipo apasionado por transformar la manera en que los equipos gestionan sus proyectos y colaboran hacia el éxito.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="section-title">Nuestra Misión</h2>
                <p class="lead">
                    Empoderar a equipos de todo el mundo con herramientas intuitivas y potentes que simplifiquen la gestión de proyectos y potencien la colaboración.
                </p>
                <p>
                    Creemos que cada proyecto merece las mejores herramientas para alcanzar el éxito. Por eso, hemos desarrollado TaskFlow con un enfoque en la simplicidad, la eficiencia y la experiencia del usuario.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-bullseye display-1 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="section-title">Nuestros Valores</h2>
                <p class="section-subtitle">
                    Los principios que guían nuestro trabajo y definen nuestra cultura
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="text-center">
                    <div class="feature-icon primary mx-auto">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h4 class="fw-bold">Innovación</h4>
                    <p>Constantemente buscamos formas nuevas y mejores de resolver los desafíos de la gestión de proyectos.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center">
                    <div class="feature-icon secondary mx-auto">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h4 class="fw-bold">Colaboración</h4>
                    <p>Creemos en el poder del trabajo en equipo tanto internamente como con nuestros usuarios.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center">
                    <div class="feature-icon success mx-auto">
                        <i class="bi bi-gem"></i>
                    </div>
                    <h4 class="fw-bold">Calidad</h4>
                    <p>No comprometemos la calidad. Cada característica es cuidadosamente diseñada y probada.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="section-title">Nuestro Equipo</h2>
                <p class="section-subtitle">
                    Conoce a las personas que hacen posible TaskFlow
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-fill text-white fs-1"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-bold">Ana García</h5>
                        <p class="text-muted mb-2">CEO & Fundadora</p>
                        <p class="card-text">
                            Visionaria con más de 10 años de experiencia en gestión de proyectos y liderazgo de equipos tecnológicos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-secondary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-fill text-white fs-1"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-bold">Carlos Ruiz</h5>
                        <p class="text-muted mb-2">CTO</p>
                        <p class="card-text">
                            Arquitecto de software especializado en soluciones escalables y experiencia del usuario excepcional.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-success rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-fill text-white fs-1"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-bold">María López</h5>
                        <p class="text-muted mb-2">Directora de Producto</p>
                        <p class="card-text">
                            Experta en UX/UI con pasión por crear productos que realmente resuelvan problemas del mundo real.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="section-title">Nuestra Historia</h2>
                <p class="section-subtitle">
                    El viaje que nos ha llevado hasta aquí
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="timeline">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="badge bg-primary fs-6 p-2">2022</div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="fw-bold">Nace la Idea</h5>
                            <p>Identificamos la necesidad de una herramienta de gestión de proyectos más intuitiva y potente.</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="badge bg-secondary fs-6 p-2">2023</div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="fw-bold">Primer Prototipo</h5>
                            <p>Desarrollamos el primer prototipo funcional y comenzamos las pruebas con equipos beta.</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="badge bg-success fs-6 p-2">2024</div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="fw-bold">Lanzamiento Oficial</h5>
                            <p>TaskFlow sale al mercado con funcionalidades completas y un equipo de soporte dedicado.</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="badge bg-warning fs-6 p-2">2025</div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="fw-bold">Expansión y Crecimiento</h5>
                            <p>Nuevas funcionalidades, integraciones avanzadas y crecimiento del equipo para servir mejor a nuestros usuarios.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection