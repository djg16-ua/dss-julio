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
     * Gestión de equipos
     */
    public function teams()
    {
        $teams = Team::withCount(['users', 'projects', 'modules'])
            ->with(['users' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_teams' => Team::count(),
            'active_members' => DB::table('team_user')->where('is_active', true)->count(),
            'total_assignments' => DB::table('project_team')->count(),
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