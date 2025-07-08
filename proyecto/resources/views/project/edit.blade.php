@extends('layouts.app')

@section('title', 'Editar Proyecto')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-primary">
                        <i class="bi bi-pencil-square me-3"></i>Editar Proyecto
                    </h1>
                    <p class="lead text-muted">
                        Actualiza la información del proyecto: <strong>{{ $project->title }}</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('project.show', $project) }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-eye me-2"></i>Ver Proyecto
                    </a>
                    <a href="{{ route('project.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Proyectos
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card feature-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle text-primary me-2"></i>Información del Proyecto
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Errores encontrados:</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('project.update', $project) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Información básica -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="title" class="form-label fw-bold">
                                                Título del Proyecto <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" 
                                                   name="title" 
                                                   value="{{ old('title', $project->title) }}" 
                                                   placeholder="Ej. E-commerce Platform"
                                                   required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label fw-bold">Estado del Proyecto</label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status">
                                                <option value="PENDING" {{ old('status', $project->status) == 'PENDING' ? 'selected' : '' }}>
                                                    ⏳ Pendiente
                                                </option>
                                                <option value="ACTIVE" {{ old('status', $project->status) == 'ACTIVE' ? 'selected' : '' }}>
                                                    ✅ Activo
                                                </option>
                                                <option value="DONE" {{ old('status', $project->status) == 'DONE' ? 'selected' : '' }}>
                                                    🎉 Completado
                                                </option>
                                                <option value="PAUSED" {{ old('status', $project->status) == 'PAUSED' ? 'selected' : '' }}>
                                                    ⏸️ Pausado
                                                </option>
                                                <option value="CANCELLED" {{ old('status', $project->status) == 'CANCELLED' ? 'selected' : '' }}>
                                                    ❌ Cancelado
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="public" class="form-label fw-bold">Privacidad</label>
                                            <select class="form-select @error('public') is-invalid @enderror" 
                                                    id="public" 
                                                    name="public">
                                                <option value="0" {{ old('public', $project->public ? '1' : '0') == '0' ? 'selected' : '' }}>
                                                    🔒 Privado (Solo miembros del proyecto)
                                                </option>
                                                <option value="1" {{ old('public', $project->public ? '1' : '0') == '1' ? 'selected' : '' }}>
                                                    🌍 Público (Visible para todos)
                                                </option>
                                            </select>
                                            @error('public')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Creado por</label>
                                            <div class="form-control-plaintext bg-light rounded p-2">
                                                <i class="bi bi-person me-2"></i>
                                                {{ $project->creator->name }}
                                                @if($project->creator->id === auth()->id())
                                                    <span class="badge bg-primary ms-2">Tú</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Describe el objetivo y alcance del proyecto...">{{ old('description', $project->description) }}</textarea>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Proporciona una descripción clara que ayude a los miembros del equipo a entender el proyecto.
                                    </div>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fechas -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label fw-bold">Fecha de Inicio</label>
                                            <input type="date" 
                                                   class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" 
                                                   name="start_date" 
                                                   value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label fw-bold">Fecha de Finalización</label>
                                            <input type="date" 
                                                   class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" 
                                                   name="end_date" 
                                                   value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Información sobre miembros y equipos -->
                                <div class="alert alert-info">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-info-circle me-2"></i>Gestión de Miembros y Equipos
                                    </h6>
                                    <p class="mb-2">
                                        Para gestionar los miembros del proyecto y crear equipos específicos, ve a la página de detalles del proyecto.
                                    </p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small>
                                                <i class="bi bi-people me-1"></i>
                                                <strong>Miembros actuales:</strong> {{ $project->users()->count() }} personas
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <small>
                                                <i class="bi bi-diagram-3 me-1"></i>
                                                <strong>Equipos personalizados:</strong> {{ $project->teams()->where('is_general', false)->count() }} equipos
                                            </small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('project.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-people me-1"></i>Gestionar Miembros y Equipos
                                        </a>
                                    </div>
                                </div>

                                <!-- Información adicional -->
                                <div class="alert alert-light border">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small>
                                                <i class="bi bi-calendar-plus me-1"></i>
                                                <strong>Creado:</strong> {{ $project->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <small>
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <strong>Última actualización:</strong> {{ $project->updated_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('project.show', $project) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Actualizar Proyecto
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Validación de fechas
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    
    if (startDate) {
        endDateInput.min = startDate;
        if (endDateInput.value && endDateInput.value < startDate) {
            endDateInput.value = '';
        }
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = this.value;
    const startDateInput = document.getElementById('start_date');
    
    if (endDate) {
        startDateInput.max = endDate;
        if (startDateInput.value && startDateInput.value > endDate) {
            startDateInput.value = '';
        }
    }
});

// Confirmación de cambio de estado
document.getElementById('status').addEventListener('change', function() {
    const status = this.value;
    const statusMessages = {
        'DONE': '¿Estás seguro de marcar este proyecto como COMPLETADO? Esto indicará que el proyecto ha finalizado exitosamente.',
        'CANCELLED': '¿Estás seguro de CANCELAR este proyecto? Esta acción indica que el proyecto no continuará.',
        'PAUSED': 'El proyecto será marcado como PAUSADO. Podrás reactivarlo más tarde.'
    };
    
    if (statusMessages[status]) {
        if (!confirm(statusMessages[status])) {
            // Restaurar valor anterior si el usuario cancela
            this.value = '{{ $project->status }}';
        }
    }
});
</script>
@endpush

<style>
.card {
    border: 1px solid #e3e6f0;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.form-control-plaintext {
    border: 1px solid #e3e6f0;
}

.feature-card {
    border: 1px solid #e3e6f0;
    border-radius: 0.75rem;
    transition: all 0.15s ease-in-out;
}

.feature-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}
</style>
@endsection