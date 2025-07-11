<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Module;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
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
     * Mostrar tareas de un proyecto específico
     */
    public function index(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Obtener todas las tareas del proyecto a través de sus módulos
        $query = Task::whereHas('module', function($q) use ($project) {
            $q->where('project_id', $project->id);
        })
        ->with(['module', 'assignedUsers', 'creator'])
        ->orderBy('created_at', 'desc');

        // Filtro por búsqueda (título o descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
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

        // Filtro por módulo
        if ($request->filled('module')) {
            $query->where('module_id', $request->module);
        }

        // Filtro por usuario asignado
        if ($request->filled('assigned_to')) {
            $query->whereHas('assignedUsers', function($q) use ($request) {
                $q->where('users.id', $request->assigned_to);
            });
        }

        $tasks = $query->get();

        // Si es una petición AJAX, devolver solo el HTML
        if ($request->ajax()) {
            $html = view('task.partials.tasks-list', compact('tasks', 'project'))->render();
            return response()->json([
                'html' => $html,
                'count' => $tasks->count()
            ]);
        }

        return view('task.index', compact('project', 'tasks'));
    }

    /**
     * Mostrar formulario de creación de tarea
     */
    public function create(Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        return view('task.create', compact('project'));
    }

    /**
     * Almacenar una nueva tarea
     */
    public function store(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:URGENT,HIGH,MEDIUM,LOW',
            'module_id' => 'required|exists:modules,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        // Validar que el módulo pertenece al proyecto
        $validator->after(function ($validator) use ($request, $project) {
            if ($request->module_id) {
                $module = Module::find($request->module_id);
                if (!$module || $module->project_id !== $project->id) {
                    $validator->errors()->add('module_id', 'El módulo no pertenece a este proyecto.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Crear la tarea
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => self::TASK_STATUS_PENDING,
                'module_id' => $request->module_id,
                'created_by' => Auth::id(),
            ]);

            // Asignar usuarios si se especificaron
            if ($request->has('assigned_users') && is_array($request->assigned_users)) {
                $module = Module::find($request->module_id);
                $moduleTeamMemberIds = $this->getModuleTeamMemberIds($module);
                
                foreach ($request->assigned_users as $userId) {
                    // Solo permitir agregar usuarios que están en equipos del módulo
                    if (in_array($userId, $moduleTeamMemberIds)) {
                        $task->assignedUsers()->attach($userId, [
                            'assigned_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

                // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarea creada exitosamente',
                    'task' => [
                        'id' => $task->id,
                        'title' => $task->title,
                        'status' => $task->status,
                        'priority' => $task->priority,
                        'module_name' => $task->module->name
                    ]
                ]);
            }

            return redirect()->route('task.show', ['project' => $project, 'task' => $task])
                ->with('success', 'Tarea creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Error al crear la tarea: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Error al crear la tarea: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Mostrar una tarea específica
     */
    public function show(Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            abort(404, 'Tarea no encontrada en este proyecto.');
        }

        // Cargar relaciones
        $task->load([
            'module',
            'assignedUsers',
            'creator',
            'comments' => function($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            }
        ]);

        // Estadísticas de la tarea
        $taskStats = [
            'assigned_users' => $task->assignedUsers->count(),
            'total_comments' => $task->comments->count(),
            'module_name' => $task->module->name,
            'creation_date' => $task->created_at->format('d/m/Y'),
            'last_update' => $task->updated_at->format('d/m/Y H:i'),
        ];

        return view('task.show', compact('project', 'task', 'taskStats'));
    }

    /**
     * Mostrar formulario de edición de tarea
     */
    public function edit(Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            abort(404, 'Tarea no encontrada en este proyecto.');
        }

        return view('task.edit', compact('project', 'task'));
    }

    /**
     * Actualizar una tarea existente
     */
    public function update(Request $request, Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
            }
            abort(404, 'Tarea no encontrada en este proyecto.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:URGENT,HIGH,MEDIUM,LOW',
            'status' => 'required|in:PENDING,ACTIVE,DONE',
        ]);

        if ($validator->fails()) {
            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => $request->status,
            ]);

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarea actualizada exitosamente',
                    'task' => [
                        'id' => $task->id,
                        'title' => $task->title,
                        'status' => $task->status,
                        'priority' => $task->priority,
                        'description' => $task->description
                    ]
                ]);
            }

            return redirect()->route('task.show', ['project' => $project, 'task' => $task])
                ->with('success', 'Tarea actualizada exitosamente.');

        } catch (\Exception $e) {
            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Error al actualizar la tarea: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar la tarea: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Eliminar una tarea
     */
    public function destroy(Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            abort(404, 'Tarea no encontrada en este proyecto.');
        }

        // Solo el creador del proyecto, admin o creador de la tarea pueden eliminarla
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        $isTaskCreator = $task->created_by === $currentUser->id;
        
        if (!$isProjectCreator && !$isAdmin && !$isTaskCreator) {
            abort(403, 'No tienes permisos para eliminar esta tarea.');
        }

        DB::beginTransaction();
        
        try {
            // Eliminar comentarios de la tarea
            $task->comments()->delete();
            
            // Desasociar usuarios de la tarea
            $task->assignedUsers()->detach();
            
            // Eliminar la tarea
            $task->delete();

            DB::commit();

            return redirect()->route('task.index', $project)
                ->with('success', 'Tarea eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar la tarea: ' . $e->getMessage()]);
        }
    }

    /**
     * Asignar usuario a la tarea
     */
    public function assignUser(Request $request, Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Datos inválidos'], 422);
        }

        // Verificar que el usuario está en equipos del módulo
        $moduleTeamMemberIds = $this->getModuleTeamMemberIds($task->module);
        if (!in_array($request->user_id, $moduleTeamMemberIds)) {
            return response()->json(['error' => 'El usuario no pertenece a ningún equipo asignado al módulo'], 422);
        }

        // Verificar que el usuario no está ya asignado a la tarea
        if ($task->assignedUsers()->where('users.id', $request->user_id)->exists()) {
            return response()->json(['error' => 'El usuario ya está asignado a esta tarea'], 422);
        }

        // Asignar el usuario a la tarea
        $task->assignedUsers()->attach($request->user_id, [
            'assigned_at' => now(),
        ]);

        // Obtener información del usuario asignado
        $user = User::find($request->user_id);

        return response()->json([
            'success' => 'Usuario asignado a la tarea exitosamente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Desasignar usuario de la tarea
     */
    public function removeUser(Request $request, Project $project, Task $task, User $user)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
        }

        // Verificar que el usuario está asignado a la tarea
        if (!$task->assignedUsers()->where('users.id', $user->id)->exists()) {
            return response()->json(['error' => 'El usuario no está asignado a esta tarea'], 422);
        }

        // Desasignar el usuario de la tarea
        $task->assignedUsers()->detach($user->id);

        return response()->json(['success' => 'Usuario desasignado de la tarea exitosamente']);
    }

    /**
     * Obtener usuarios disponibles para asignar a la tarea
     */
    public function getAvailableUsers(Request $request, Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
        }

        // Obtener miembros de equipos del módulo que no están asignados a la tarea
        $moduleTeamMembers = $this->getModuleTeamMembersHelper($task->module);
        $assignedUserIds = $task->assignedUsers()->pluck('users.id')->toArray();
        $availableUsers = $moduleTeamMembers->whereNotIn('id', $assignedUserIds);

        // Filtrar por búsqueda si se proporciona
        if ($request->filled('search')) {
            $search = $request->search;
            $availableUsers = $availableUsers->filter(function($user) use ($search) {
                return stripos($user->name, $search) !== false || 
                       stripos($user->email, $search) !== false;
            });
        }

        return response()->json($availableUsers->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'team_names' => $user->team_names ?? ''
            ];
        })->values());
    }

    /**
     * Obtener miembros de equipos del módulo para asignación
     */
    public function getModuleTeamMembersAPI(Request $request, Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
        }

        $moduleTeamMembers = $this->getModuleTeamMembers($task->module);

        return response()->json($moduleTeamMembers->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_current_user' => $user->id === Auth::id(),
                'team_names' => $user->team_names ?? ''
            ];
        })->values());
    }

    /**
     * Añadir comentario a la tarea
     */
    public function addComment(Request $request, Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'El comentario es requerido y no puede exceder 2000 caracteres'], 422);
        }

        try {
            $comment = Comment::create([
                'content' => $request->content,
                'task_id' => $task->id,
                'user_id' => Auth::id(),
            ]);

            // Cargar la relación del usuario
            $comment->load('user');

            return response()->json([
                'success' => 'Comentario añadido exitosamente',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                    ],
                    'created_at' => $comment->created_at->format('d/m/Y H:i'),
                    'can_delete' => true, // El usuario puede eliminar su propio comentario
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al añadir el comentario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar comentario de la tarea
     */
    public function deleteComment(Request $request, Project $project, Task $task, Comment $comment)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
        }

        // Verificar que el comentario pertenece a la tarea
        if ($comment->task_id !== $task->id) {
            return response()->json(['error' => 'El comentario no pertenece a esta tarea'], 404);
        }

        // Solo el autor del comentario, creador del proyecto o admin pueden eliminar
        $currentUser = Auth::user();
        $isCommentAuthor = $comment->user_id === $currentUser->id;
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isCommentAuthor && !$isProjectCreator && !$isAdmin) {
            return response()->json(['error' => 'No tienes permisos para eliminar este comentario'], 403);
        }

        try {
            $comment->delete();
            return response()->json(['success' => 'Comentario eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el comentario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar estado de tarea
     */
    public function updateStatus(Request $request, Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
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
    public function updatePriority(Request $request, Project $project, Task $task)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que la tarea pertenece al proyecto
        if ($task->module->project_id !== $project->id) {
            return response()->json(['error' => 'Tarea no encontrada en este proyecto'], 404);
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
     * Obtener miembros de equipos asignados al módulo
     */
    private function getModuleTeamMembersHelper(Module $module)
    {
        // Obtener todos los usuarios de los equipos asignados al módulo
        $teamMembers = collect();
        
        foreach ($module->teams as $team) {
            $members = $team->users()
                ->where('team_user.is_active', true)
                ->get()
                ->map(function($user) use ($team) {
                    $user->team_names = $team->name;
                    return $user;
                });
            
            $teamMembers = $teamMembers->merge($members);
        }
        
        // Eliminar duplicados por ID de usuario
        return $teamMembers->unique('id');
    }

    /**
     * Obtener IDs de miembros de equipos asignados al módulo
     */
    private function getModuleTeamMemberIds(Module $module)
    {
        return $this->getModuleTeamMembersHelper($module)->pluck('id')->toArray();
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