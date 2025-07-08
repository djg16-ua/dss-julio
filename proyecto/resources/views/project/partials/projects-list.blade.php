@if($projects->count() > 0)
    <div class="row g-3">
        @foreach($projects as $project)
            <div class="col-lg-6 col-xl-4">
                <div class="card border h-100 project-card" 
                     onclick="window.location.href='{{ route('project.show', $project) }}'"
                     style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-bold mb-0 flex-grow-1 me-2">{{ $project->title }}</h6>
                            <div class="d-flex flex-column align-items-end gap-1">
                                @switch($project->status)
                                    @case('ACTIVE')
                                        <span class="badge bg-success">Activo</span>
                                        @break
                                    @case('PENDING')
                                        <span class="badge bg-warning">Pendiente</span>
                                        @break
                                    @case('COMPLETED')
                                        <span class="badge bg-info">Completado</span>
                                        @break
                                    @case('PAUSED')
                                        <span class="badge bg-secondary">Pausado</span>
                                        @break
                                    @case('CANCELLED')
                                        <span class="badge bg-danger">Cancelado</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ $project->status }}</span>
                                @endswitch
                                
                                @if($project->public)
                                    <span class="badge bg-primary">
                                        <i class="bi bi-globe me-1"></i>Público
                                    </span>
                                @else
                                    <span class="badge bg-dark">
                                        <i class="bi bi-lock me-1"></i>Privado
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($project->description)
                            <p class="text-muted small mb-3">{{ Str::limit($project->description, 100) }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>
                                {{ $project->creator->name }}
                                @if($project->creator->id === auth()->id())
                                    <span class="text-primary">(Tú)</span>
                                @endif
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $project->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="bi bi-folder-plus display-1 text-muted"></i>
        </div>
        <h5 class="text-muted">No hay proyectos disponibles</h5>
        <p class="text-muted">
            @if(request()->hasAny(['search', 'status', 'public']))
                No se encontraron proyectos que coincidan con los filtros aplicados.
                <button type="button" class="btn btn-link p-0" onclick="document.getElementById('clear-filters').click()">Limpiar filtros</button>
            @else
                Aún no formas parte de ningún proyecto. 
                <a href="{{ route('project.create') }}" class="text-decoration-none">Crea tu primer proyecto</a>
            @endif
        </p>
    </div>
@endif