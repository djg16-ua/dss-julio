<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Mostrar todos los proyectos del usuario autenticado
     * Los usuarios acceden a proyectos a través de sus equipos
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener proyectos del usuario a través de sus equipos
        $projects = $user->teams()
            ->with(['projects' => function($query) {
                $query->with('creator')
                      ->select('projects.*')
                      ->orderByRaw("CASE 
                          WHEN status = 'ACTIVE' THEN 1 
                          WHEN status = 'PENDING' THEN 2 
                          ELSE 3 
                      END")
                      ->orderBy('created_at', 'desc');
            }])
            ->get()
            ->pluck('projects')
            ->flatten()
            ->unique('id')
            ->values();

        // Estadísticas de proyectos del usuario
        $stats = [
            'total_projects' => $projects->count(),
            'active_projects' => $projects->where('status', 'ACTIVE')->count(),
            'pending_projects' => $projects->where('status', 'PENDING')->count(),
            'completed_projects' => $projects->where('status', 'COMPLETED')->count(),
            'public_projects' => $projects->where('public', true)->count(),
            'private_projects' => $projects->where('public', false)->count(),
        ];

        return view('project.index', compact('projects', 'stats'));
    }

    /**
     * Mostrar un proyecto específico
     */
    public function show(Project $project)
    {
        // Verificar que el usuario tiene acceso al proyecto a través de sus equipos
        $userHasAccess = Auth::user()->teams()
            ->whereHas('projects', function($query) use ($project) {
                $query->where('projects.id', $project->id);
            })
            ->exists();

        if (!$userHasAccess) {
            abort(403, 'No tienes acceso a este proyecto.');
        }

        // Cargar relaciones necesarias
        $project->load([
            'creator',
            'teams.users',
            'modules.tasks.assignedUsers',
            'modules.tasks.creator'
        ]);

        // Estadísticas del proyecto
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
            'team_members' => $project->teams->sum(function($team) {
                return $team->users->where('pivot.is_active', true)->count();
            }),
        ];

        return view('projects.show', compact('project', 'projectStats'));
    }

    /**
     * Mostrar formulario para crear nuevo proyecto
     */
    public function create()
    {
        // Obtener equipos del usuario para asignar al proyecto
        $userTeams = Auth::user()->teams()->where('is_active', true)->get();
        
        return view('projects.create', compact('userTeams'));
    }

    /**
     * Almacenar nuevo proyecto
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'public' => 'boolean',
            'teams' => 'required|array|min:1',
            'teams.*' => 'exists:teams,id'
        ]);

        // Verificar que el usuario pertenece a todos los equipos seleccionados
        $userTeamIds = Auth::user()->teams()->pluck('teams.id')->toArray();
        $selectedTeamIds = $request->teams;
        
        if (!empty(array_diff($selectedTeamIds, $userTeamIds))) {
            return back()->withErrors(['teams' => 'Solo puedes asignar equipos a los que perteneces.']);
        }

        DB::beginTransaction();
        try {
            // Crear el proyecto
            $project = Project::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'PENDING',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'public' => $request->boolean('public'),
                'created_by' => Auth::id(),
            ]);

            // Asignar equipos al proyecto
            $project->teams()->attach($selectedTeamIds, [
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('projects.show', $project)
                ->with('success', 'Proyecto creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al crear el proyecto: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario para editar proyecto
     */
    public function edit(Project $project)
    {
        // Verificar acceso
        $userHasAccess = Auth::user()->teams()
            ->whereHas('projects', function($query) use ($project) {
                $query->where('projects.id', $project->id);
            })
            ->exists();

        if (!$userHasAccess) {
            abort(403, 'No tienes acceso a este proyecto.');
        }

        // Cargar equipos del usuario y equipos asignados al proyecto
        $userTeams = Auth::user()->teams()->where('is_active', true)->get();
        $project->load('teams');
        
        return view('projects.edit', compact('project', 'userTeams'));
    }

    /**
     * Actualizar proyecto
     */
    public function update(Request $request, Project $project)
    {
        // Verificar acceso
        $userHasAccess = Auth::user()->teams()
            ->whereHas('projects', function($query) use ($project) {
                $query->where('projects.id', $project->id);
            })
            ->exists();

        if (!$userHasAccess) {
            abort(403, 'No tienes acceso a este proyecto.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:PENDING,ACTIVE,COMPLETED,PAUSED,CANCELLED',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'public' => 'boolean',
            'teams' => 'required|array|min:1',
            'teams.*' => 'exists:teams,id'
        ]);

        // Verificar que el usuario pertenece a todos los equipos seleccionados
        $userTeamIds = Auth::user()->teams()->pluck('teams.id')->toArray();
        $selectedTeamIds = $request->teams;
        
        if (!empty(array_diff($selectedTeamIds, $userTeamIds))) {
            return back()->withErrors(['teams' => 'Solo puedes asignar equipos a los que perteneces.']);
        }

        DB::beginTransaction();
        try {
            // Actualizar el proyecto
            $project->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'public' => $request->boolean('public'),
            ]);

            // Actualizar equipos asignados
            $project->teams()->sync(collect($selectedTeamIds)->mapWithKeys(function($teamId) {
                return [$teamId => [
                    'assigned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]];
            }));

            DB::commit();

            return redirect()->route('projects.show', $project)
                ->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al actualizar el proyecto: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar proyecto
     */
    public function destroy(Project $project)
    {
        // Solo el creador del proyecto puede eliminarlo
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Solo el creador del proyecto puede eliminarlo.');
        }

        $projectTitle = $project->title;
        
        DB::beginTransaction();
        try {
            $project->delete();
            DB::commit();

            return redirect()->route('project.index')
                ->with('success', "Proyecto '{$projectTitle}' eliminado exitosamente.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('project.index')
                ->withErrors(['error' => 'Error al eliminar el proyecto: ' . $e->getMessage()]);
        }
    }
}