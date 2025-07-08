<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // MUTADOR INTELIGENTE: Solo hashea si la contraseña no está ya hasheada
    public function setPasswordAttribute($value)
    {
        // Si el valor ya está hasheado (empieza con $2y$), no lo vuelvas a hashear
        if (preg_match('/^\$2[ayb]\$.{56}$/', $value)) {
            $this->attributes['password'] = $value;
        } else {
            // Si es texto plano, hashearlo
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Relaciones
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->withPivot('role', 'joined_at', 'left_at', 'is_active')
            ->withTimestamps();
    }

    // Método para obtener proyectos del usuario a través de equipos
    public function projects()
    {
        return $this->teams()
            ->with('project')
            ->get()
            ->pluck('project')
            ->unique('id');
    }

    // Verificar si el usuario trabaja en un proyecto específico
    public function belongsToProject($projectId): bool
    {
        return $this->teams()
            ->whereHas('project', function ($query) use ($projectId) {
                $query->where('id', $projectId);
            })
            ->where('is_active', true)
            ->exists();
    }

    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    // NUEVA RELACIÓN: Many-to-Many con tareas asignadas
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_user')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role', 'ADMIN');
    }

    public function scopeUsers($query)
    {
        return $query->where('role', 'USER');
    }

    // NUEVO SCOPE: Usuarios con tareas asignadas
    public function scopeWithAssignedTasks($query)
    {
        return $query->whereHas('assignedTasks');
    }

    // Métodos auxiliares
    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isUser(): bool
    {
        return $this->role === 'USER';
    }

    // NUEVOS MÉTODOS para manejar tareas asignadas
    public function hasTasksAssigned(): bool
    {
        return $this->assignedTasks()->exists();
    }

    public function getActiveTasksCount(): int
    {
        return $this->assignedTasks()->where('status', 'ACTIVE')->count();
    }

    public function getPendingTasksCount(): int
    {
        return $this->assignedTasks()->where('status', 'PENDING')->count();
    }

    public function getCompletedTasksCount(): int
    {
        return $this->assignedTasks()->where('status', 'DONE')->count();
    }

    public function getOverdueTasksCount(): int
    {
        return $this->assignedTasks()
            ->where('end_date', '<', now())
            ->whereNotIn('status', ['DONE', 'CANCELLED'])
            ->count();
    }

    public function getTasksByPriority(string $priority): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assignedTasks()->where('priority', $priority)->get();
    }

    public function getTasksByStatus(string $status): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assignedTasks()->where('status', $status)->get();
    }
}