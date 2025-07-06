<section>
    <header class="mb-4">
        <h4 class="fw-bold text-dark">Información del Perfil</h4>
        <p class="text-muted">
            Actualiza la información de tu cuenta y dirección de email.
        </p>
    </header>

    <!-- Formulario oculto para reenvío de verificación -->
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Formulario principal -->
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="row g-3">
            <!-- Nombre -->
            <div class="col-md-6">
                <label for="name" class="form-label fw-medium">Nombre Completo</label>
                <input type="text" 
                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus 
                       autocomplete="name">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <label for="email" class="form-label fw-medium">Dirección de Email</label>
                <input type="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       autocomplete="username">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Verificación de email -->
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <strong>Tu dirección de email no está verificada.</strong>
                            <button type="submit" form="send-verification" class="btn btn-link p-0 ms-2 text-decoration-underline">
                                Haz clic aquí para reenviar el email de verificación.
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success d-flex align-items-center mt-2" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>Se ha enviado un nuevo enlace de verificación a tu dirección de email.</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Información adicional del usuario -->
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Rol Actual</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-{{ $user->role === 'ADMIN' ? 'primary' : 'secondary' }} fs-6">
                                <i class="bi bi-shield-check me-1"></i>
                                {{ $user->role }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Miembro desde</label>
                        <div class="form-control-plaintext text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $user->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Estado de Verificación</label>
                        <div class="form-control-plaintext">
                            @if($user->email_verified_at)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Verificado
                            </span>
                            @else
                            <span class="badge bg-warning">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Pendiente
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle me-2"></i>Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
            <div class="alert alert-success d-flex align-items-center mb-0 py-2 px-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>¡Información actualizada correctamente!</div>
            </div>
            @endif
        </div>
    </form>
</section>