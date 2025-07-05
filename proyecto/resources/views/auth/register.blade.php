@extends('layouts.app')

@section('title', 'Registro - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-kanban text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 mb-1 fw-bold">Crear Cuenta</h2>
                        <p class="text-muted">Únete a TaskFlow y organiza tus proyectos</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">Nombre Completo</label>
                            <input type="text"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                autofocus>
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email</label>
                            <input type="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username">
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
                                autocomplete="new-password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-medium">Confirmar Contraseña</label>
                            <input type="password"
                                class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password">
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto los <a href="#" class="text-primary text-decoration-none">términos y condiciones</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                        </button>
                    </form>

                    <div class="text-center">
                        <p class="text-muted">
                            ¿Ya tienes una cuenta?
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-medium">
                                Inicia sesión aquí
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection