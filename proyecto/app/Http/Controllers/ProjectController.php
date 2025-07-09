<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProjectController extends Controller
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
     * Mostrar todos los proyectos del usuario autenticado
     * Los usuarios acceden a proyectos a través de sus equipos
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener proyectos del usuario a través de sus equipos
        $query = Project::whereHas('teams.users', function($q) use ($user) {
            $q->where('users.id', $user->id)
              ->where('team_user.is_active', true);
        })->with(['creator', 'teams' => function($teamQuery) {
            $teamQuery->where('is_general', false); // Solo mostrar equipos personalizados
        }]);
        
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
        
        // Filtro por privacidad
        if ($request->filled('public')) {
            $query->where('public', $request->public == '1');
        }
        
        $projects = $query->orderByRaw("CASE 
            WHEN status = 'ACTIVE' THEN 1 
            WHEN status = 'PENDING' THEN 2 
            ELSE 3 
        END")
        ->orderBy('created_at', 'desc')
        ->get();

        // Estadísticas de proyectos del usuario
        $allUserProjects = Project::whereHas('teams.users', function($q) use ($user) {
            $q->where('users.id', $user->id)
              ->where('team_user.is_active', true);
        })->get();

        $stats = [
            'total_projects' => $allUserProjects->count(),
            'active_projects' => $allUserProjects->where('status', 'ACTIVE')->count(),
            'pending_projects' => $allUserProjects->where('status', 'PENDING')->count(),
            'completed_projects' => $allUserProjects->where('status', 'DONE')->count(),
            'public_projects' => $allUserProjects->where('public', true)->count(),
            'private_projects' => $allUserProjects->where('public', false)->count(),
        ];

        // Si es una petición AJAX, devolver solo el HTML de los proyectos
        if ($request->ajax() || $request->has('ajax')) {
            $html = view('project.partials.projects-list', compact('projects'))->render();
            return response()->json([
                'html' => $html,
                'count' => $projects->count()
            ]);
        }

        return view('project.index', compact('projects', 'stats'));
    }

    /**
     * Mostrar formulario de creación de proyecto
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Buscar usuarios para agregar al proyecto
     */
    public function searchUsers(Request $request)
    {
        $term = $request->get('term', '');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $currentUserId = Auth::id();
        
        $users = User::where('id', '!=', $currentUserId)
            ->where(function($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    /**
     * Almacenar un nuevo proyecto
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'public' => 'required|boolean',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'additional_members' => 'nullable|array',
            'additional_members.*' => 'exists:users,id',
            'member_roles' => 'nullable|array',
            'member_roles.*' => 'in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
            'additional_teams' => 'nullable|array',
            'additional_teams.*.name' => 'nullable|string|max:255',
            'additional_teams.*.description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            // Crear el proyecto (el equipo general se crea automáticamente en el boot del modelo)
            $project = Project::create([
                'title' => $request->title,
                'description' => $request->description,
                'public' => $request->boolean('public'),
                'status' => 'PENDING',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => $user->id,
            ]);

            // Obtener el equipo general creado automáticamente
            $generalTeam = $project->getGeneralTeam();

            // Añadir al creador al equipo general como LEAD
            $generalTeam->users()->attach($user->id, [
                'is_active' => true,
                'role' => self::TEAM_ROLE_LEAD,
                'joined_at' => now(),
            ]);

            // Añadir miembros adicionales al equipo general si se especificaron
            if ($request->has('additional_members') && is_array($request->additional_members)) {
                foreach ($request->additional_members as $memberData) {
                    if (!empty(trim($memberData))) {
                        // Separar userId y rol
                        $parts = explode('|', $memberData, 2);
                        $userId = intval(trim($parts[0]));
                        $role = isset($parts[1]) && !empty(trim($parts[1])) 
                            ? trim($parts[1]) 
                            : self::TEAM_ROLE_DEVELOPER; // Rol por defecto

                        if ($userId && $userId != $user->id) { // Evitar duplicar al creador
                            $generalTeam->users()->attach($userId, [
                                'is_active' => true,
                                'role' => $role,
                                'joined_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // Crear equipos adicionales si se especificaron
            if ($request->has('additional_teams') && is_array($request->additional_teams)) {
                foreach ($request->additional_teams as $teamData) {
                    if (!empty(trim($teamData))) {
                        // Separar nombre y descripción si están combinados con "|"
                        $parts = explode('|', $teamData, 2);
                        $teamName = trim($parts[0]);
                        $teamDescription = isset($parts[1]) && !empty(trim($parts[1])) 
                            ? trim($parts[1]) 
                            : 'Equipo especializado para el proyecto ' . $project->title;

                        if (!empty($teamName)) {
                            Team::create([
                                'name' => $teamName,
                                'description' => $teamDescription,
                                'project_id' => $project->id,
                                'is_general' => false,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('project.show', $project)
                ->with('success', 'Proyecto creado exitosamente. Se ha creado un equipo "General" con todos los miembros del proyecto.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el proyecto: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Mostrar un proyecto específico
     */
    public function show(Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto a través de sus equipos
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

        // Cargar relaciones necesarias - solo equipos personalizados (no el general)
        $project->load([
            'creator',
            'teams' => function($query) {
                $query->where('is_general', false)->with('users');
            },
            'modules.tasks.assignedUsers',
            'modules.tasks.creator'
        ]);

        // Estadísticas del proyecto
        $generalTeam = $project->getGeneralTeam();
        $projectStats = [
            'total_modules' => $project->modules->count(),
            'total_tasks' => $project->modules->sum(function($module) {
                return $module->tasks->count();
            }),
            'completed_tasks' => $project->modules->sum(function($module) {
                return $module->tasks->where('status', 'DONE')->count();
            }),
            'active_tasks' => $project->modules->sum(function($module) {
                return $module->tasks->where('status', 'ACTIVE')->count();
            }),
            'team_members' => $generalTeam ? $generalTeam->users->where('pivot.is_active', true)->count() : 0,
        ];

        return view('project.show', compact('project', 'projectStats'));
    }

    /**
     * Mostrar formulario de edición de proyecto
     */
    public function edit(Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
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

        return view('project.edit', compact('project'));
    }

    /**
     * Actualizar un proyecto existente
     */
    public function update(Request $request, Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto
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

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'public' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Actualizar datos del proyecto
            $project->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'public' => $request->boolean('public'),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return redirect()->route('project.show', $project)
                ->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el proyecto: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Eliminar un proyecto
     */
    public function destroy(Project $project)
    {
        // Solo el creador del proyecto puede eliminarlo
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Solo el creador del proyecto puede eliminarlo.');
        }

        DB::beginTransaction();
        
        try {
            // Los equipos se eliminan automáticamente por cascade (project_id foreign key)
            // Los módulos y tareas también se eliminan por cascade
            
            $project->delete();

            DB::commit();

            return redirect()->route('project.index')
                ->with('success', 'Proyecto eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar el proyecto: ' . $e->getMessage()]);
        }
    }

    /**
     * Agregar miembro al proyecto (lo añade al equipo general)
     */
    public function addMember(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Datos inválidos'], 422);
        }

        // Verificar acceso
        $userHasAccess = Auth::user()->teams()
            ->where('project_id', $project->id)
            ->whereHas('users', function($query) {
                $query->where('users.id', Auth::id())
                    ->where('team_user.is_active', true);
            })
            ->exists();

        if (!$userHasAccess) {
            return response()->json(['error' => 'No tienes acceso a este proyecto'], 403);
        }

        $generalTeam = $project->getGeneralTeam();
        
        // Verificar si el usuario ya está en el equipo general
        if ($generalTeam->users()->where('users.id', $request->user_id)->exists()) {
            return response()->json(['error' => 'El usuario ya pertenece al proyecto'], 422);
        }

        // Añadir al equipo general
        $generalTeam->users()->attach($request->user_id, [
            'is_active' => true,
            'role' => $request->role,
            'joined_at' => now(),
        ]);

        // Obtener datos del usuario para retornar al frontend
        $user = User::find($request->user_id);

        return response()->json([
            'success' => 'Miembro añadido al proyecto exitosamente',
            'member' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $request->role
            ]
        ]);
    }

    /**
     * Remover miembro del proyecto (lo quita del equipo general)
     */
    public function removeMember(Request $request, Project $project, User $user)
    {
        // Verificar acceso
        $userHasAccess = Auth::user()->teams()
            ->where('project_id', $project->id)
            ->whereHas('users', function($query) {
                $query->where('users.id', Auth::id())
                      ->where('team_user.is_active', true);
            })
            ->exists();

        if (!$userHasAccess) {
            return response()->json(['error' => 'No tienes acceso a este proyecto'], 403);
        }

        // No permitir que se elimine al creador del proyecto
        if ($user->id === $project->created_by) {
            return response()->json(['error' => 'No puedes eliminar al creador del proyecto'], 422);
        }

        $generalTeam = $project->getGeneralTeam();
        
        // Remover del equipo general
        $generalTeam->users()->detach($user->id);

        // También remover de todos los equipos personalizados del proyecto
        $project->teams()->where('is_general', false)->each(function($team) use ($user) {
            $team->users()->detach($user->id);
        });

        return response()->json(['success' => 'Miembro removido del proyecto exitosamente']);
    }
    
}