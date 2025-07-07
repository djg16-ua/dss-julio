<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Estadísticas para administradores (todo el sistema)
            $userStats = [
                'projects' => Project::count(),
                'tasks' => Task::count(),
                'teams' => Team::count(),
                'users' => User::count(),
            ];
            
            // Proyectos recientes del sistema
            $recentProjects = Project::with('creator')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
        } else {
            // Estadísticas para usuarios normales (solo sus datos)
            $userStats = [
                'projects' => Project::where('created_by', $user->id)->count(),
                'tasks' => Task::where('assigned_to', $user->id)->count(),
                'teams' => DB::table('team_user')
                    ->where('user_id', $user->id)
                    ->where('is_active', true)
                    ->count(),
                'users' => '156h', // Placeholder para horas trabajadas
            ];
            
            // Proyectos del usuario
            $recentProjects = Project::where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('dashboard', compact('userStats', 'recentProjects'));
    }
}