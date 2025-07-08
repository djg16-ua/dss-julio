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
    public function index(Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
        $this->checkProjectAccess($project);

        // Obtener solo equipos personalizados (no el general)
        $teams = $project->teams()
            ->where('is_general', false)
            ->with(['users' => function($query) {
                $query->where('team_user.is_active', true);
            }])
            ->orderBy('created_at', 'asc')
            ->get();

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
     * Obtener miembros disponibles para agregar a un equipo
     */
    public function getAvailableMembers(Project $project, Team $team)
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

        $projectMembers = $generalTeam->users()->where('team_user.is_active', true)->get();
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