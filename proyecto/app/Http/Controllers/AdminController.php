<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use App\Models\Task;
use App\Models\Module;
use App\Models\Comment;

class AdminController extends Controller
{
    /**
     * Middleware para verificar que el usuario es admin
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Acceso denegado. Solo administradores.');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard principal de administración
     */
    public function dashboard()
    {
        // Estadísticas generales
        $stats = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_teams' => Team::count(),
            'total_tasks' => Task::count(),
            'total_modules' => Module::count(),
            'total_comments' => Comment::count(),
            'active_projects' => Project::where('status', 'ACTIVE')->count(),
            'completed_tasks' => Task::where('status', 'DONE')->count(),
            'admin_users' => User::where('role', 'ADMIN')->count(),
        ];

        // Proyectos recientes
        $recentProjects = Project::with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Usuarios recientes
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Tareas por estado
        $tasksByStatus = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Proyectos por estado
        $projectsByStatus = Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'stats',
            'recentProjects',
            'recentUsers',
            'tasksByStatus',
            'projectsByStatus'
        ));
    }

    /**
     * Gestión de usuarios con filtros
     */
    public function users(Request $request)
    {
        $query = User::with(['teams', 'createdProjects', 'assignedTasks', 'comments']);

        // Filtro por búsqueda (nombre o email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtro por verificación
        if ($request->filled('verified')) {
            if ($request->verified == '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'ADMIN')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.users', compact('users', 'stats'));
    }

    /**
     * Gestión de proyectos
     */
    public function projects(Request $request)
    {
        $query = Project::with(['creator', 'teams', 'modules']);

        // Filtros similares para proyectos
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('public')) {
            $query->where('public', $request->public == '1');
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'ACTIVE')->count(),
            'public_projects' => Project::where('public', true)->count(),
            'completed_projects' => Project::where('status', 'DONE')->count(),
        ];

        return view('admin.projects', compact('projects', 'stats'));
    }

    /**
     * Gestión de equipos con filtros mejorados
     */
    public function teams(Request $request)
    {
        $query = Team::query();

        // Aplicar filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('min_members')) {
            $query->whereHas('users', function($q) {
                $q->where('is_active', true);
            }, '>=', $request->min_members);
        }

        if ($request->filled('has_projects')) {
            if ($request->has_projects === '1') {
                $query->whereHas('projects');
            } elseif ($request->has_projects === '0') {
                $query->whereDoesntHave('projects');
            }
        }

        // Cargar relaciones necesarias con conteos
        $teams = $query->with([
            'users' => function($query) {
                $query->withPivot(['role', 'is_active', 'joined_at', 'left_at']);
            },
            'projects.modules'
        ])->withCount([
            'users as users_count' => function($query) {
                $query->where('is_active', true);
            },
            'projects as projects_count',
            'modules as modules_count'
        ])->orderBy('name')->paginate(10);

        // Estadísticas generales
        $stats = [
            'total_teams' => Team::count(),
            'active_members' => DB::table('team_user')->where('is_active', true)->distinct('user_id')->count(),
            'total_assignments' => DB::table('project_team')->count() + DB::table('module_team')->count()
        ];

        return view('admin.teams', compact('teams', 'stats'));
    }

    /**
     * Actualizar rol de usuario
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:ADMIN,USER'
        ]);

        // No permitir cambiar el rol del último admin
        if ($user->isAdmin() && $request->role === 'USER' && User::where('role', 'ADMIN')->count() <= 1) {
            return back()->with('error', 'No puedes quitar privilegios de administrador al último admin del sistema.');
        }

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', "Rol de {$user->name} actualizado a {$request->role}");
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser(User $user)
    {
        // No permitir eliminar al último admin
        if ($user->isAdmin() && User::where('role', 'ADMIN')->count() <= 1) {
            return back()->with('error', 'No puedes eliminar al último administrador del sistema.');
        }

        // No permitir auto-eliminación
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta desde aquí.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Usuario {$userName} eliminado correctamente.");
    }

    /**
     * Actualizar estado de proyecto
     */
    public function updateProjectStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED'
        ]);

        $project->update([
            'status' => $request->status
        ]);

        return back()->with('success', "Estado del proyecto '{$project->title}' actualizado a {$request->status}");
    }

    /**
     * Eliminar proyecto
     */
    public function deleteProject(Project $project)
    {
        $projectTitle = $project->title;
        $project->delete();

        return back()->with('success', "Proyecto '{$projectTitle}' eliminado correctamente.");
    }

    /**
     * Eliminar equipo
     */
    public function deleteTeam(Team $team)
    {
        $teamName = $team->name;
        $team->delete();

        return back()->with('success', "Equipo '{$teamName}' eliminado correctamente.");
    }

    /**
     * Mostrar formulario de edición de usuario
     */
    public function editUser(User $user)
    {
        $user->load(['teams', 'createdProjects', 'assignedTasks', 'comments']);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Asignar módulo al equipo
     */
    public function assignTeamModule(Request $request, Team $team)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
        ]);

        // Verificar que el módulo no esté ya asignado al equipo
        if ($team->modules()->where('module_id', $request->module_id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El módulo ya está asignado al equipo.');
        }

        $team->modules()->attach($request->module_id, [
            'assigned_at' => now(),
        ]);

        $module = Module::find($request->module_id);
        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Módulo '{$module->name}' asignado al equipo correctamente.");
    }

    /**
     * Desasignar módulo del equipo
     */
    public function unassignTeamModule(Team $team, Module $module)
    {
        // Verificar que el módulo está asignado al equipo
        if (!$team->modules()->where('module_id', $module->id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El módulo no está asignado al equipo.');
        }

        $team->modules()->detach($module->id);

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Módulo '{$module->name}' desasignado del equipo correctamente.");
    }

    /**
     * Actualizar información básica del usuario
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:ADMIN,USER',
            'email_verified' => 'required|in:0,1'
        ]);

        // No permitir cambiar el rol del último admin
        if ($user->isAdmin() && $request->role === 'USER' && User::where('role', 'ADMIN')->count() <= 1) {
            return back()->with('error', 'No puedes quitar privilegios de administrador al último admin del sistema.');
        }

        // No permitir cambiar el propio rol
        if ($user->id === auth()->id() && $user->role !== $request->role) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        // Actualizar verificación de email
        $emailVerifiedAt = $request->email_verified == '1' ? 
            ($user->email_verified_at ?: now()) : null;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'email_verified_at' => $emailVerifiedAt,
        ]);

        return back()->with('success', 'Información del usuario actualizada correctamente.');
    }

    /**
     * Actualizar contraseña del usuario
     */
    public function updateUserPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => $request->new_password, // Se hashea automáticamente
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Resetear contraseña del usuario (generar temporal)
     */
    public function resetUserPassword(User $user)
    {
        $temporaryPassword = 'TaskFlow' . rand(1000, 9999);
        
        $user->update([
            'password' => $temporaryPassword,
        ]);

        return back()->with('success', "Contraseña temporal generada: {$temporaryPassword}");
    }

    /**
     * Actualizar rol en equipo o estado
     */
    public function updateUserTeamRole(Request $request, User $user, Team $team)
    {
        $pivot = $user->teams()->where('team_id', $team->id)->first();
        
        if (!$pivot) {
            return back()->with('error', 'El usuario no pertenece a este equipo.');
        }

        if ($request->action === 'toggle_status') {
            $user->teams()->updateExistingPivot($team->id, [
                'is_active' => !$pivot->pivot->is_active
            ]);
            
            $status = $pivot->pivot->is_active ? 'desactivado' : 'activado';
            return back()->with('success', "Usuario {$status} en el equipo {$team->name}.");
        }

        return back();
    }

    /**
     * Remover usuario de equipo
     */
    public function removeUserFromTeam(User $user, Team $team)
    {
        $user->teams()->detach($team->id);
        
        return back()->with('success', "Usuario removido del equipo {$team->name}.");
    }

    /**
     * Vista de estadísticas avanzadas
     */
    public function statistics()
    {
        // Estadísticas básicas
        $stats = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_teams' => Team::count(),
            'total_tasks' => Task::count(),
            'total_modules' => Module::count(),
            'total_comments' => Comment::count(),
            'active_projects' => Project::where('status', 'ACTIVE')->count(),
            'completed_tasks' => Task::where('status', 'DONE')->count(),
            'admin_users' => User::where('role', 'ADMIN')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),

            // Estadísticas temporales
            'users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'comments_this_week' => Comment::where('created_at', '>=', now()->startOfWeek())->count(),
            'today_activity' => Task::whereDate('created_at', today())->count() + Task::whereDate('updated_at', today())->where('status', 'DONE')->count(),
            'week_activity' => Project::where('created_at', '>=', now()->startOfWeek())->count() + Team::where('created_at', '>=', now()->startOfWeek())->count(),
            'month_activity' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'year_activity' => Project::where('created_at', '>=', now()->startOfYear())->count(),
        ];

        // Métricas calculadas
        $stats['avg_team_size'] = $stats['total_teams'] > 0 ?
            round(DB::table('team_user')->count() / $stats['total_teams'], 1) : 0;

        $stats['avg_modules_per_project'] = $stats['total_projects'] > 0 ?
            round($stats['total_modules'] / $stats['total_projects'], 1) : 0;

        $stats['completion_rate'] = $stats['total_tasks'] > 0 ?
            round(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 1) : 0;

        $stats['avg_tasks_per_user'] = $stats['total_users'] > 0 ?
            round($stats['total_tasks'] / $stats['total_users'], 1) : 0;

        $stats['projects_per_team'] = $stats['total_teams'] > 0 ?
            round(DB::table('project_team')->count() / $stats['total_teams'], 1) : 0;

        // Duración promedio de proyectos (solo completados)
        $completedProjects = Project::where('status', 'DONE')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get();

        $stats['avg_project_duration'] = $completedProjects->count() > 0 ?
            round($completedProjects->avg(function ($project) {
                return $project->start_date->diffInDays($project->end_date);
            }), 0) : 0;

        // Top usuarios más activos
        $topUsers = User::withCount(['createdProjects as projects_count', 'assignedTasks as tasks_count'])
            ->orderByDesc('projects_count')
            ->orderByDesc('tasks_count')
            ->limit(5)
            ->get();

        // Proyectos más grandes (por número de tareas)
        $biggestProjects = Project::withCount(['teams', 'modules'])
            ->with(['creator'])
            ->withCount(['modules as tasks_count' => function ($query) {
                $query->join('tasks', 'modules.id', '=', 'tasks.module_id');
            }])
            ->orderByDesc('tasks_count')
            ->orderByDesc('modules_count')
            ->limit(10)
            ->get();

        // Datos para gráfico de crecimiento de usuarios (últimos 12 meses)
        $userGrowthData = [];
        $userGrowthLabels = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $userGrowthLabels[] = $month->format('M Y');
            $userGrowthData[] = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Datos para gráfico de estados de proyectos
        $projectStatusData = Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $projectStatusLabels = $projectStatusData->pluck('status')->toArray();
        $projectStatusData = $projectStatusData->pluck('count')->toArray();

        return view('admin.statistics', compact(
            'stats',
            'topUsers',
            'biggestProjects',
            'userGrowthData',
            'userGrowthLabels',
            'projectStatusLabels',
            'projectStatusData'
        ));
    }

    /**
     * Mostrar formulario de edición del equipo
     */
    public function editTeam(Team $team)
    {
        // Cargar relaciones necesarias
        $team->load([
            'users' => function ($query) {
                $query->withPivot(['role', 'is_active', 'joined_at', 'left_at']);
            },
            'modules' => function ($query) {
                $query->with(['project', 'dependency', 'tasks']);
            }
        ]);

        // Obtener usuarios disponibles para agregar al equipo (que no están ya en el equipo)
        $availableUsers = User::whereNotIn('id', $team->users->pluck('id'))
            ->orderBy('name')
            ->get();

        // Obtener módulos disponibles para asignar al equipo
        $availableModules = Module::with('project')
            ->whereDoesntHave('teams', function ($query) use ($team) {
                $query->where('team_id', $team->id);
            })
            ->orderBy('name')
            ->get();

        // Roles disponibles para miembros del equipo
        $teamRoles = [
            'LEAD' => 'Líder de Equipo',
            'SENIOR_DEV' => 'Desarrollador Senior',
            'DEVELOPER' => 'Desarrollador',
            'JUNIOR_DEV' => 'Desarrollador Junior',
            'DESIGNER' => 'Diseñador',
            'TESTER' => 'Tester',
            'ANALYST' => 'Analista',
            'OBSERVER' => 'Observador'
        ];

        return view('admin.teams.edit', compact(
            'team',
            'availableUsers',
            'availableModules',
            'teamRoles'
        ));
    }

    /**
     * Mostrar formulario para crear equipo
     */
    public function createTeam()
    {
        // Obtener usuarios disponibles
        $availableUsers = User::orderBy('name')->get();

        // Roles disponibles para miembros del equipo
        $teamRoles = [
            'LEAD' => 'Líder de Equipo',
            'SENIOR_DEV' => 'Desarrollador Senior',
            'DEVELOPER' => 'Desarrollador',
            'JUNIOR_DEV' => 'Desarrollador Junior',
            'DESIGNER' => 'Diseñador',
            'TESTER' => 'Tester',
            'ANALYST' => 'Analista',
            'OBSERVER' => 'Observador'
        ];

        // Estadísticas del sistema
        $stats = [
            'total_teams' => Team::count(),
            'total_users' => User::count(),
            'active_members' => DB::table('team_user')->where('is_active', true)->count(),
            'total_projects' => Project::count(),
        ];

        return view('admin.teams.create', compact(
            'availableUsers',
            'teamRoles',
            'stats'
        ));
    }

    /**
     * Crear nuevo equipo
     */
    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'description' => 'nullable|string|max:1000',
            'lead_user' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*.user_id' => 'nullable|exists:users,id',
            'members.*.role' => 'nullable|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        // Crear el equipo
        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Agregar el líder si fue especificado
        if ($request->lead_user) {
            $team->users()->attach($request->lead_user, [
                'role' => 'LEAD',
                'is_active' => true,
                'joined_at' => now(),
            ]);
        }

        // Agregar miembros iniciales
        if ($request->members) {
            foreach ($request->members as $member) {
                if (!empty($member['user_id']) && !empty($member['role'])) {
                    // Evitar duplicados (si el líder ya fue agregado)
                    if ($member['user_id'] != $request->lead_user) {
                        $team->users()->attach($member['user_id'], [
                            'role' => $member['role'],
                            'is_active' => true,
                            'joined_at' => now(),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Equipo '{$team->name}' creado correctamente. Ahora puedes asignar módulos y gestionar más miembros.");
    }

    /**
     * Actualizar información básica del equipo
     */
    public function updateTeam(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $team->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', 'Información del equipo actualizada correctamente.');
    }

    /**
     * Agregar miembro al equipo
     */
    public function addTeamMember(Request $request, Team $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        // Verificar que el usuario no esté ya en el equipo
        if ($team->users()->where('user_id', $request->user_id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El usuario ya es miembro del equipo.');
        }

        $team->users()->attach($request->user_id, [
            'role' => $request->role,
            'is_active' => true,
            'joined_at' => now(),
        ]);

        $user = User::find($request->user_id);
        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Usuario {$user->name} agregado al equipo correctamente.");
    }

    /**
     * Actualizar rol de miembro del equipo
     */
    public function updateTeamMemberRole(Request $request, Team $team, User $user)
    {
        $request->validate([
            'role' => 'required|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        // Verificar que el usuario es miembro del equipo
        if (!$team->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El usuario no es miembro del equipo.');
        }

        $team->users()->updateExistingPivot($user->id, [
            'role' => $request->role,
        ]);

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Rol de {$user->name} actualizado correctamente.");
    }

    /**
     * Remover miembro del equipo
     */
    public function removeTeamMember(Team $team, User $user)
    {
        // Verificar que el usuario es miembro del equipo
        if (!$team->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El usuario no es miembro del equipo.');
        }

        // Marcar como inactivo en lugar de eliminar completamente
        $team->users()->updateExistingPivot($user->id, [
            'is_active' => false,
            'left_at' => now(),
        ]);

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Usuario {$user->name} removido del equipo correctamente.");
    }

    /**
     * Asignar proyecto al equipo
     */
    public function assignTeamProject(Request $request, Team $team)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        // Verificar que el proyecto no esté ya asignado al equipo
        if ($team->projects()->where('project_id', $request->project_id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El proyecto ya está asignado al equipo.');
        }

        $team->projects()->attach($request->project_id, [
            'assigned_at' => now(),
        ]);

        $project = Project::find($request->project_id);
        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Proyecto '{$project->title}' asignado al equipo correctamente.");
    }

    /**
     * Desasignar proyecto del equipo
     */
    public function unassignTeamProject(Team $team, Project $project)
    {
        // Verificar que el proyecto está asignado al equipo
        if (!$team->projects()->where('project_id', $project->id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El proyecto no está asignado al equipo.');
        }

        $team->projects()->detach($project->id);

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Proyecto '{$project->title}' desasignado del equipo correctamente.");
    }

    /**
     * Mostrar formulario de edición del proyecto
     */
    public function editProject(Project $project)
    {
        // Cargar relaciones necesarias
        $project->load([
            'creator',
            'teams' => function($query) {
                $query->withPivot(['assigned_at']);
            },
            'modules.tasks'
        ]);

        // Obtener equipos disponibles para asignar al proyecto
        $availableTeams = Team::whereDoesntHave('projects', function($query) use ($project) {
            $query->where('project_id', $project->id);
        })->orderBy('name')->get();

        // Estados disponibles para el proyecto
        $projectStatuses = [
            'PENDING' => 'Pendiente',
            'ACTIVE' => 'Activo',
            'DONE' => 'Completado',
            'PAUSED' => 'Pausado',
            'CANCELLED' => 'Cancelado'
        ];

        // Categorías de módulos
        $moduleCategories = [
            'DEVELOPMENT' => 'Desarrollo',
            'DESIGN' => 'Diseño',
            'TESTING' => 'Pruebas',
            'DOCUMENTATION' => 'Documentación',
            'RESEARCH' => 'Investigación',
            'DEPLOYMENT' => 'Despliegue',
            'MAINTENANCE' => 'Mantenimiento',
            'INTEGRATION' => 'Integración'
        ];

        // Prioridades
        $priorities = [
            'LOW' => 'Baja',
            'MEDIUM' => 'Media',
            'HIGH' => 'Alta',
            'URGENT' => 'Urgente'
        ];

        return view('admin.projects.edit', compact(
            'project',
            'availableTeams',
            'projectStatuses',
            'moduleCategories',
            'priorities'
        ));
    }

    /**
     * Actualizar información básica del proyecto
     */
    public function updateProject(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'public' => 'required|boolean',
        ]);

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'public' => $request->public,
        ]);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', 'Información del proyecto actualizada correctamente.');
    }

    /**
     * Asignar equipo al proyecto
     */
    public function assignProjectTeam(Request $request, Project $project)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        // Verificar que el equipo no esté ya asignado al proyecto
        if ($project->teams()->where('team_id', $request->team_id)->exists()) {
            return redirect()->route('admin.projects.edit', $project)
                ->with('error', 'El equipo ya está asignado al proyecto.');
        }

        $project->teams()->attach($request->team_id, [
            'assigned_at' => now(),
        ]);

        $team = Team::find($request->team_id);
        return redirect()->route('admin.projects.edit', $project)
            ->with('success', "Equipo '{$team->name}' asignado al proyecto correctamente.");
    }

    /**
     * Desasignar equipo del proyecto
     */
    public function unassignProjectTeam(Project $project, Team $team)
    {
        // Verificar que el equipo está asignado al proyecto
        if (!$project->teams()->where('team_id', $team->id)->exists()) {
            return redirect()->route('admin.projects.edit', $project)
                ->with('error', 'El equipo no está asignado al proyecto.');
        }

        $project->teams()->detach($team->id);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', "Equipo '{$team->name}' desasignado del proyecto correctamente.");
    }

    /**
     * Crear nuevo módulo para el proyecto
     */
    public function createProjectModule(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:modules,name,NULL,id,project_id,' . $project->id,
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'category' => 'required|in:DEVELOPMENT,DESIGN,TESTING,DOCUMENTATION,RESEARCH,DEPLOYMENT,MAINTENANCE,INTEGRATION',
            'depends_on' => 'nullable|exists:modules,id',
            'is_core' => 'required|boolean',
        ]);

        // Verificar que el módulo de dependencia pertenece al mismo proyecto
        if ($request->depends_on) {
            $dependencyModule = Module::find($request->depends_on);
            if ($dependencyModule && $dependencyModule->project_id !== $project->id) {
                return redirect()->route('admin.projects.edit', $project)
                    ->with('error', 'El módulo de dependencia debe pertenecer al mismo proyecto.');
            }
        }

        $module = $project->modules()->create([
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => 'PENDING',
            'depends_on' => $request->depends_on,
            'is_core' => $request->is_core,
        ]);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', "Módulo '{$module->name}' creado correctamente.");
    }

    /**
     * Actualizar módulo del proyecto
     */
    public function updateProjectModule(Request $request, Project $project, Module $module)
    {
        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return redirect()->route('admin.projects.edit', $project)
                ->with('error', 'El módulo no pertenece a este proyecto.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:modules,name,' . $module->id . ',id,project_id,' . $project->id,
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'category' => 'required|in:DEVELOPMENT,DESIGN,TESTING,DOCUMENTATION,RESEARCH,DEPLOYMENT,MAINTENANCE,INTEGRATION',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'depends_on' => 'nullable|exists:modules,id',
            'is_core' => 'required|boolean',
        ]);

        // Verificar que el módulo de dependencia pertenece al mismo proyecto y no es el mismo módulo
        if ($request->depends_on) {
            if ($request->depends_on == $module->id) {
                return redirect()->route('admin.projects.edit', $project)
                    ->with('error', 'Un módulo no puede depender de sí mismo.');
            }
            
            $dependencyModule = Module::find($request->depends_on);
            if ($dependencyModule && $dependencyModule->project_id !== $project->id) {
                return redirect()->route('admin.projects.edit', $project)
                    ->with('error', 'El módulo de dependencia debe pertenecer al mismo proyecto.');
            }
        }

        $module->update([
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => $request->status,
            'depends_on' => $request->depends_on,
            'is_core' => $request->is_core,
        ]);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', "Módulo '{$module->name}' actualizado correctamente.");
    }

    /**
     * Eliminar módulo del proyecto
     */
    public function deleteProjectModule(Project $project, Module $module)
    {
        // Verificar que el módulo pertenece al proyecto
        if ($module->project_id !== $project->id) {
            return redirect()->route('admin.projects.edit', $project)
                ->with('error', 'El módulo no pertenece a este proyecto.');
        }

        // Verificar si hay otros módulos que dependen de este
        $dependentModules = Module::where('depends_on', $module->id)->count();
        if ($dependentModules > 0) {
            return redirect()->route('admin.projects.edit', $project)
                ->with('error', "No se puede eliminar el módulo '{$module->name}' porque otros módulos dependen de él.");
        }

        $moduleName = $module->name;
        $module->delete();

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', "Módulo '{$moduleName}' eliminado correctamente.");
    }
}

