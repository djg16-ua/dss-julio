<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    // Constantes para los roles de equipo
    const TEAM_ROLE_LEAD = 'LEAD';
    const TEAM_ROLE_SENIOR_DEV = 'SENIOR_DEV';
    const TEAM_ROLE_DEVELOPER = 'DEVELOPER';
    const TEAM_ROLE_JUNIOR_DEV = 'JUNIOR_DEV';
    const TEAM_ROLE_DESIGNER = 'DESIGNER';
    const TEAM_ROLE_TESTER = 'TESTER';
    const TEAM_ROLE_ANALYST = 'ANALYST';
    const TEAM_ROLE_OBSERVER = 'OBSERVER';

    /**
     * Mostrar equipos de un proyecto específico
     */
    public function index(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Obtener solo equipos personalizados (no el general)
        $query = $project->teams()
            ->where('is_general', false)
            ->with(['users' => function($userQuery) {
                $userQuery->where('team_user.is_active', true);
            }, 'modules'])
            ->orderBy('created_at', 'asc');

        // Filtro por búsqueda (nombre o descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por módulo asignado
        if ($request->filled('module')) {
            $query->whereHas('modules', function($q) use ($request) {
                $q->where('modules.id', $request->module);
            });
        }

        // Filtro por cantidad de miembros (se aplicará en la vista con JavaScript)
        $teams = $query->get();

        // Si es una petición AJAX, devolver solo el HTML
        if ($request->ajax()) {
            $html = view('team.partials.teams-list', compact('teams', 'project'))->render();
            return response()->json([
                'html' => $html,
                'count' => $teams->count()
            ]);
        }

        return view('team.index', compact('project', 'teams'));
    }

    /**
     * Mostrar formulario de creación de equipo
     */
    public function create(Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        return view('team.create', compact('project'));
    }

    /**
     * Almacenar un nuevo equipo
     */
    public function store(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
            'roles' => 'nullable|array',
            'roles.*' => 'in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        // Validar que el nombre no esté duplicado en el proyecto
        $validator->after(function ($validator) use ($request, $project) {
            if ($project->teams()->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'Ya existe un equipo con este nombre en el proyecto.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Crear el equipo
            $team = Team::create([
                'name' => $request->name,
                'description' => $request->description,
                'project_id' => $project->id,
                'is_general' => false,
            ]);

            // Añadir miembros si se especificaron
            if ($request->has('members') && is_array($request->members)) {
                $generalTeam = $project->getGeneralTeam();
                $projectMemberIds = $generalTeam ? $generalTeam->users()->pluck('users.id')->toArray() : [];

                foreach ($request->members as $index => $userId) {
                    // Solo permitir agregar usuarios que ya están en el proyecto (equipo general)
                    if (in_array($userId, $projectMemberIds)) {
                        $role = $request->roles[$index] ?? self::TEAM_ROLE_DEVELOPER;
                        
                        $team->users()->attach($userId, [
                            'is_active' => true,
                            'role' => $role,
                            'joined_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('team.show', ['project' => $project, 'team' => $team])
                ->with('success', 'Equipo creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el equipo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Mostrar un equipo específico
     */
    public function show(Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            abort(404, 'Equipo no encontrado en este proyecto.');
        }

        // No permitir ver el equipo general directamente
        if ($team->is_general) {
            abort(404, 'El equipo general no se puede visualizar directamente.');
        }

        // Cargar relaciones
        $team->load(['users' => function($query) {
            $query->where('team_user.is_active', true)
                  ->orderBy('team_user.joined_at', 'asc');
        }]);

        // Obtener miembros del proyecto disponibles para agregar
        $generalTeam = $project->getGeneralTeam();
        $projectMembers = $generalTeam ? $generalTeam->users()->where('team_user.is_active', true)->get() : collect();
        $currentTeamMemberIds = $team->users->pluck('id')->toArray();
        $availableMembers = $projectMembers->whereNotIn('id', $currentTeamMemberIds);

        return view('team.show', compact('project', 'team', 'availableMembers'));
    }

    /**
     * Mostrar formulario de edición de equipo
     */
    public function edit(Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            abort(404, 'Equipo no encontrado en este proyecto.');
        }

        // No permitir editar el equipo general
        if ($team->is_general) {
            abort(403, 'No se puede editar el equipo general del proyecto.');
        }

        return view('team.edit', compact('project', 'team'));
    }

    /**
     * Actualizar un equipo existente
     */
    public function update(Request $request, Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            abort(404, 'Equipo no encontrado en este proyecto.');
        }

        // No permitir editar el equipo general
        if ($team->is_general) {
            abort(403, 'No se puede editar el equipo general del proyecto.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Validar que el nombre no esté duplicado en el proyecto (excluyendo el equipo actual)
        $validator->after(function ($validator) use ($request, $project, $team) {
            if ($project->teams()->where('name', $request->name)->where('id', '!=', $team->id)->exists()) {
                $validator->errors()->add('name', 'Ya existe un equipo con este nombre en el proyecto.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $team->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('team.show', ['project' => $project, 'team' => $team])
                ->with('success', 'Equipo actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el equipo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Eliminar un equipo
     */
    public function destroy(Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            abort(404, 'Equipo no encontrado en este proyecto.');
        }

        // No permitir eliminar el equipo general
        if ($team->is_general) {
            abort(403, 'No se puede eliminar el equipo general del proyecto.');
        }

        DB::beginTransaction();
        
        try {
            // Desasociar usuarios del equipo
            $team->users()->detach();
            
            // Desasociar módulos del equipo
            $team->modules()->detach();
            
            // Eliminar el equipo
            $team->delete();

            DB::commit();

            return redirect()->route('team.index', $project)
                ->with('success', 'Equipo eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar el equipo: ' . $e->getMessage()]);
        }
    }

    /**
     * Añadir miembro a un equipo específico
     */
    public function addMember(Request $request, Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // No permitir modificar el equipo general
        if ($team->is_general) {
            return response()->json(['error' => 'No se puede modificar el equipo general'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Datos inválidos'], 422);
        }

        // Verificar que el usuario está en el proyecto (equipo general)
        $generalTeam = $project->getGeneralTeam();
        if (!$generalTeam || !$generalTeam->users()->where('users.id', $request->user_id)->exists()) {
            return response()->json(['error' => 'El usuario no pertenece al proyecto'], 422);
        }

        // Verificar que el usuario no está ya en el equipo
        if ($team->users()->where('users.id', $request->user_id)->exists()) {
            return response()->json(['error' => 'El usuario ya pertenece a este equipo'], 422);
        }

        // Añadir al equipo
        $team->users()->attach($request->user_id, [
            'is_active' => true,
            'role' => $request->role,
            'joined_at' => now(),
        ]);

        return response()->json(['success' => 'Miembro añadido al equipo exitosamente']);
    }

    /**
     * Remover miembro de un equipo específico
     */
    public function removeMember(Request $request, Project $project, Team $team, User $user)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // No permitir modificar el equipo general
        if ($team->is_general) {
            return response()->json(['error' => 'No se puede modificar el equipo general'], 403);
        }

        // Remover del equipo
        $team->users()->detach($user->id);

        return response()->json(['success' => 'Miembro removido del equipo exitosamente']);
    }

    /**
     * Actualizar rol de un miembro en el equipo
     */
    public function updateMemberRole(Request $request, Project $project, Team $team, User $user)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // No permitir modificar el equipo general directamente
        if ($team->is_general) {
            return response()->json(['error' => 'No se puede modificar el equipo general directamente'], 403);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Rol inválido'], 422);
        }

        // Verificar que el usuario está en el equipo
        if (!$team->users()->where('users.id', $user->id)->exists()) {
            return response()->json(['error' => 'El usuario no pertenece a este equipo'], 422);
        }

        // Actualizar rol
        $team->users()->updateExistingPivot($user->id, [
            'role' => $request->role,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => 'Rol actualizado exitosamente']);
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

    /**
     * Obtener miembros disponibles para agregar a un equipo (con búsqueda)
     */
    public function getAvailableMembers(Request $request, Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // Obtener miembros del proyecto que no están en este equipo
        $generalTeam = $project->getGeneralTeam();
        if (!$generalTeam) {
            return response()->json([]);
        }

        $query = $generalTeam->users()->where('team_user.is_active', true);
        
        // Filtrar por búsqueda si se proporciona
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                ->orWhere('users.email', 'like', "%{$search}%");
            });
        }
        
        $projectMembers = $query->get();
        $currentTeamMemberIds = $team->users()->pluck('users.id')->toArray();
        $availableMembers = $projectMembers->whereNotIn('id', $currentTeamMemberIds);

        return response()->json($availableMembers->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        })->values());
    }

    /**
     * Asignar módulo al equipo
     */
    public function assignModule(Request $request, Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // Solo creador del proyecto o admin pueden asignar módulos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isProjectCreator && !$isAdmin) {
            return response()->json(['error' => 'No tienes permisos para asignar módulos'], 403);
        }

        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Datos inválidos'], 422);
        }

        // Verificar que el módulo pertenece al proyecto
        $module = $project->modules()->find($request->module_id);
        if (!$module) {
            return response()->json(['error' => 'El módulo no pertenece a este proyecto'], 422);
        }

        // Verificar que el módulo no está ya asignado al equipo
        if ($team->modules()->where('modules.id', $request->module_id)->exists()) {
            return response()->json(['error' => 'El módulo ya está asignado a este equipo'], 422);
        }

        // Asignar el módulo al equipo
        $team->modules()->attach($request->module_id, [
            'assigned_at' => now(),
        ]);

        return response()->json([
            'success' => 'Módulo asignado al equipo exitosamente',
            'module' => [
                'id' => $module->id,
                'name' => $module->name,
                'description' => $module->description,
                'status' => $module->status
            ]
        ]);
    }

    /**
     * Desasignar módulo del equipo
     */
    public function removeModule(Request $request, Project $project, Team $team, $moduleId)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // Solo creador del proyecto o admin pueden desasignar módulos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        
        if (!$isProjectCreator && !$isAdmin) {
            return response()->json(['error' => 'No tienes permisos para desasignar módulos'], 403);
        }

        // Verificar que el módulo está asignado al equipo
        if (!$team->modules()->where('modules.id', $moduleId)->exists()) {
            return response()->json(['error' => 'El módulo no está asignado a este equipo'], 422);
        }

        // Desasignar el módulo del equipo
        $team->modules()->detach($moduleId);

        return response()->json(['success' => 'Módulo desasignado del equipo exitosamente']);
    }

    /**
     * Actualizar estado de módulo (opcional - si quieres permitir cambiar estados desde el equipo)
     */
    public function updateModuleStatus(Request $request, Project $project, Team $team, $moduleId)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // Verificar permisos
        $currentUser = Auth::user();
        $isProjectCreator = $project->created_by === $currentUser->id;
        $isAdmin = $currentUser->role === 'ADMIN';
        $teamLead = $team->users->where('pivot.role', 'LEAD')->first();
        $isTeamLead = $teamLead && $teamLead->id === $currentUser->id;
        
        if (!$isProjectCreator && !$isAdmin && !$isTeamLead) {
            return response()->json(['error' => 'No tienes permisos para actualizar el estado del módulo'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Estado inválido'], 422);
        }

        // Verificar que el módulo está asignado al equipo
        if (!$team->modules()->where('modules.id', $moduleId)->exists()) {
            return response()->json(['error' => 'El módulo no está asignado a este equipo'], 422);
        }

        // Buscar el módulo y actualizar su estado
        $module = $project->modules()->find($moduleId);
        if (!$module) {
            return response()->json(['error' => 'Módulo no encontrado'], 404);
        }

        $module->update(['status' => $request->status]);

        return response()->json(['success' => 'Estado del módulo actualizado exitosamente']);
    }
    /**
     * Obtener módulos disponibles para asignar a un equipo
     */
    public function getAvailableModules(Request $request, Project $project, Team $team)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Verificar que el equipo pertenece al proyecto
        if ($team->project_id !== $project->id) {
            return response()->json(['error' => 'Equipo no encontrado en este proyecto'], 404);
        }

        // Obtener módulos del proyecto que no están asignados a este equipo
        $query = $project->modules();
        
        // Filtrar por búsqueda si se proporciona
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('modules.name', 'like', "%{$search}%")
                ->orWhere('modules.description', 'like', "%{$search}%");
            });
        }
        
        $projectModules = $query->get();
        $assignedModuleIds = $team->modules()->pluck('modules.id')->toArray();
        $availableModules = $projectModules->whereNotIn('id', $assignedModuleIds);

        return response()->json($availableModules->map(function($module) {
            return [
                'id' => $module->id,
                'name' => $module->name,
                'description' => $module->description,
                'status' => $module->status,
                'priority' => $module->priority,
            ];
        })->values());
    }
}