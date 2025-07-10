@if($tasks->count() > 0)
    <div class="list-group list-group-flush">
        @foreach($tasks->take(4) as $task)
        <div class="list-group-item border-0 px-0 clickable-task" 
             onclick="window.location.href='{{ route('task.show', [$task->module->project ?? app('request')->route('project'), $task]) }}'"
             style="cursor: pointer;">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <i class="bi bi-{{ $task->status === 'DONE' ? 'check-circle-fill text-success' : ($task->status === 'ACTIVE' ? 'play-circle-fill text-primary' : 'circle text-muted') }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $task->title }}</h6>
                            @if($task->description)
                                <p class="text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                            @endif
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge bg-{{ $task->priority === 'URGENT' ? 'danger' : ($task->priority === 'HIGH' ? 'warning' : ($task->priority === 'MEDIUM' ? 'info' : 'secondary')) }}">
                                    {{ $task->priority }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-folder me-1"></i>
                                    {{ $task->module->name ?? 'Sin módulo' }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $task->status === 'DONE' ? 'success' : ($task->status === 'ACTIVE' ? 'primary' : 'secondary') }}">
                                {{ $task->status }}
                            </span>
                            @if($task->end_date)
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($task->end_date)->format('d/m/Y') }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($tasks->count() > 4)
            @foreach($tasks->skip(4) as $task)
            <div class="list-group-item border-0 px-0 clickable-task hidden-task" 
                 onclick="window.location.href='{{ route('task.show', [$task->module->project ?? app('request')->route('project'), $task]) }}'"
                 style="cursor: pointer; display: none;">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="bi bi-{{ $task->status === 'DONE' ? 'check-circle-fill text-success' : ($task->status === 'ACTIVE' ? 'play-circle-fill text-primary' : 'circle text-muted') }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $task->title }}</h6>
                                @if($task->description)
                                    <p class="text-muted small mb-2">{{ Str::limit($task->description, 100) }}</p>
                                @endif
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge bg-{{ $task->priority === 'URGENT' ? 'danger' : ($task->priority === 'HIGH' ? 'warning' : ($task->priority === 'MEDIUM' ? 'info' : 'secondary')) }}">
                                        {{ $task->priority }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-folder me-1"></i>
                                        {{ $task->module->name ?? 'Sin módulo' }}
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $task->status === 'DONE' ? 'success' : ($task->status === 'ACTIVE' ? 'primary' : 'secondary') }}">
                                    {{ $task->status }}
                                </span>
                                @if($task->end_date)
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($task->end_date)->format('d/m/Y') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    
    @if($tasks->count() > 4)
        <div class="text-center mt-3">
            <button type="button" class="btn btn-link" id="show-more-tasks">
                Ver más <i class="bi bi-chevron-down"></i>
            </button>
            <button type="button" class="btn btn-link" id="show-less-tasks" style="display: none;">
                Ver menos <i class="bi bi-chevron-up"></i>
            </button>
        </div>
    @endif
@else
    <div class="text-center py-4">
        <i class="bi bi-{{ request('module_id') ? 'funnel' : 'check2-all' }} display-4 text-{{ request('module_id') ? 'muted' : 'success' }} mb-3"></i>
        <h6 class="text-muted">{{ request('module_id') ? 'No hay tareas en este módulo' : 'No hay tareas creadas' }}</h6>
        <p class="text-muted mb-0">{{ request('module_id') ? 'Selecciona otro módulo o limpia el filtro' : 'Este proyecto aún no tiene tareas asignadas' }}</p>
    </div>
@endif