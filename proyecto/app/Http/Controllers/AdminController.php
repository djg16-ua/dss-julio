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

    // ==========================================
    // DASHBOARD Y STATISTICS
    // ==========================================

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

        // Métricas calculadas adaptadas a la nueva estructura
        $stats['avg_team_size'] = $stats['total_teams'] > 0 ?
            round(DB::table('team_user')->where('is_active', true)->count() / $stats['total_teams'], 1) : 0;

        $stats['avg_modules_per_project'] = $stats['total_projects'] > 0 ?
            round($stats['total_modules'] / $stats['total_projects'], 1) : 0;

        $stats['completion_rate'] = $stats['total_tasks'] > 0 ?
            round(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 1) : 0;

        $stats['avg_tasks_per_user'] = $stats['total_users'] > 0 ?
            round($stats['total_tasks'] / $stats['total_users'], 1) : 0;

        // NUEVA MÉTRICA: Equipos por proyecto (incluyendo generales)
        $stats['teams_per_project'] = $stats['total_projects'] > 0 ?
            round($stats['total_teams'] / $stats['total_projects'], 1) : 0;

        // NUEVA MÉTRICA: Equipos personalizados (excluyendo generales)
        $customTeamsCount = Team::where('is_general', false)->count();
        $stats['custom_teams_per_project'] = $stats['total_projects'] > 0 ?
            round($customTeamsCount / $stats['total_projects'], 1) : 0;

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

    // ==========================================
    // PROJECTS
    // ==========================================

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
     * Mostrar formulario para crear proyecto
     */
    public function createProject()
    {
        // Estados disponibles para el proyecto
        $projectStatuses = [
            'PENDING' => 'Pendiente',
            'ACTIVE' => 'Activo',
            'DONE' => 'Completado',
            'PAUSED' => 'Pausado',
            'CANCELLED' => 'Cancelado'
        ];

        // Estadísticas del sistema
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'ACTIVE')->count(),
            'total_teams' => Team::count(),
            'total_modules' => Module::count(),
        ];

        return view('admin.projects.create', compact(
            'projectStatuses',
            'stats'
        ));
    }

    /**
     * Crear nuevo proyecto
     */
    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:projects,title',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'public' => 'required|boolean',
        ]);

        // Crear el proyecto (el equipo general se crea automáticamente en el boot() del modelo)
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'public' => $request->public,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', "Proyecto '{$project->title}' creado correctamente. Se ha generado automáticamente un equipo general. Ahora puedes crear módulos y equipos personalizados.");
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
                $query->withCount(['users as active_users_count' => function($q) {
                    $q->where('is_active', true);
                }]);
            },
            'modules.tasks'
        ]);

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

    // ==========================================
    // TEAMS
    // ==========================================

    /**
     * Gestión de equipos con filtros mejorados
     */
    public function teams(Request $request)
    {
        // Solo mostrar equipos personalizados (no generales) en la gestión general
        $query = Team::where('is_general', false)->with(['project', 'users']);

        // Aplicar filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('project', function($projectQuery) use ($request) {
                      $projectQuery->where('title', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('min_members')) {
            $query->whereHas('users', function($q) {
                $q->where('is_active', true);
            }, '>=', $request->min_members);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Cargar relaciones necesarias con conteos
        $teams = $query->withCount([
            'users as active_users_count' => function($query) {
                $query->where('is_active', true);
            },
            'modules as modules_count'
        ])->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas generales
        $stats = [
            'total_teams' => Team::where('is_general', false)->count(),
            'general_teams' => Team::where('is_general', true)->count(),
            'active_members' => DB::table('team_user')->where('is_active', true)->distinct('user_id')->count(),
            'total_assignments' => DB::table('module_team')->count()
        ];

        // Proyectos disponibles para filtro
        $projects = Project::orderBy('title')->get();

        return view('admin.teams', compact('teams', 'stats', 'projects'));
    }

    /**
     * Mostrar formulario para crear equipo
     */
    public function createTeam()
    {
        // Obtener usuarios disponibles
        $availableUsers = User::orderBy('name')->get();
        
        // Obtener proyectos disponibles
        $projects = Project::orderBy('title')->get();

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
            'total_teams' => Team::where('is_general', false)->count(),
            'general_teams' => Team::where('is_general', true)->count(),
            'total_users' => User::count(),
            'active_members' => DB::table('team_user')->where('is_active', true)->count(),
            'total_projects' => Project::count(),
        ];

        return view('admin.teams.create', compact(
            'availableUsers',
            'projects',
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'project_id' => 'required|exists:projects,id',
            'lead_user' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*.user_id' => 'nullable|exists:users,id',
            'members.*.role' => 'nullable|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        // Verificar que el nombre sea único dentro del proyecto
        $existingTeam = Team::where('project_id', $request->project_id)
            ->where('name', $request->name)
            ->first();

        if ($existingTeam) {
            return back()->with('error', 'Ya existe un equipo con ese nombre en el proyecto seleccionado.');
        }

        // Crear el equipo (siempre personalizado)
        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'is_general' => false,
        ]);

        // Agregar el líder si fue especificado
        if ($request->lead_user) {
            $team->users()->attach($request->lead_user, [
                'role' => 'LEAD',
                'is_active' => true,
                'joined_at' => now(),
            ]);

            // También agregar al equipo general del proyecto
            $generalTeam = $team->project->getGeneralTeam();
            if ($generalTeam && !$generalTeam->users()->where('user_id', $request->lead_user)->exists()) {
                $generalTeam->users()->attach($request->lead_user, [
                    'role' => 'DEVELOPER',
                    'is_active' => true,
                    'joined_at' => now(),
                ]);
            }
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

                        // También agregar al equipo general del proyecto
                        $generalTeam = $team->project->getGeneralTeam();
                        if ($generalTeam && !$generalTeam->users()->where('user_id', $member['user_id'])->exists()) {
                            $generalTeam->users()->attach($member['user_id'], [
                                'role' => 'DEVELOPER',
                                'is_active' => true,
                                'joined_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Equipo '{$team->name}' creado correctamente. Los miembros han sido agregados automáticamente al equipo general del proyecto.");
    }

    /**
     * Mostrar formulario de edición del equipo
     */
    public function editTeam(Team $team)
    {
        // Cargar relaciones necesarias
        $team->load([
            'project',
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

        // Obtener módulos disponibles para asignar al equipo (del mismo proyecto)
        $availableModules = Module::where('project_id', $team->project_id)
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
     * Actualizar información básica del equipo
     */
    public function updateTeam(Request $request, Team $team)
    {
        // Proteger equipos generales
        if ($team->is_general) {
            return back()->with('error', 'No se puede modificar la información del equipo general.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Verificar que el nombre sea único dentro del proyecto (excluyendo el equipo actual)
        $existingTeam = Team::where('project_id', $team->project_id)
            ->where('name', $request->name)
            ->where('id', '!=', $team->id)
            ->first();

        if ($existingTeam) {
            return back()->with('error', 'Ya existe un equipo con ese nombre en el proyecto.');
        }

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

        // Si es un equipo personalizado, también agregar al equipo general del proyecto
        if (!$team->is_general) {
            $generalTeam = $team->project->getGeneralTeam();
            if ($generalTeam && !$generalTeam->users()->where('user_id', $request->user_id)->exists()) {
                $generalTeam->users()->attach($request->user_id, [
                    'role' => 'DEVELOPER',
                    'is_active' => true,
                    'joined_at' => now(),
                ]);
            }
        }

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

        // Si es el equipo general, no permitir remover miembros directamente
        if ($team->is_general) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'No se pueden remover miembros del equipo general directamente. Remuévelos de todos los equipos personalizados del proyecto.');
        }

        // Marcar como inactivo en lugar de eliminar completamente
        $team->users()->updateExistingPivot($user->id, [
            'is_active' => false,
            'left_at' => now(),
        ]);

        // Verificar si el usuario sigue en otros equipos del proyecto
        $userInOtherTeams = Team::where('project_id', $team->project_id)
            ->where('id', '!=', $team->id)
            ->where('is_general', false)
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('is_active', true);
            })->exists();

        // Si no está en otros equipos personalizados, remover del equipo general
        if (!$userInOtherTeams) {
            $generalTeam = $team->project->getGeneralTeam();
            if ($generalTeam) {
                $generalTeam->users()->updateExistingPivot($user->id, [
                    'is_active' => false,
                    'left_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.teams.edit', $team)
            ->with('success', "Usuario {$user->name} removido del equipo correctamente.");
    }

    /**
     * Eliminar equipo
     */
    public function deleteTeam(Team $team)
    {
        // Proteger equipos generales
        if ($team->is_general) {
            return back()->with('error', 'No se puede eliminar el equipo general del proyecto.');
        }

        $teamName = $team->name;
        $project = $team->project;
        
        // Los miembros del equipo se gestionan automáticamente por las relaciones
        $team->delete();

        return back()->with('success', "Equipo '{$teamName}' eliminado correctamente.");
    }

    /**
     * Asignar módulo al equipo
     */
    public function assignTeamModule(Request $request, Team $team)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
        ]);

        $module = Module::find($request->module_id);

        // Verificar que el módulo pertenece al mismo proyecto
        if ($module->project_id !== $team->project_id) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El módulo debe pertenecer al mismo proyecto que el equipo.');
        }

        // Verificar que el módulo no esté ya asignado al equipo
        if ($team->modules()->where('module_id', $request->module_id)->exists()) {
            return redirect()->route('admin.teams.edit', $team)
                ->with('error', 'El módulo ya está asignado al equipo.');
        }

        $team->modules()->attach($request->module_id, [
            'assigned_at' => now(),
        ]);

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

    // ==========================================
    // USERS
    // ==========================================

    /**
     * Gestión de usuarios con filtros
     */
    public function users(Request $request)
    {
        $query = User::with(['teams.project', 'createdProjects', 'assignedTasks', 'comments']);

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

        // Filtro por proyecto
        if ($request->filled('project_id')) {
            $query->whereHas('teams', function($q) use ($request) {
                $q->where('project_id', $request->project_id)
                  ->where('is_active', true);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'ADMIN')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'active_in_projects' => User::whereHas('teams', function($q) {
                $q->where('is_active', true);
            })->count(),
        ];

        // Proyectos disponibles para filtro
        $projects = Project::orderBy('title')->get();

        return view('admin.users', compact('users', 'stats', 'projects'));
    }

    /**
     * Mostrar formulario para crear usuario
     */
    public function createUser()
    {
        // Obtener proyectos disponibles (para agregar a equipos generales)
        $projects = Project::with(['teams' => function($query) {
            $query->where('is_general', false); // Solo equipos personalizados
        }])->orderBy('title')->get();

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
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'ADMIN')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'total_projects' => Project::count(),
            'total_teams' => Team::where('is_general', false)->count(),
        ];

        return view('admin.users.create', compact(
            'projects',
            'teamRoles',
            'stats'
        ));
    }

    /**
     * Crear nuevo usuario
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:USER,ADMIN',
            'email_verified' => 'required|in:0,1',
            'projects' => 'nullable|array',
            'projects.*.project_id' => 'nullable|exists:projects,id',
            'projects.*.role' => 'nullable|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
            'teams' => 'nullable|array',
            'teams.*.team_id' => 'nullable|exists:teams,id',
            'teams.*.role' => 'nullable|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Se hashea automáticamente
            'role' => $request->role,
            'email_verified_at' => $request->email_verified === '1' ? now() : null,
        ]);

        // Asignar a proyectos (equipos generales) si se especificaron
        if ($request->projects) {
            foreach ($request->projects as $projectData) {
                if (!empty($projectData['project_id']) && !empty($projectData['role'])) {
                    $project = Project::find($projectData['project_id']);
                    $generalTeam = $project->getGeneralTeam();
                    
                    if ($generalTeam) {
                        $generalTeam->users()->attach($user->id, [
                            'role' => $projectData['role'],
                            'is_active' => true,
                            'joined_at' => now(),
                        ]);
                    }
                }
            }
        }

        // Asignar a equipos personalizados si se especificaron
        if ($request->teams) {
            foreach ($request->teams as $teamData) {
                if (!empty($teamData['team_id']) && !empty($teamData['role'])) {
                    $team = Team::find($teamData['team_id']);
                    
                    // Verificar que no sea un equipo general
                    if (!$team->is_general) {
                        $team->users()->attach($user->id, [
                            'role' => $teamData['role'],
                            'is_active' => true,
                            'joined_at' => now(),
                        ]);

                        // También agregar al equipo general del proyecto si no está ya
                        $generalTeam = $team->project->getGeneralTeam();
                        if ($generalTeam && !$generalTeam->users()->where('user_id', $user->id)->exists()) {
                            $generalTeam->users()->attach($user->id, [
                                'role' => 'DEVELOPER',
                                'is_active' => true,
                                'joined_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.users.edit', $user)
            ->with('success', "Usuario '{$user->name}' creado correctamente. Ha sido agregado a los equipos y proyectos seleccionados.");
    }

    /**
     * Mostrar formulario de edición de usuario
     */
    public function editUser(User $user)
    {
        $user->load([
            'teams.project', 
            'createdProjects', 
            'assignedTasks.module.project', 
            'comments.task'
        ]);

        // Obtener proyectos disponibles
        $availableProjects = Project::whereDoesntHave('teams', function($query) use ($user) {
            $query->where('is_general', true)
                  ->whereHas('users', function($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        })->orderBy('title')->get();

        // Obtener equipos personalizados disponibles (agrupados por proyecto)
        $availableTeams = Team::where('is_general', false)
            ->whereDoesntHave('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('project')
            ->orderBy('name')
            ->get()
            ->groupBy('project.title');

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

        return view('admin.users.edit', compact(
            'user',
            'availableProjects',
            'availableTeams',
            'teamRoles'
        ));
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
        $emailVerifiedAt = null;
        if ($request->email_verified == '1') {
            // Si se marca como verificado, asegurar que tenga fecha
            $emailVerifiedAt = $user->email_verified_at ?: now();
        }
        // Si se marca como no verificado (valor '0'), emailVerifiedAt queda null

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'email_verified_at' => $emailVerifiedAt,
        ]);

        return back()->with('success', 'Información del usuario actualizada correctamente.');
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
     * Agregar usuario a proyecto (equipo general)
     */
    public function addUserToProject(Request $request, User $user)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'role' => 'required|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        $project = Project::find($request->project_id);
        $generalTeam = $project->getGeneralTeam();

        if (!$generalTeam) {
            return back()->with('error', 'El proyecto no tiene equipo general configurado.');
        }

        // Verificar que el usuario no esté ya en el proyecto
        if ($generalTeam->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'El usuario ya participa en este proyecto.');
        }

        $generalTeam->users()->attach($user->id, [
            'role' => $request->role,
            'is_active' => true,
            'joined_at' => now(),
        ]);

        return back()->with('success', "Usuario agregado al proyecto '{$project->title}' correctamente.");
    }

    /**
     * Remover usuario de proyecto (equipo general)
     */
    public function removeUserFromProject(User $user, Project $project)
    {
        $generalTeam = $project->getGeneralTeam();

        if (!$generalTeam) {
            return back()->with('error', 'El proyecto no tiene equipo general configurado.');
        }

        // Primero remover de todos los equipos personalizados del proyecto
        $customTeams = $project->teams()->where('is_general', false)->get();
        foreach ($customTeams as $team) {
            if ($team->users()->where('user_id', $user->id)->exists()) {
                $team->users()->updateExistingPivot($user->id, [
                    'is_active' => false,
                    'left_at' => now(),
                ]);
            }
        }

        // Luego remover del equipo general
        $generalTeam->users()->updateExistingPivot($user->id, [
            'is_active' => false,
            'left_at' => now(),
        ]);

        return back()->with('success', "Usuario removido del proyecto '{$project->title}' correctamente.");
    }

    /**
     * Agregar usuario a equipo personalizado
     */
    public function addUserToTeam(Request $request, User $user)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'role' => 'required|in:LEAD,SENIOR_DEV,DEVELOPER,JUNIOR_DEV,DESIGNER,TESTER,ANALYST,OBSERVER',
        ]);

        $team = Team::find($request->team_id);

        // Verificar que no sea un equipo general
        if ($team->is_general) {
            return back()->with('error', 'Use la función de agregar a proyecto para equipos generales.');
        }

        // Verificar que el usuario no esté ya en el equipo
        if ($team->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'El usuario ya es miembro de este equipo.');
        }

        $team->users()->attach($user->id, [
            'role' => $request->role,
            'is_active' => true,
            'joined_at' => now(),
        ]);

        // También agregar al equipo general del proyecto si no está ya
        $generalTeam = $team->project->getGeneralTeam();
        if ($generalTeam && !$generalTeam->users()->where('user_id', $user->id)->exists()) {
            $generalTeam->users()->attach($user->id, [
                'role' => 'DEVELOPER',
                'is_active' => true,
                'joined_at' => now(),
            ]);
        }

        return back()->with('success', "Usuario agregado al equipo '{$team->name}' correctamente.");
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
            // Proteger equipos generales de desactivación manual
            if ($team->is_general) {
                return back()->with('error', 'No se puede cambiar el estado en el equipo general directamente. Use remover de proyecto.');
            }

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
        // Proteger equipos generales
        if ($team->is_general) {
            return back()->with('error', 'Use la función de remover de proyecto para equipos generales.');
        }

        $user->teams()->detach($team->id);
        
        // Verificar si el usuario sigue en otros equipos del proyecto
        $userInOtherTeams = Team::where('project_id', $team->project_id)
            ->where('id', '!=', $team->id)
            ->where('is_general', false)
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('is_active', true);
            })->exists();

        // Si no está en otros equipos personalizados, remover del equipo general
        if (!$userInOtherTeams) {
            $generalTeam = $team->project->getGeneralTeam();
            if ($generalTeam) {
                $generalTeam->users()->updateExistingPivot($user->id, [
                    'is_active' => false,
                    'left_at' => now(),
                ]);
            }
        }
        
        return back()->with('success', "Usuario removido del equipo {$team->name}.");
    }

    // ==========================================
    // MODULES
    // ==========================================

    /**
     * Gestión de módulos con filtros
     */
    public function modules(Request $request)
    {
        $query = Module::with(['project', 'teams', 'tasks', 'dependency', 'dependents']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($projectQuery) use ($search) {
                        $projectQuery->where('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('is_core')) {
            $query->where('is_core', $request->is_core == '1');
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $modules = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_modules' => Module::count(),
            'active_modules' => Module::where('status', 'ACTIVE')->count(),
            'core_modules' => Module::where('is_core', true)->count(),
            'completed_modules' => Module::where('status', 'DONE')->count(),
            'modules_with_teams' => Module::whereHas('teams')->count(),
        ];

        // Proyectos disponibles para filtro
        $projects = Project::orderBy('title')->get();

        return view('admin.modules', compact('modules', 'stats', 'projects'));
    }

    /**
     * Mostrar formulario para crear módulo
     */
    public function createModule()
    {
        $projects = Project::orderBy('title')->get();

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

        $priorities = [
            'LOW' => 'Baja',
            'MEDIUM' => 'Media',
            'HIGH' => 'Alta',
            'URGENT' => 'Urgente'
        ];

        $stats = [
            'total_modules' => Module::count(),
            'active_modules' => Module::where('status', 'ACTIVE')->count(),
            'core_modules' => Module::where('is_core', true)->count(),
            'total_projects' => Project::count(),
        ];

        return view('admin.modules.create', compact(
            'projects',
            'moduleCategories',
            'priorities',
            'stats'
        ));
    }

    /**
     * Crear nuevo módulo
     */
    public function storeModule(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:DEVELOPMENT,DESIGN,TESTING,DOCUMENTATION,RESEARCH,DEPLOYMENT,MAINTENANCE,INTEGRATION',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'depends_on' => 'nullable|exists:modules,id',
            'is_core' => 'required|boolean',
            'teams' => 'nullable|array',
            'teams.*.team_id' => 'nullable|exists:teams,id',
        ]);

        // Verificar que el módulo de dependencia pertenece al mismo proyecto
        if ($request->depends_on) {
            $dependencyModule = Module::find($request->depends_on);
            if ($dependencyModule && $dependencyModule->project_id != $request->project_id) {
                return back()->with('error', 'El módulo de dependencia debe pertenecer al mismo proyecto.');
            }
        }

        // Verificar que el nombre es único dentro del proyecto
        $existingModule = Module::where('project_id', $request->project_id)
            ->where('name', $request->name)
            ->first();

        if ($existingModule) {
            return back()->with('error', 'Ya existe un módulo con ese nombre en el proyecto seleccionado.');
        }

        // Crear el módulo
        $module = Module::create([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'depends_on' => $request->depends_on,
            'is_core' => $request->is_core,
        ]);

        // Asignar equipos si se especificaron
        if ($request->teams) {
            foreach ($request->teams as $team) {
                if (!empty($team['team_id'])) {
                    $teamModel = Team::find($team['team_id']);

                    // Verificar que el equipo pertenece al mismo proyecto
                    if ($teamModel && $teamModel->project_id === $module->project_id) {
                        $module->teams()->attach($team['team_id'], [
                            'assigned_at' => now(),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.modules.edit', $module)
            ->with('success', "Módulo '{$module->name}' creado correctamente.");
    }

    /**
     * Mostrar formulario de edición del módulo
     */
    public function editModule(Module $module)
    {
        $module->load(['project', 'teams', 'tasks.assignedUser', 'dependency', 'dependents']);

        $projects = Project::orderBy('title')->get();

        // Solo módulos del mismo proyecto para dependencias
        $projectModules = Module::where('project_id', $module->project_id)
            ->where('id', '!=', $module->id)
            ->get();

        // Solo equipos del mismo proyecto que no estén ya asignados
        $availableTeams = Team::where('project_id', $module->project_id)
            ->whereDoesntHave('modules', function ($query) use ($module) {
                $query->where('module_id', $module->id);
            })
            ->orderBy('name')
            ->get();

        // Tareas disponibles para asignar (sin módulo o con módulo diferente)
        $availableTasks = Task::where(function ($query) use ($module) {
            $query->whereNull('module_id')
                ->orWhere('module_id', '!=', $module->id);
        })->with('assignedUser')->orderBy('title')->get();

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

        $priorities = [
            'LOW' => 'Baja',
            'MEDIUM' => 'Media',
            'HIGH' => 'Alta',
            'URGENT' => 'Urgente'
        ];

        return view('admin.modules.edit', compact(
            'module',
            'projects',
            'projectModules',
            'availableTeams',
            'availableTasks',
            'moduleCategories',
            'priorities'
        ));
    }

    /**
     * Actualizar módulo
     */
    public function updateModule(Request $request, Module $module)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:DEVELOPMENT,DESIGN,TESTING,DOCUMENTATION,RESEARCH,DEPLOYMENT,MAINTENANCE,INTEGRATION',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'depends_on' => 'nullable|exists:modules,id',
            'is_core' => 'required|boolean',
        ]);

        // Verificar que el módulo de dependencia pertenece al mismo proyecto
        if ($request->depends_on) {
            if ($request->depends_on == $module->id) {
                return back()->with('error', 'Un módulo no puede depender de sí mismo.');
            }

            $dependencyModule = Module::find($request->depends_on);
            if ($dependencyModule && $dependencyModule->project_id != $request->project_id) {
                return back()->with('error', 'El módulo de dependencia debe pertenecer al mismo proyecto.');
            }
        }

        // Verificar que el nombre es único dentro del proyecto (excluyendo el módulo actual)
        $existingModule = Module::where('project_id', $request->project_id)
            ->where('name', $request->name)
            ->where('id', '!=', $module->id)
            ->first();

        if ($existingModule) {
            return back()->with('error', 'Ya existe un módulo con ese nombre en el proyecto seleccionado.');
        }

        // Si se cambia de proyecto, desasignar todos los equipos actuales
        if ($module->project_id !== $request->project_id) {
            $module->teams()->detach();
        }

        $module->update([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'depends_on' => $request->depends_on,
            'is_core' => $request->is_core,
        ]);

        return redirect()->route('admin.modules.edit', $module)
            ->with('success', 'Módulo actualizado correctamente.');
    }

    /**
     * Actualizar estado del módulo
     */
    public function updateModuleStatus(Request $request, Module $module)
    {
        $request->validate([
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED'
        ]);

        $module->update([
            'status' => $request->status
        ]);

        return back()->with('success', "Estado del módulo '{$module->name}' actualizado a {$request->status}");
    }

    /**
     * Eliminar módulo
     */
    public function deleteModule(Module $module)
    {
        // Verificar si hay otros módulos que dependen de este
        $dependentModules = Module::where('depends_on', $module->id)->count();
        if ($dependentModules > 0) {
            return back()->with('error', "No se puede eliminar el módulo '{$module->name}' porque otros módulos dependen de él.");
        }

        $moduleName = $module->name;
        $module->delete();

        return redirect()->route('admin.modules')
            ->with('success', "Módulo '{$moduleName}' eliminado correctamente.");
    }

    /**
     * Asignar equipo al módulo
     */
    public function assignModuleTeam(Request $request, Module $module)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        $team = Team::find($request->team_id);

        // Verificar que el equipo pertenece al mismo proyecto
        if ($team->project_id !== $module->project_id) {
            return redirect()->route('admin.modules.edit', $module)
                ->with('error', 'El equipo debe pertenecer al mismo proyecto que el módulo.');
        }

        // Verificar que el equipo no esté ya asignado al módulo
        if ($module->teams()->where('team_id', $request->team_id)->exists()) {
            return redirect()->route('admin.modules.edit', $module)
                ->with('error', 'El equipo ya está asignado al módulo.');
        }

        $module->teams()->attach($request->team_id, [
            'assigned_at' => now(),
        ]);

        return redirect()->route('admin.modules.edit', $module)
            ->with('success', "Equipo '{$team->name}' asignado al módulo correctamente.");
    }

    /**
     * Desasignar equipo del módulo
     */
    public function unassignModuleTeam(Module $module, Team $team)
    {
        // Verificar que el equipo está asignado al módulo
        if (!$module->teams()->where('team_id', $team->id)->exists()) {
            return redirect()->route('admin.modules.edit', $module)
                ->with('error', 'El equipo no está asignado al módulo.');
        }

        $module->teams()->detach($team->id);

        return redirect()->route('admin.modules.edit', $module)
            ->with('success', "Equipo '{$team->name}' desasignado del módulo correctamente.");
    }

    /**
     * Asignar tarea existente al módulo
     */
    public function assignModuleTask(Request $request, Module $module)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
        ]);

        $task = Task::find($request->task_id);

        // Verificar que la tarea no esté ya asignada a otro módulo
        if ($task->module_id && $task->module_id !== $module->id) {
            return redirect()->route('admin.modules.edit', $module)
                ->with('error', 'La tarea ya está asignada a otro módulo.');
        }

        // Asignar la tarea al módulo
        $task->update(['module_id' => $module->id]);

        return redirect()->route('admin.modules.edit', $module)
            ->with('success', "Tarea '{$task->title}' asignada al módulo correctamente.");
    }

    /**
     * Desasignar tarea del módulo
     */
    public function unassignModuleTask(Module $module, Task $task)
    {
        // Verificar que la tarea pertenece al módulo
        if ($task->module_id !== $module->id) {
            return redirect()->route('admin.modules.edit', $module)
                ->with('error', 'La tarea no pertenece a este módulo.');
        }

        // Desasignar la tarea del módulo
        $task->update(['module_id' => null]);

        return redirect()->route('admin.modules.edit', $module)
            ->with('success', "Tarea '{$task->title}' desasignada del módulo correctamente.");
    }

    /**
     * Crear nueva tarea para el módulo
     */
    public function createModuleTask(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'assigned_to' => 'nullable|exists:users,id',
            'end_date' => 'nullable|date|after_or_equal:today',
            'depends_on' => 'nullable|exists:tasks,id',
        ]);

        // Verificar dependencia circular si se especifica
        if ($request->depends_on) {
            $dependency = Task::find($request->depends_on);
            if ($dependency && $this->hasCircularDependency($dependency, $request->depends_on)) {
                return back()->with('error', 'No se puede crear una dependencia circular.');
            }
        }

        // Si se asigna a un usuario, verificar que pertenezca al proyecto
        if ($request->assigned_to) {
            $user = User::find($request->assigned_to);
            $userInProject = $user->teams()
                ->where('project_id', $module->project_id)
                ->where('is_active', true)
                ->exists();

            if (!$userInProject) {
                return back()->with('error', 'El usuario asignado debe ser miembro del proyecto.');
            }
        }

        // Crear la tarea
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'PENDING',
            'module_id' => $module->id,
            'assigned_to' => $request->assigned_to,
            'end_date' => $request->end_date,
            'depends_on' => $request->depends_on,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.modules.edit', $module)
            ->with('success', "Tarea '{$task->title}' creada correctamente.");
    }

    /**
     * API endpoint para obtener módulos de un proyecto (para dependencias)
     */
    public function getProjectModules(Project $project)
    {
        $modules = $project->modules()->select('id', 'name')->get();
        return response()->json($modules);
    }

    /**
     * API endpoint para obtener equipos de un proyecto (para asignación)
     */
    public function getProjectTeams(Project $project)
    {
        $teams = $project->teams()
            ->select('id', 'name', 'is_general')
            ->withCount(['users as active_users_count' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        return response()->json($teams);
    }

    // ==========================================
    // TASKS
    // ==========================================

    /**
     * Gestión de tareas con filtros
     */
    public function tasks(Request $request)
    {
        $query = Task::with(['assignedUsers', 'creator', 'module.project', 'dependency', 'dependents', 'comments']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('module.project', function ($projectQuery) use ($search) {
                        $projectQuery->where('title', 'like', "%{$search}%");
                    })
                    ->orWhereHas('assignedUsers', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned')) {
            if ($request->assigned === 'assigned') {
                $query->whereHas('assignedUsers');
            } elseif ($request->assigned === 'unassigned') {
                $query->whereDoesntHave('assignedUsers');
            }
        }

        if ($request->filled('project_id')) {
            $query->whereHas('module', function ($moduleQuery) use ($request) {
                $moduleQuery->where('project_id', $request->project_id);
            });
        }

        if ($request->filled('module_id')) {
            $query->where('module_id', $request->module_id);
        }

        if ($request->filled('overdue')) {
            if ($request->overdue === '1') {
                $query->where('end_date', '<', now())
                    ->whereNotIn('status', ['DONE', 'CANCELLED']);
            } elseif ($request->overdue === '0') {
                $query->where(function ($q) {
                    $q->where('end_date', '>=', now())
                        ->orWhereNull('end_date')
                        ->orWhereIn('status', ['DONE', 'CANCELLED']);
                });
            }
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'PENDING')->count(),
            'active_tasks' => Task::where('status', 'ACTIVE')->count(),
            'completed_tasks' => Task::where('status', 'DONE')->count(),
            'overdue_tasks' => Task::where('end_date', '<', now())
                ->whereNotIn('status', ['DONE', 'CANCELLED'])
                ->count(),
            'assigned_tasks' => Task::whereHas('assignedUsers')->count(),
            'unassigned_tasks' => Task::whereDoesntHave('assignedUsers')->count(),
        ];

        // Proyectos y módulos disponibles para filtros
        $projects = Project::orderBy('title')->get();
        $modules = Module::with('project')->orderBy('name')->get();

        return view('admin.tasks', compact('tasks', 'stats', 'projects', 'modules'));
    }

    /**
     * Mostrar formulario para crear tarea
     */
    public function createTask()
    {
        $modules = Module::with('project')->orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $tasks = Task::where('status', '!=', 'CANCELLED')->orderBy('title')->get();

        $priorities = [
            'LOW' => 'Baja',
            'MEDIUM' => 'Media',
            'HIGH' => 'Alta',
            'URGENT' => 'Urgente'
        ];

        $stats = [
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'PENDING')->count(),
            'completed_tasks' => Task::where('status', 'DONE')->count(),
            'overdue_tasks' => Task::where('end_date', '<', now())
                ->whereNotIn('status', ['DONE', 'CANCELLED'])
                ->count(),
        ];

        return view('admin.tasks.create', compact(
            'modules',
            'users',
            'tasks',
            'priorities',
            'stats'
        ));
    }

    /**
     * Crear nueva tarea
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'module_id' => 'nullable|exists:modules,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'nullable|exists:users,id',
            'end_date' => 'nullable|date|after_or_equal:today',
            'depends_on' => 'nullable|exists:tasks,id',
        ]);

        // Verificar dependencia circular
        if ($request->depends_on) {
            $dependency = Task::find($request->depends_on);
            if ($dependency && $this->hasCircularDependency($dependency, $request->depends_on)) {
                return back()->with('error', 'No se puede crear una dependencia circular.');
            }
        }

        // Si se asigna un módulo y usuarios, verificar que los usuarios pertenezcan al proyecto
        if ($request->module_id && $request->assigned_users) {
            $module = Module::find($request->module_id);
            foreach ($request->assigned_users as $userId) {
                $user = User::find($userId);
                $userInProject = $user->teams()
                    ->where('project_id', $module->project_id)
                    ->where('is_active', true)
                    ->exists();

                if (!$userInProject) {
                    return back()->with('error', "El usuario {$user->name} debe ser miembro del proyecto al que pertenece el módulo.");
                }
            }
        }

        // Crear la tarea
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'module_id' => $request->module_id,
            'end_date' => $request->end_date,
            'depends_on' => $request->depends_on,
            'created_by' => auth()->id(),
            'completed_at' => $request->status === 'DONE' ? now() : null,
        ]);

        // Asignar usuarios si se especificaron
        if ($request->assigned_users) {
            $task->assignedUsers()->sync(
                collect($request->assigned_users)->mapWithKeys(function ($userId) {
                    return [$userId => ['assigned_at' => now()]];
                })->toArray()
            );
        }

        return redirect()->route('admin.tasks.edit', $task)
            ->with('success', "Tarea '{$task->title}' creada correctamente.");
    }

    /**
     * Mostrar formulario de edición de la tarea
     */
    public function editTask(Task $task)
    {
        $task->load(['assignedUsers', 'creator', 'module.project', 'dependency', 'dependents', 'comments.user']);

        $modules = Module::with('project')->orderBy('name')->get();

        // Solo usuarios que pertenezcan al proyecto (si la tarea tiene módulo)
        $availableUsers = User::orderBy('name')->get();
        if ($task->module_id) {
            $availableUsers = User::whereHas('teams', function ($query) use ($task) {
                $query->where('project_id', $task->module->project_id)
                    ->where('is_active', true);
            })->orderBy('name')->get();
        }

        $availableTasks = Task::where('id', '!=', $task->id)
            ->where('status', '!=', 'CANCELLED')
            ->orderBy('title')
            ->get();

        $priorities = [
            'LOW' => 'Baja',
            'MEDIUM' => 'Media',
            'HIGH' => 'Alta',
            'URGENT' => 'Urgente'
        ];

        return view('admin.tasks.edit', compact(
            'task',
            'modules',
            'availableUsers',
            'availableTasks',
            'priorities'
        ));
    }

    /**
     * Actualizar tarea
     */
    public function updateTask(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED',
            'module_id' => 'nullable|exists:modules,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'nullable|exists:users,id',
            'end_date' => 'nullable|date',
            'depends_on' => 'nullable|exists:tasks,id',
        ]);

        // Verificar dependencia circular
        if ($request->depends_on && $request->depends_on != $task->depends_on) {
            if ($request->depends_on == $task->id) {
                return back()->with('error', 'Una tarea no puede depender de sí misma.');
            }

            if ($this->hasCircularDependency($task, $request->depends_on)) {
                return back()->with('error', 'No se puede crear una dependencia circular.');
            }
        }

        // Si se asigna un módulo y usuarios, verificar que los usuarios pertenezcan al proyecto
        if ($request->module_id && $request->assigned_users) {
            $module = Module::find($request->module_id);
            foreach ($request->assigned_users as $userId) {
                $user = User::find($userId);
                $userInProject = $user->teams()
                    ->where('project_id', $module->project_id)
                    ->where('is_active', true)
                    ->exists();

                if (!$userInProject) {
                    return back()->with('error', "El usuario {$user->name} debe ser miembro del proyecto al que pertenece el módulo.");
                }
            }
        }

        // Manejar cambio de estado a completado
        $completedAt = $task->completed_at;
        if ($request->status === 'DONE' && $task->status !== 'DONE') {
            $completedAt = now();
        } elseif ($request->status !== 'DONE') {
            $completedAt = null;
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'module_id' => $request->module_id,
            'end_date' => $request->end_date,
            'depends_on' => $request->depends_on,
            'completed_at' => $completedAt,
        ]);

        // Actualizar usuarios asignados
        if ($request->has('assigned_users')) {
            if ($request->assigned_users) {
                $task->assignedUsers()->sync(
                    collect($request->assigned_users)->mapWithKeys(function ($userId) {
                        return [$userId => ['assigned_at' => now()]];
                    })->toArray()
                );
            } else {
                $task->assignedUsers()->detach();
            }
        }

        return redirect()->route('admin.tasks.edit', $task)
            ->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Actualizar estado de la tarea
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:PENDING,ACTIVE,DONE,PAUSED,CANCELLED'
        ]);

        // Manejar cambio de estado a completado
        $completedAt = $task->completed_at;
        if ($request->status === 'DONE' && $task->status !== 'DONE') {
            $completedAt = now();
        } elseif ($request->status !== 'DONE') {
            $completedAt = null;
        }

        $task->update([
            'status' => $request->status,
            'completed_at' => $completedAt,
        ]);

        return back()->with('success', "Estado de la tarea '{$task->title}' actualizado a {$request->status}");
    }

    /**
     * Eliminar tarea
     */
    public function deleteTask(Task $task)
    {
        // Verificar si hay tareas que dependen de esta
        $dependentTasks = Task::where('depends_on', $task->id)->count();
        if ($dependentTasks > 0) {
            return back()->with('error', "No se puede eliminar la tarea '{$task->title}' porque otras tareas dependen de ella.");
        }

        $taskTitle = $task->title;
        $task->delete();

        return redirect()->route('admin.tasks')
            ->with('success', "Tarea '{$taskTitle}' eliminada correctamente.");
    }

    /**
     * Agregar comentario a la tarea
     */
    public function addTaskComment(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'task_id' => $task->id,
        ]);

        return redirect()->route('admin.tasks.edit', $task)
            ->with('success', 'Comentario agregado correctamente.');
    }

    /**
     * Eliminar comentario de la tarea
     */
    public function deleteTaskComment(Task $task, Comment $comment)
    {
        // Verificar que el comentario pertenece a la tarea
        if ($comment->task_id !== $task->id) {
            return redirect()->route('admin.tasks.edit', $task)
                ->with('error', 'El comentario no pertenece a esta tarea.');
        }

        $comment->delete();

        return redirect()->route('admin.tasks.edit', $task)
            ->with('success', 'Comentario eliminado correctamente.');
    }

    /**
     * API endpoint para obtener tareas de un módulo
     */
    public function getModuleTasks(Module $module)
    {
        $tasks = $module->tasks()
            ->select('id', 'title', 'status', 'priority')
            ->with('assignedUsers:id,name')
            ->get();

        return response()->json($tasks);
    }

    /**
     * API endpoint para obtener tareas de un proyecto
     */
    public function getProjectTasks(Project $project)
    {
        $tasks = Task::whereHas('module', function ($query) use ($project) {
            $query->where('project_id', $project->id);
        })
            ->select('id', 'title', 'status', 'priority', 'module_id')
            ->with([
                'module:id,name',
                'assignedUsers:id,name'
            ])
            ->get();

        return response()->json($tasks);
    }

    /**
     * Verificar dependencias circulares
     */
    private function hasCircularDependency($task, $dependencyId, $visited = [])
    {
        if (in_array($dependencyId, $visited)) {
            return true;
        }

        $visited[] = $dependencyId;

        $dependency = Task::find($dependencyId);
        if (!$dependency || !$dependency->depends_on) {
            return false;
        }

        return $this->hasCircularDependency($task, $dependency->depends_on, $visited);
    }
}