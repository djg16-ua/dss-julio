extends('layouts.app')

@section('title', 'Recuperar Contraseña - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-key text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 mb-1 fw-bold">Recuperar Contraseña</h2>
                        <p class="text-muted">Te enviaremos un enlace para restablecer tu contraseña</p>
                    </div>

                    <div class="mb-4 text-center">
                        <p class="text-muted">
                            ¿Olvidaste tu contraseña? No hay problema. Solo proporciona tu dirección de email
                            y te enviaremos un enlace para restablecer tu contraseña.
                        </p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email</label>
                            <input type="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus>
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bi bi-envelope me-2"></i>Enviar Enlace de Recuperación
                        </button>
                    </form>

                    <div class="text-center">
                        <p class="text-muted">
                            ¿Recordaste tu contraseña?
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-medium">
                                Volver a Iniciar Sesión
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection