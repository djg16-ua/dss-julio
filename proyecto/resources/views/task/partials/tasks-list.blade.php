@if($tasks->count() > 0)
    @php
        // Ordenar tareas: primero por estado (ACTIVE, PENDING, DONE), luego por prioridad
        $statusOrder = ['ACTIVE' => 1, 'PENDING' => 2, 'DONE' => 3];
        $priorityOrder = ['URGENT' => 1, 'HIGH' => 2, 'MEDIUM' => 3, 'LOW' => 4];
        
        $sortedTasks = $tasks->sort(function($a, $b) use ($statusOrder, $priorityOrder) {
            $statusA = $statusOrder[$a->status] ?? 4;
            $statusB = $statusOrder[$b->status] ?? 4;
            
            if ($statusA !== $statusB) {
                return $statusA <=> $statusB;
            }
            
            $priorityA = $priorityOrder[$a->priority] ?? 5;
            $priorityB = $priorityOrder[$b->priority] ?? 5;
            
            return $priorityA <=> $priorityB;
        });
    @endphp
    
    <div class="row g-3">
        @foreach($sortedTasks as $task)
            <div class="col-lg-6 col-xl-4">
                <div class="card border h-100 task-card" 
                     onclick="window.location.href='{{ route('task.show', [$project, $task]) }}'"
                     style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-bold mb-0 flex-grow-1 me-2">{{ $task->title }}</h6>
                            <div class="d-flex flex-column align-items-end gap-1">
                                @switch($task->status)
                                    @case('ACTIVE')
                                        <span class="badge bg-success">✅ Activa</span>
                                        @break
                                    @case('PENDING')
                                        <span class="badge bg-warning">⏳ Pendiente</span>
                                        @break
                                    @case('DONE')
                                        <span class="badge bg-info">🎉 Completada</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ $task->status }}</span>
                                @endswitch
                                
                                @switch($task->priority)
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
                        
                        @if($task->description)
                            <p class="text-muted small mb-3">{{ Str::limit($task->description, 100) }}</p>
                        @endif
                        
                        <!-- Información del módulo -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <small class="text-muted fw-bold">
                                    <i class="bi bi-collection me-1"></i>Módulo:
                                </small>
                                <small class="text-primary ms-2">
                                    {{ $task->module->name }}
                                </small>
                            </div>
                        </div>
                        
                        <!-- Usuarios asignados -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted fw-bold">
                                    <i class="bi bi-people me-1"></i>Asignados:
                                </small>
                                <small class="text-muted">
                                    {{ $task->assignedUsers->count() }} usuario(s)
                                </small>
                            </div>
                            
                            @if($task->assignedUsers->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($task->assignedUsers->take(3) as $user)
                                        <div class="d-flex align-items-center avatar-circle" title="{{ $user->name }}">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 24px; height: 24px; font-size: 10px; font-weight: bold;">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($task->assignedUsers->count() > 3)
                                        <small class="text-muted align-self-center">
                                            +{{ $task->assignedUsers->count() - 3 }} más
                                        </small>
                                    @endif
                                </div>
                            @else
                                <small class="text-muted">Sin asignar</small>
                            @endif
                        </div>
                        
                        <!-- Comentarios y creador -->
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-chat-dots me-1"></i>
                                {{ $task->comments->count() }} comentario(s)
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>
                                {{ $task->creator->name }}
                            </small>
                        </div>
                        
                        <!-- Fecha de creación -->
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $task->created_at->format('d/m/Y') }}
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
            <i class="bi bi-check2-square display-1 text-muted"></i>
        </div>
        <h5 class="text-muted">No hay tareas disponibles</h5>
        <p class="text-muted">
            @if(request()->hasAny(['search', 'status', 'priority', 'module']))
                No se encontraron tareas que coincidan con los filtros aplicados.
                <button type="button" class="btn btn-link p-0" onclick="document.getElementById('clear-filters').click()">Limpiar filtros</button>
            @else
                Este proyecto aún no tiene tareas. 
                <a href="{{ route('task.create', $project) }}" class="text-decoration-none">Crea la primera tarea</a>
            @endif
        </p>
    </div>
@endif