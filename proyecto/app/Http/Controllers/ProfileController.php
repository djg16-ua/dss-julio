<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        
        // Obtener estadísticas reales del usuario basadas en las migraciones
        $userStats = [
            'projects' => DB::table('projects')->where('created_by', $user->id)->count(),
            'tasks' => DB::table('tasks')->where('assigned_to', $user->id)->count(),
            'teams' => DB::table('team_user')->where('user_id', $user->id)->where('is_active', true)->count(),
            'comments' => DB::table('comments')->where('user_id', $user->id)->count(),
        ];
        
        // Obtener equipos del usuario
        $userTeams = DB::table('team_user')
            ->join('teams', 'team_user.team_id', '=', 'teams.id')
            ->where('team_user.user_id', $user->id)
            ->where('team_user.is_active', true)
            ->select(
                'teams.id',
                'teams.name', 
                'teams.description', 
                'team_user.role', 
                'team_user.joined_at'
            )
            ->get()
            ->map(function ($item) {
                return (object) [
                    'team' => (object) [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description
                    ],
                    'role' => $item->role,
                    'joined_at' => \Carbon\Carbon::parse($item->joined_at)
                ];
            });
        
        // Obtener proyectos creados por el usuario
        $userProjects = DB::table('projects')
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($project) {
                return (object) [
                    'id' => $project->id,
                    'title' => $project->title,
                    'description' => $project->description,
                    'status' => $project->status,
                    'start_date' => $project->start_date ? \Carbon\Carbon::parse($project->start_date) : null,
                    'public' => (bool) $project->public
                ];
            });
        
        // Obtener tareas asignadas al usuario
        $userTasks = DB::table('tasks')
            ->join('modules', 'tasks.module_id', '=', 'modules.id')
            ->where('tasks.assigned_to', $user->id)
            ->select(
                'tasks.*', 
                'modules.name as module_name'
            )
            ->orderBy('tasks.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($task) {
                return (object) [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'end_date' => $task->end_date ? \Carbon\Carbon::parse($task->end_date) : null,
                    'module' => (object) ['name' => $task->module_name]
                ];
            });
        
        // Simular actividad reciente
        $recentActivity = collect([
            [
                'icon' => 'plus-circle',
                'title' => 'Cuenta creada',
                'description' => 'Te has unido a TaskFlow',
                'time' => $user->created_at->diffForHumans()
            ]
        ]);
        
        return view('profile.show', compact(
            'userStats', 
            'userTeams', 
            'userProjects', 
            'userTasks', 
            'recentActivity'
        ));
    }
    
    /**
     * Mostrar el formulario de edición del perfil
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }
    
    /**
     * Actualizar la información del perfil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        // Si el email cambió, resetear la verificación
        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }
        
        $user->update($validated);
        
        return back()->with('status', 'profile-updated');
    }
    
    /**
     * Eliminar la cuenta del usuario
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
        
        $user = Auth::user();
        
        // Cerrar sesión antes de eliminar
        Auth::logout();
        
        // Eliminar el usuario (las foreign keys se encargarán del resto)
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }
}