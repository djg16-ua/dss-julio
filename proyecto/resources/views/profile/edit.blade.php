@extends('layouts.app')

@section('title', 'Editar Perfil - TaskFlow')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-gear me-3"></i>Configuración del Perfil
                    </h1>
                    <p class="lead text-muted">
                        Actualiza tu información personal y configuración de seguridad
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Perfil
                    </a>
                </div>
            </div>

            <!-- Secciones del perfil -->
            <div class="row g-4">
                <!-- Información del perfil -->
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle text-primary me-2"></i>
                                Información del Perfil
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Cambiar contraseña -->
                <div class="col-12">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-shield-lock text-primary me-2"></i>
                                Actualizar Contraseña
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Eliminar cuenta -->
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-header bg-light py-3">
                            <h5 class="card-title mb-0 text-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Zona de Peligro
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection