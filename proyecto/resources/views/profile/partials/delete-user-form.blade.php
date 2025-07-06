<section>
    <header class="mb-4">
        <h4 class="fw-bold text-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Cuenta
        </h4>
        <p class="text-muted">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. 
            Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.
        </p>
    </header>

    <!-- Advertencias -->
    <div class="alert alert-danger" role="alert">
        <h6 class="alert-heading">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>¡Advertencia!
        </h6>
        <p class="mb-2">Esta acción es <strong>irreversible</strong>. Al eliminar tu cuenta:</p>
        <ul class="mb-0">
            <li>Se eliminarán todos tus proyectos y tareas</li>
            <li>Perderás acceso a todos los equipos</li>
            <li>Se borrarán todos tus comentarios</li>
            <li>No podrás recuperar esta información</li>
        </ul>
    </div>

    <!-- Botón para abrir modal -->
    <button type="button" 
            class="btn btn-danger btn-lg" 
            data-bs-toggle="modal" 
            data-bs-target="#confirmDeleteModal">
        <i class="bi bi-trash me-2"></i>Eliminar Cuenta
    </button>
</section>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirmar Eliminación de Cuenta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h5 class="text-center mb-3">¿Estás seguro de que quieres eliminar tu cuenta?</h5>
                    
                    <p class="text-muted text-center mb-4">
                        Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. 
                        Por favor ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.
                    </p>

                    <!-- Lista de datos que se eliminarán -->
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">Se eliminarán los siguientes datos:</h6>
                        <ul class="mb-0 small">
                            <li><strong>Información personal:</strong> Nombre, email y configuraciones</li>
                            <li><strong>Proyectos:</strong> Todos los proyectos que hayas creado</li>
                            <li><strong>Tareas:</strong> Todas las tareas asignadas y creadas por ti</li>
                            <li><strong>Comentarios:</strong> Todos tus comentarios en tareas</li>
                            <li><strong>Membresías:</strong> Tu participación en equipos</li>
                        </ul>
                    </div>

                    <!-- Campo de contraseña -->
                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-medium">
                            <i class="bi bi-lock me-1"></i>Confirma tu contraseña
                        </label>
                        <input type="password" 
                               class="form-control form-control-lg @error('password', 'userDeletion') is-invalid @enderror" 
                               id="delete_password" 
                               name="password" 
                               required
                               placeholder="Ingresa tu contraseña para confirmar">
                        @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Checkbox de confirmación adicional -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            Entiendo que esta acción es irreversible y acepto eliminar mi cuenta permanentemente
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="bi bi-trash me-1"></i>Eliminar Cuenta Definitivamente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Habilitar/deshabilitar botón de confirmación
document.getElementById('confirmDelete').addEventListener('change', function() {
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    deleteBtn.disabled = !this.checked;
});

// Mostrar modal si hay errores de validación
@if($errors->userDeletion->isNotEmpty())
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    deleteModal.show();
});
@endif
</script>
@endpush