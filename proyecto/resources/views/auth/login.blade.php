@extends('layouts.app')

@section('title', 'Iniciar Sesión - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-kanban text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 mb-1 fw-bold">Iniciar Sesión</h2>
                        <p class="text-muted">Accede a tu cuenta de TaskFlow</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
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
                                autocomplete="username"
                                autofocus>
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">Contraseña</label>
                            <input type="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">
                                Recordarme
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>
                    </form>

                    <div class="text-center">
                        <p class="text-muted">
                            ¿No tienes una cuenta?
                            <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-medium">
                                Regístrate aquí
                            </a>
                        </p>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-question-circle me-1"></i>¿Olvidaste tu contraseña?
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection