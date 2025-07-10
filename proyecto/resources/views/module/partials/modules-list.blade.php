@if($modules->count() > 0)
    @php
        // Ordenar módulos: primero por estado (ACTIVE, PENDING, resto), luego por prioridad
        $statusOrder = ['ACTIVE' => 1, 'PENDING' => 2, 'DONE' => 3, 'PAUSED' => 4, 'CANCELLED' => 5];
        $priorityOrder = ['URGENT' => 1, 'HIGH' => 2, 'MEDIUM' => 3, 'LOW' => 4];
        
        $sortedModules = $modules->sort(function($a, $b) use ($statusOrder, $priorityOrder) {
            $statusA = $statusOrder[$a->status] ?? 6;
            $statusB = $statusOrder[$b->status] ?? 6;
            
            if ($statusA !== $statusB) {
                return $statusA <=> $statusB;
            }
            
            $priorityA = $priorityOrder[$a->priority] ?? 5;
            $priorityB = $priorityOrder[$b->priority] ?? 5;
            
            return $priorityA <=> $priorityB;
        });
    @endphp
    
    <div class="row g-3">
        @foreach($sortedModules as $module)
            <div class="col-lg-6 col-xl-4">
                <div class="card border h-100 module-card" 
                     onclick="window.location.href='{{ route('module.show', [$project, $module]) }}'"
                     style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-bold mb-0 flex-grow-1 me-2">{{ $module->name }}</h6>
                            <div class="d-flex flex-column align-items-end gap-1">
                                @switch($module->status)
                                    @case('ACTIVE')
                                        <span class="badge bg-success">✅ Activo</span>
                                        @break
                                    @case('PENDING')
                                        <span class="badge bg-warning">⏳ Pendiente</span>
                                        @break
                                    @case('DONE')
                                        <span class="badge bg-info">🎉 Completado</span>
                                        @break
                                    @case('PAUSED')
                                        <span class="badge bg-secondary">⏸️ Pausado</span>
                                        @break
                                    @case('CANCELLED')
                                        <span class="badge bg-danger">❌ Cancelado</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ $module->status }}</span>
                                @endswitch
                                
                                @switch($module->priority)
                                    @case('URGENT')
                                        <span class="badge bg-danger">🚨 Urgente</span>
                                        @break
                                    @case('HIGH')
                                        <span class="badge bg-warning">⚡ Alta</span>
                                        @break
                                    @case('MEDIUM')
                                        <span class="badge bg-info">📋 Media</span>
                                        @break
                                    @case('LOW')
                                        <span class="badge bg-secondary">📝 Baja</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        
                        @if($module->description)
                            <p class="text-muted small mb-3">{{ Str::limit($module->description, 100) }}</p>
                        @endif
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted fw-bold">
                                    <i class="bi bi-check2-square me-1"></i>Tareas:
                                </small>
                                <small class="text-muted">
                                    {{ $module->tasks->count() }} total
                                </small>
                            </div>
                            
                            @if($module->tasks->count() > 0)
                                <div class="row g-1">
                                    <div class="col-4">
                                        <small class="text-success">
                                            <i class="bi bi-check-circle-fill"></i> {{ $module->tasks->where('status', 'DONE')->count() }}
                                        </small>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-primary">
                                            <i class="bi bi-play-circle-fill"></i> {{ $module->tasks->where('status', 'ACTIVE')->count() }}
                                        </small>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-warning">
                                            <i class="bi bi-clock-fill"></i> {{ $module->tasks->where('status', 'PENDING')->count() }}
                                        </small>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($module->is_core)
                                    <i class="bi bi-star-fill text-warning me-1"></i>Módulo Core
                                @else
                                    <i class="bi bi-circle me-1"></i>Módulo Estándar
                                @endif
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-people me-1"></i>
                                {{ $module->teams->count() }} equipo(s)
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
            <i class="bi bi-collection-plus display-1 text-muted"></i>
        </div>
        <h5 class="text-muted">No hay módulos disponibles</h5>
        <p class="text-muted">
            @if(request()->hasAny(['search', 'status', 'priority']))
                No se encontraron módulos que coincidan con los filtros aplicados.
                <button type="button" class="btn btn-link p-0" onclick="document.getElementById('clear-filters').click()">Limpiar filtros</button>
            @else
                Este proyecto aún no tiene módulos. 
                <a href="{{ route('module.create', $project) }}" class="text-decoration-none">Crea el primer módulo</a>
            @endif
        </p>
    </div>
@endif