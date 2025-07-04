@extends('layouts.app')

@section('title', 'Contacto - TaskFlow')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold mb-4">Contacta con Nosotros</h1>
                <p class="lead">
                    ¿Tienes alguna pregunta o necesitas ayuda? Estamos aquí para asistirte. Contáctanos y te responderemos lo antes posible.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form">
                    <h3 class="fw-bold mb-4">Envíanos un Mensaje</h3>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ url('/contact') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">Asunto *</label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Selecciona un asunto...</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>Consulta General</option>
                                    <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Soporte Técnico</option>
                                    <option value="sales" {{ old('subject') == 'sales' ? 'selected' : '' }}>Información de Ventas</option>
                                    <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Asociaciones</option>
                                    <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback del Producto</option>
                                </select>
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}"
                                    placeholder="+34 600 123 456">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="company" class="form-label">Empresa/Organización</label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror"
                                    id="company" name="company" value="{{ old('company') }}"
                                    placeholder="Nombre de tu empresa (opcional)">
                                @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Mensaje *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                    id="message" name="message" rows="5" required
                                    placeholder="Describe tu consulta o comentario...">{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Mínimo 10 caracteres</div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input @error('privacy') is-invalid @enderror"
                                        type="checkbox" id="privacy" name="privacy" value="1" required
                                        {{ old('privacy') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="privacy">
                                        Acepto la <a href="#" class="text-decoration-none">política de privacidad</a>
                                        y el tratamiento de mis datos personales *
                                    </label>
                                    @error('privacy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>Enviar Mensaje
                                </button>
                                <button type="reset" class="btn btn-outline-secondary btn-lg ms-2">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="h-100">
                    <h3 class="fw-bold mb-4">Información de Contacto</h3>

                    <div class="contact-info">
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="feature-icon primary me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Oficina Principal</h5>
                                    <p class="mb-0 text-muted">
                                        Calle Innovación 123<br>
                                        03690 San Vicente del Raspeig<br>
                                        Alicante, España
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="feature-icon secondary me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Teléfono</h5>
                                    <p class="mb-1 text-muted">
                                        <a href="tel:+34965123456" class="text-decoration-none text-muted">
                                            +34 965 123 456
                                        </a>
                                    </p>
                                    <small class="text-muted">Lun-Vie: 9:00 - 18:00</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="feature-icon success me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Email</h5>
                                    <p class="mb-1 text-muted">
                                        <a href="mailto:contacto@taskflow.com" class="text-decoration-none text-muted">
                                            contacto@taskflow.com
                                        </a>
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <a href="mailto:soporte@taskflow.com" class="text-decoration-none text-muted">
                                            soporte@taskflow.com
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="feature-icon primary me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Chat en Vivo</h5>
                                    <p class="mb-2 text-muted">Disponible 24/7</p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-chat-left-text me-1"></i>Iniciar Chat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-5">
                        <h5 class="fw-bold mb-3">Síguenos</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-outline-primary btn-sm" title="Twitter">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm" title="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm" title="GitHub">
                                <i class="bi bi-github"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                        </div>
                    </div>

                    <!-- FAQ Link -->
                    <div class="mt-5 p-4 bg-light rounded">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-question-circle-fill text-primary me-2"></i>
                            ¿Buscas respuestas rápidas?
                        </h6>
                        <p class="mb-3 small text-muted">
                            Consulta nuestra sección de preguntas frecuentes antes de contactarnos.
                        </p>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-question-circle me-1"></i>Ver FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="fw-bold text-center mb-4">
                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                    Nuestra Ubicación
                </h3>
                <p class="text-center text-muted mb-4">
                    Visítanos en nuestras oficinas en el corazón tecnológico de Alicante
                </p>
                <div class="ratio ratio-21x9 rounded overflow-hidden shadow">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3131.234567890123!2d-0.511677!3d38.387749!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzjCsDIzJzE1LjkiTiAwwrAzMCc0Mi4wIlc!5e0!3m2!1ses!2ses!4v1234567890123"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="text-center mt-3">
                    <a href="https://maps.google.com" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-map me-1"></i>Ver en Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Contact Options -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="fw-bold mb-4">Otras formas de contactarnos</h3>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="feature-icon secondary mx-auto mb-3">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h5 class="fw-bold">Soporte Técnico</h5>
                            <p class="text-muted">Para problemas técnicos específicos</p>
                            <a href="mailto:soporte@taskflow.com" class="btn btn-outline-secondary btn-sm">
                                Contactar Soporte
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <h5 class="fw-bold">Ventas</h5>
                            <p class="text-muted">Información sobre planes y precios</p>
                            <a href="mailto:ventas@taskflow.com" class="btn btn-outline-success btn-sm">
                                Hablar con Ventas
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="feature-icon primary mx-auto mb-3">
                                <i class="bi bi-handshake-fill"></i>
                            </div>
                            <h5 class="fw-bold">Partnerships</h5>
                            <p class="text-muted">Oportunidades de colaboración</p>
                            <a href="mailto:partnerships@taskflow.com" class="btn btn-outline-primary btn-sm">
                                Proponer Alianza
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection