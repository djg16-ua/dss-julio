<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Project;
use App\Models\Team;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    // Constantes para los estados de módulo
    const MODULE_STATUS_PENDING = 'PENDING';
    const MODULE_STATUS_ACTIVE = 'ACTIVE';
    const MODULE_STATUS_DONE = 'DONE';
    const MODULE_STATUS_PAUSED = 'PAUSED';
    const MODULE_STATUS_CANCELLED = 'CANCELLED';

    // Constantes para las prioridades de módulo
    const MODULE_PRIORITY_URGENT = 'URGENT';
    const MODULE_PRIORITY_HIGH = 'HIGH';
    const MODULE_PRIORITY_MEDIUM = 'MEDIUM';
    const MODULE_PRIORITY_LOW = 'LOW';

    // Constantes para los estados de tarea
    const TASK_STATUS_PENDING = 'PENDING';
    const TASK_STATUS_ACTIVE = 'ACTIVE';
    const TASK_STATUS_DONE = 'DONE';

    // Constantes para las prioridades de tarea
    const TASK_PRIORITY_URGENT = 'URGENT';
    const TASK_PRIORITY_HIGH = 'HIGH';
    const TASK_PRIORITY_MEDIUM = 'MEDIUM';
    const TASK_PRIORITY_LOW = 'LOW';

    /**
     * Mostrar módulos de un proyecto específico
     */
    public function index(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        $query = $project->modules()
            ->with(['teams', 'tasks' => function($taskQuery) {
                $taskQuery->select('id', 'module_id', 'status', 'priority');
            }])
            ->orderBy('created_at', 'desc');

        // Filtro por búsqueda (nombre o descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por prioridad
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filtro por equipo asignado
        if ($request->filled('team')) {
            $query->whereHas('teams', function($q) use ($request) {
                $q->where('teams.id', $request->team);
            });
        }

        $modules = $query->get();

        // Si es una petición AJAX, devolver solo el HTML
        if ($request->ajax()) {
            $html = view('module.partials.modules-list', compact('modules', 'project'))->render();
            return response()->json([
                'html' => $html,
                'count' => $modules->count()
            ]);
        }

        return view('module.index', compact('project', 'modules'));
    }

    /**
     * Mostrar formulario de creación de módulo
     */
    public function create(Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        return view('module.create', compact('project'));
    }

    /**
     * Almacenar un nuevo módulo
     */
    public function store(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:URGENT,HIGH,MEDIUM,LOW',
            'is_core' => 'boolean',
            'teams' => 'nullable|array',
            'teams.*' => 'exists:teams,id',
            'tasks' => 'nullable|array',
            'tasks.*' => 'string', // JSON strings
        ]);

        // Validar que el nombre no esté duplicado en el proyecto
        $validator->after(function ($validator) use ($request, $project) {
            if ($project->modules()->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'Ya existe un módulo con este nombre en el proyecto.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Crear el módulo
            $module = Module::create([
                'name' => $request->name,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => self::MODULE_STATUS_PENDING,
                'is_core' => $request->boolean('is_core', false),
                'project_id' => $project->id,
            ]);

            // Asignar equipos si se especificaron
            if ($request->has('teams') && is_array($request->teams)) {
                foreach ($request->teams as $teamId) {
                    // Solo permitir agregar equipos que pertenezcan al proyecto
                    if ($project->teams()->where('teams.id', $teamId)->exists()) {
                        $module->teams()->attach($teamId, [
                            'assigned_at' => now(),
                        ]);
                    }
                }
            }
            
            // Crear tareas iniciales si se especificaron
            if ($request->has('tasks') && is_array($request->tasks)) {
                foreach ($request->tasks as $taskJson) {
                    $taskData = json_decode($taskJson, true);
                    if ($taskData && !empty($taskData['title'])) {
                        Task::create([
                            'title' => $taskData['title'],
                            'description' => $taskData['description'] ?? null,
                            'priority' => $taskData['priority'],
                            'status' => self::TASK_STATUS_PENDING,
                            'module_id' => $module->id,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('module.show', ['project' => $project, 'module' => $module])
                ->with('success', 'Módulo creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el módulo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Mostrar un módulo específico
     */
    public function show(Project $project, Module $module)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            abort(404, 'Módulo no encontrado en este proyecto.');
        }

        // Cargar relaciones
        $module->load(['teams', 'tasks' => function($query) {
            $query->with('assignedUsers')->orderBy('created_at', 'desc');
        }]);

        // Estadísticas del módulo
        $moduleStats = [
            'total_tasks' => $module->tasks->count(),
            'completed_tasks' => $module->tasks->where('status', self::TASK_STATUS_DONE)->count(),
            'active_tasks' => $module->tasks->where('status', self::TASK_STATUS_ACTIVE)->count(),
            'pending_tasks' => $module->tasks->where('status', self::TASK_STATUS_PENDING)->count(),
            'assigned_teams' => $module->teams->count(),
        ];

        return view('module.show', compact('project', 'module', 'moduleStats'));
    }

    /**
     * Mostrar formulario de edición de módulo
     */
    public function edit(Project $project, Module $module)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            abort(404, 'Módulo no encontrado en este proyecto.');
        }

        // Solo el creador del proyecto o admin pueden editar módulos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isProjectCreator && !$isAdmin) {
            abort(403, 'No tienes permisos para editar este módulo.');
        }

        // Cargar relaciones necesarias
        $module->load(['teams.users', 'tasks']);

        return view('module.edit', compact('project', 'module'));
    }

    /**
     * Actualizar un módulo existente
     */
    public function update(Request $request, Project $project, Module $module)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            abort(404, 'Módulo no encontrado en este proyecto.');
        }

        // Solo el creador del proyecto o admin pueden editar módulos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isProjectCreator && !$isAdmin) {
            abort(403, 'No tienes permisos para editar este módulo.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:URGENT,HIGH,MEDIUM,LOW',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'is_core' => 'boolean',
        ]);

        // Validar que el nombre no esté duplicado en el proyecto (excluyendo el módulo actual)
        $validator->after(function ($validator) use ($request, $project, $module) {
            if ($project->modules()->where('name', $request->name)->where('id', '!=', $module->id)->exists()) {
                $validator->errors()->add('name', 'Ya existe un módulo con este nombre en el proyecto.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $module->update([
                'name' => $request->name,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => $request->status,
                'is_core' => $request->boolean('is_core', false),
            ]);

            return redirect()->route('module.show', ['project' => $project, 'module' => $module])
                ->with('success', 'Módulo actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el módulo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Eliminar un módulo
     */
    public function destroy(Project $project, Module $module)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            abort(404, 'Módulo no encontrado en este proyecto.');
        }

        // Solo el creador del proyecto o admin pueden eliminar módulos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isProjectCreator && !$isAdmin) {
            abort(403, 'No tienes permisos para eliminar este módulo.');
        }

        DB::beginTransaction();
        
        try {
            // Eliminar tareas del módulo
            $module->tasks()->delete();
            
            // Desasociar equipos del módulo
            $module->teams()->detach();
            
            // Eliminar el módulo
            $module->delete();

            DB::commit();

            return redirect()->route('module.index', $project)
                ->with('success', 'Módulo eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar el módulo: ' . $e->getMessage()]);
        }
    }

    /**
     * Asignar equipo al módulo
     */
    public function assignTeam(Request $request, Project $project, Module $module)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Solo creador del proyecto o admin pueden asignar equipos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isProjectCreator && !$isAdmin) {
            return response()->json(['error' => 'No tienes permisos para asignar equipos'], 403);
        }

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Datos inválidos'], 422);
        }

        // Verificar que el equipo pertenece al proyecto
        $team = $project->teams()->find($request->team_id);
        if (!$team) {
            return response()->json(['error' => 'El equipo no pertenece a este proyecto'], 422);
        }

        // Verificar que el equipo no está ya asignado al módulo
        if ($module->teams()->where('teams.id', $request->team_id)->exists()) {
            return response()->json(['error' => 'El equipo ya está asignado a este módulo'], 422);
        }

        // Asignar el equipo al módulo
        $module->teams()->attach($request->team_id, [
            'assigned_at' => now(),
        ]);

        return response()->json([
            'success' => 'Equipo asignado al módulo exitosamente',
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
                'description' => $team->description,
                'members_count' => $team->users->where('pivot.is_active', true)->count()
            ]
        ]);
    }

    /**
     * Desasignar equipo del módulo
     */
    public function removeTeam(Request $request, Project $project, Module $module, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Solo creador del proyecto o admin pueden desasignar equipos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isProjectCreator && !$isAdmin) {
            return response()->json(['error' => 'No tienes permisos para desasignar equipos'], 403);
        }

        // Verificar que el equipo está asignado al módulo
        if (!$module->teams()->where('teams.id', $team->id)->exists()) {
            return response()->json(['error' => 'El equipo no está asignado a este módulo'], 422);
        }

        // Desasignar el equipo del módulo
        $module->teams()->detach($team->id);

        return response()->json(['success' => 'Equipo desasignado del módulo exitosamente']);
    }

    /**
     * Obtener equipos disponibles para asignar al módulo
     */
    public function getAvailableTeams(Request $request, Project $project, Module $module)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Obtener equipos del proyecto que no están asignados a este módulo
        $query = $project->teams()->where('is_general', false); // Excluir equipo general
        
        // Filtrar por búsqueda si se proporciona
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('teams.name', 'like', "%{$search}%")
                  ->orWhere('teams.description', 'like', "%{$search}%");
            });
        }
        
        $projectTeams = $query->with('users')->get();
        $assignedTeamIds = $module->teams()->pluck('teams.id')->toArray();
        $availableTeams = $projectTeams->whereNotIn('id', $assignedTeamIds);

        return response()->json($availableTeams->map(function($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'description' => $team->description,
                'members_count' => $team->users->where('pivot.is_active', true)->count(),
                'is_general' => $team->is_general,
            ];
        })->values());
    }

    /**
     * Obtener equipos disponibles para asignar a un módulo en creación
     */
    public function getAvailableTeamsForCreate(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Obtener todos los equipos del proyecto excepto el general
        $query = $project->teams()->where('is_general', false);
        
        // Filtrar por búsqueda si se proporciona
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('teams.name', 'like', "%{$search}%")
                  ->orWhere('teams.description', 'like', "%{$search}%");
            });
        }
        
        $teams = $query->with(['users' => function($query) {
            $query->where('team_user.is_active', true);
        }])->get();

        return response()->json($teams->map(function($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'description' => $team->description,
                'members_count' => $team->users->count(),
                'is_general' => $team->is_general,
            ];
        })->values());
    }

    /**
     * Obtener miembros de los equipos asignados al módulo
     */
    public function getModuleTeamMembers(Request $request, Project $project, Module $module)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Obtener todos los usuarios de los equipos asignados al módulo
        $teamIds = $module->teams()->pluck('teams.id');
        
        if ($teamIds->isEmpty()) {
            return response()->json([]);
        }

        $members = User::whereHas('teams', function($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds)
                  ->where('team_user.is_active', true);
        })
        ->with(['teams' => function($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        }])
        ->distinct()
        ->get();

        return response()->json($members->map(function($member) use ($module) {
            $memberTeams = $member->teams->whereIn('id', $module->teams->pluck('id'));
            
            return [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'is_current_user' => $member->id === Auth::id(),
                'team_names' => $memberTeams->pluck('name')->join(', ')
            ];
        })->values());
    }

    /**
     * Crear nueva tarea en el módulo
     */
    public function createTask(Request $request, Project $project, Module $module)
    {
        \Log::info('=== INICIO createTask ===');
        \Log::info('Request completo:', $request->all());
        
        try {
            // Verificar que el usuario tiene acceso al proyecto
            $this->checkProjectAccess($project);
            \Log::info('Acceso al proyecto verificado');

            // Verificar que el módulo pertenece al proyecto
            if ($module->project_id !== $project->id) {
                \Log::error('Módulo no pertenece al proyecto');
                return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
            }
            \Log::info('Módulo verificado');

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'priority' => 'required|in:URGENT,HIGH,MEDIUM,LOW',
                'assigned_to' => 'nullable|array',
                'assigned_to.*' => 'exists:users,id',
            ]);

            if ($validator->fails()) {
                \Log::error('Validación falló:', $validator->errors()->toArray());
                return response()->json(['error' => 'Datos inválidos: ' . $validator->errors()->first()], 422);
            }
            \Log::info('Validación pasada');

            // Verificar usuarios si se especifican
            if ($request->filled('assigned_to') && is_array($request->assigned_to)) {
                \Log::info('Verificando usuarios asignados:', $request->assigned_to);
                
                $moduleTeamIds = $module->teams()->pluck('teams.id');
                \Log::info('IDs de equipos del módulo:', $moduleTeamIds->toArray());
                
                foreach ($request->assigned_to as $userId) {
                    $userInModuleTeams = User::whereHas('teams', function($query) use ($moduleTeamIds) {
                        $query->whereIn('teams.id', $moduleTeamIds)
                            ->where('team_user.is_active', true);
                    })->where('id', $userId)->exists();
                    
                    \Log::info('Usuario en equipos del módulo', ['user_id' => $userId, 'exists' => $userInModuleTeams]);
                    
                    if (!$userInModuleTeams) {
                        \Log::error('Usuario no está en equipos del módulo', ['user_id' => $userId]);
                        return response()->json(['error' => 'Uno o más usuarios asignados no pertenecen a los equipos del módulo'], 422);
                    }
                }
            }
            \Log::info('Usuarios verificados');

            // Crear la tarea
            \Log::info('Creando tarea con datos:', [
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => self::TASK_STATUS_PENDING,
                'module_id' => $module->id,
                'created_by' => Auth::id(),
            ]);

            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => self::TASK_STATUS_PENDING,
                'module_id' => $module->id,
                'created_by' => Auth::id(),
            ]);

            \Log::info('Tarea creada con ID:', ['task_id' => $task->id]);

            // Asignar usuarios si se especificaron
            if ($request->filled('assigned_to') && is_array($request->assigned_to)) {
                \Log::info('Asignando usuarios a la tarea');
                
                foreach ($request->assigned_to as $userId) {
                    \Log::info('Asignando usuario', ['user_id' => $userId]);
                    $task->assignedUsers()->attach($userId, [
                        'assigned_at' => now()
                    ]);
                }
                \Log::info('Usuarios asignados correctamente');
            }

            // Cargar la relación de usuarios asignados
            $task->load('assignedUsers');
            \Log::info('Relaciones cargadas');

            $response = [
                'success' => 'Tarea creada exitosamente',
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'status' => $task->status,
                    'assigned_to' => $task->assignedUsers->map(function($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                        ];
                    }),
                    'created_at' => $task->created_at->format('d/m/Y H:i'),
                ]
            ];

            \Log::info('Respuesta preparada:', $response);
            \Log::info('=== FIN createTask EXITOSO ===');
            
            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('=== ERROR EN createTask ===');
            \Log::error('Mensaje:', $e->getMessage());
            \Log::error('Archivo:', $e->getFile());
            \Log::error('Línea:', $e->getLine());
            \Log::error('Trace:', $e->getTraceAsString());
            \Log::error('=== FIN ERROR ===');
            
            return response()->json(['error' => 'Error al crear la tarea: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar tarea del módulo
     */
    public function removeTask(Request $request, Project $project, Module $module, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Verificar que la tarea pertenece al módulo
        if ($task->module_id !== $module->id) {
            return response()->json(['error' => 'La tarea no pertenece a este módulo'], 404);
        }

        // Solo el creador del proyecto, admin o creador de la tarea pueden eliminarla
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        $isTaskCreator = $task->created_by === $currentUser->id;
        
        if (!$isProjectCreator && !$isAdmin && !$isTaskCreator) {
            return response()->json(['error' => 'No tienes permisos para eliminar esta tarea'], 403);
        }

        try {
            $task->delete();
            return response()->json(['success' => 'Tarea eliminada exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la tarea: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar estado de tarea
     */
    public function updateTaskStatus(Request $request, Project $project, Module $module, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Verificar que la tarea pertenece al módulo
        if ($task->module_id !== $module->id) {
            return response()->json(['error' => 'La tarea no pertenece a este módulo'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:PENDING,ACTIVE,DONE',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Estado inválido'], 422);
        }

        try {
            $task->update(['status' => $request->status]);
            return response()->json(['success' => 'Estado de la tarea actualizado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el estado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar prioridad de tarea
     */
    public function updateTaskPriority(Request $request, Project $project, Module $module, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Verificar que la tarea pertenece al módulo
        if ($task->module_id !== $module->id) {
            return response()->json(['error' => 'La tarea no pertenece a este módulo'], 404);
        }

        $validator = Validator::make($request->all(), [
            'priority' => 'required|in:URGENT,HIGH,MEDIUM,LOW',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Prioridad inválida'], 422);
        }

        try {
            $task->update(['priority' => $request->priority]);
            return response()->json(['success' => 'Prioridad de la tarea actualizada exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la prioridad: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Asignar tarea a usuario
     */
    public function assignTaskToUser(Request $request, Project $project, Module $module, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return response()->json(['error' => 'Módulo no encontrado en este proyecto'], 404);
        }

        // Verificar que la tarea pertenece al módulo
        if ($task->module_id !== $module->id) {
            return response()->json(['error' => 'La tarea no pertenece a este módulo'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Usuario inválido'], 422);
        }

        // Verificar que el usuario está en el proyecto si se especifica
        if ($request->filled('user_id')) {
            $generalTeam = $project->getGeneralTeam();
            if (!$generalTeam || !$generalTeam->users()->where('users.id', $request->user_id)->exists()) {
                return response()->json(['error' => 'El usuario no pertenece al proyecto'], 422);
            }
        }

        try {
            $task->update(['assigned_to' => $request->user_id]);
            
            // Cargar la relación del usuario asignado
            $task->load('assignedUser');
            
            return response()->json([
                'success' => 'Tarea asignada exitosamente',
                'assigned_user' => $task->assignedUser ? [
                    'id' => $task->assignedUser->id,
                    'name' => $task->assignedUser->name,
                    'email' => $task->assignedUser->email,
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al asignar la tarea: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verificar que el usuario tiene acceso al proyecto
     */
    private function checkProjectAccess(Project $project)
    {
        $userHasAccess = Auth::user()->teams()
            ->where('project_id', $project->id)
            ->whereHas('users', function($query) {
                $query->where('users.id', Auth::id())
                      ->where('team_user.is_active', true);
            })
            ->exists();

        if (!$userHasAccess) {
            abort(403, 'No tienes acceso a este proyecto.');
        }
    }
}