<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal del usuario
     */
    public function index()
    {
        $user = Auth::user();

        // Estadísticas principales (3 cards) - USANDO NUEVA ESTRUCTURA
        // Tareas activas del usuario (usando nueva relación n:n)
        $activeTasks = $user->assignedTasks()->where('status', 'ACTIVE')->count();

        // Proyectos activos donde el usuario participa a través de equipos
        $activeProjects = Project::where('status', 'ACTIVE')
            ->whereHas('teams.users', function($query) use ($user) {
                $query->where('users.id', $user->id)
                      ->where('team_user.is_active', true);
            })
            ->count();

        // Comentarios en tareas activas (usando nueva relación)
        $activeComments = Comment::whereHas('task', function($query) use ($user) {
            $query->where('status', 'ACTIVE')
                  ->whereHas('assignedUsers', function($userQuery) use ($user) {
                      $userQuery->where('user_id', $user->id);
                  });
        })->where('user_id', $user->id)->count();

        // Tareas en curso (CON BOTÓN "VER MÁS")
        // Todas las tareas ACTIVAS (sin límite)
        $activeTasks_list = $user->assignedTasks()
            ->with(['module.project', 'creator'])
            ->where('status', 'ACTIVE')
            ->orderByRaw("CASE 
                WHEN priority = 'URGENT' THEN 1 
                WHEN priority = 'HIGH' THEN 2 
                WHEN priority = 'MEDIUM' THEN 3 
                WHEN priority = 'LOW' THEN 4 
                ELSE 5 
            END")
            ->get();

        // Máximo 3 tareas PENDIENTES
        $pendingTasks_list = $user->assignedTasks()
            ->with(['module.project', 'creator'])
            ->where('status', 'PENDING')
            ->orderByRaw("CASE 
                WHEN priority = 'URGENT' THEN 1 
                WHEN priority = 'HIGH' THEN 2 
                WHEN priority = 'MEDIUM' THEN 3 
                WHEN priority = 'LOW' THEN 4 
                ELSE 5 
            END")
            ->limit(3)
            ->get();

        // Combinar las tareas
        $tasksInProgress = $activeTasks_list->concat($pendingTasks_list);

        // Resumen Personal (debajo de tareas)
        $taskSummary = [
            'total' => $user->assignedTasks()->count(),
            'completed' => $user->assignedTasks()->where('status', 'DONE')->count(),
            'in_progress' => $user->assignedTasks()->where('status', 'ACTIVE')->count(),
            'pending' => $user->assignedTasks()->where('status', 'PENDING')->count(),
        ];

        // Proyectos Activos (CON BOTÓN "VER MÁS") - Máximo 3 proyectos activos donde participa
        $activeProjectsList = Project::where('status', 'ACTIVE')
            ->whereHas('teams.users', function($query) use ($user) {
                $query->where('users.id', $user->id)
                      ->where('team_user.is_active', true);
            })
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Comentarios en Curso (CON BOTÓN "VER MÁS")
        // Todos los comentarios en tareas ACTIVAS (sin límite)
        $activeComments_list = Comment::with(['task.module.project', 'task'])
            ->where('user_id', $user->id)
            ->whereHas('task', function($query) use ($user) {
                $query->where('status', 'ACTIVE')
                      ->whereHas('assignedUsers', function($userQuery) use ($user) {
                          $userQuery->where('user_id', $user->id);
                      });
            })
            ->orderByRaw("(SELECT CASE 
                WHEN tasks.priority = 'URGENT' THEN 1 
                WHEN tasks.priority = 'HIGH' THEN 2 
                WHEN tasks.priority = 'MEDIUM' THEN 3 
                WHEN tasks.priority = 'LOW' THEN 4 
                ELSE 5 
            END FROM tasks WHERE tasks.id = comments.task_id)")
            ->orderBy('created_at', 'desc')
            ->get();

        // Máximo 3 comentarios en tareas PENDIENTES
        $pendingComments_list = Comment::with(['task.module.project', 'task'])
            ->where('user_id', $user->id)
            ->whereHas('task', function($query) use ($user) {
                $query->where('status', 'PENDING')
                      ->whereHas('assignedUsers', function($userQuery) use ($user) {
                          $userQuery->where('user_id', $user->id);
                      });
            })
            ->orderByRaw("(SELECT CASE 
                WHEN tasks.priority = 'URGENT' THEN 1 
                WHEN tasks.priority = 'HIGH' THEN 2 
                WHEN tasks.priority = 'MEDIUM' THEN 3 
                WHEN tasks.priority = 'LOW' THEN 4 
                ELSE 5 
            END FROM tasks WHERE tasks.id = comments.task_id)")
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Combinar comentarios
        $commentsInProgress = $activeComments_list->concat($pendingComments_list);

        return view('dashboard', compact(
            'activeTasks',
            'activeProjects', 
            'activeComments',
            'tasksInProgress',
            'taskSummary',
            'activeProjectsList',
            'commentsInProgress'
        ));
    }
}