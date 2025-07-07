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
     * Estadísticas avanzadas
     */
    public function analytics()
    {
        // Datos para gráficos
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $projectGrowth = Project::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $taskCompletionRate = [
            'completed' => Task::where('status', 'DONE')->count(),
            'total' => Task::count()
        ];

        return view('admin.analytics', compact(
            'userGrowth',
            'projectGrowth',
            'taskCompletionRate'
        ));
    }
}