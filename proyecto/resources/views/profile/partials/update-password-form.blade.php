<section>
    <header class="mb-4">
        <h4 class="fw-bold text-dark">Actualizar Contraseña</h4>
        <p class="text-muted">
            Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerte seguro.
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <!-- Contraseña actual -->
            <div class="col-12">
                <label for="current_password" class="form-label fw-medium">
                    <i class="bi bi-lock me-1"></i>Contraseña Actual
                </label>
                <input type="password" 
                       class="form-control form-control-lg @error('current_password', 'updatePassword') is-invalid @enderror" 
                       id="current_password" 
                       name="current_password" 
                       required
                       autocomplete="current-password"
                       placeholder="Ingresa tu contraseña actual">
                @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nueva contraseña -->
            <div class="col-md-6">
                <label for="password" class="form-label fw-medium">
                    <i class="bi bi-key me-1"></i>Nueva Contraseña
                </label>
                <input type="password" 
                       class="form-control form-control-lg @error('password', 'updatePassword') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required
                       autocomplete="new-password"
                       placeholder="Mínimo 8 caracteres">
                @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    La contraseña debe tener al menos 8 caracteres
                </div>
            </div>

            <!-- Confirmar nueva contraseña -->
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label fw-medium">
                    <i class="bi bi-key-fill me-1"></i>Confirmar Nueva Contraseña
                </label>
                <input type="password" 
                       class="form-control form-control-lg @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       autocomplete="new-password"
                       placeholder="Repite la nueva contraseña">
                @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Consejos de seguridad -->
        <div class="alert alert-info mt-3" role="alert">
            <h6 class="alert-heading">
                <i class="bi bi-shield-check me-2"></i>Consejos de Seguridad
            </h6>
            <ul class="mb-0 small">
                <li>Usa una combinación de letras mayúsculas y minúsculas</li>
                <li>Incluye números y símbolos especiales</li>
                <li>Evita usar información personal como fechas de nacimiento</li>
                <li>No reutilices contraseñas de otras cuentas</li>
            </ul>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-shield-lock me-2"></i>Actualizar Contraseña
            </button>

            <button type="reset" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
            </button>

            @if (session('status') === 'password-updated')
            <div class="alert alert-success d-flex align-items-center mb-0 py-2 px-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>¡Contraseña actualizada correctamente!</div>
            </div>
            @endif
        </div>
    </form>

    <!-- Información adicional sobre seguridad -->
    <div class="mt-4">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-lightbulb text-info me-2"></i>
                    ¿Sabías que...?
                </h6>
                <p class="card-text small text-muted mb-0">
                    TaskFlow utiliza encriptación avanzada para proteger tu contraseña. 
                    Cambia tu contraseña regularmente y nunca la compartas con nadie.
                </p>
            </div>
        </div>
    </div>
</section>