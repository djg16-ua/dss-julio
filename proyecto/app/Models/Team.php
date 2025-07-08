<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'is_general',
    ];

    protected $casts = [
        'is_general' => 'boolean',
    ];

    // Relaciones
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'joined_at', 'left_at', 'is_active')
            ->withTimestamps();
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_team')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    // Scopes
    public function scopeGeneral($query)
    {
        return $query->where('is_general', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_general', false);
    }

    public function scopeWithActiveUsers($query)
    {
        return $query->whereHas('users', function ($q) {
            $q->where('is_active', true);
        });
    }

    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    // Métodos auxiliares
    public function getActiveUsersCount(): int
    {
        return $this->users()->where('is_active', true)->count();
    }

    public function isGeneral(): bool
    {
        return $this->is_general;
    }

    public function isCustom(): bool
    {
        return !$this->is_general;
    }

    // Boot method para crear automáticamente el equipo general
    protected static function boot()
    {
        parent::boot();

        // Prevenir eliminación del equipo general
        static::deleting(function ($team) {
            if ($team->is_general) {
                throw new \Exception('No se puede eliminar el equipo general del proyecto.');
            }
    /**
     * Obtener total de módulos a través de proyectos
     */
    public function getModulesCountAttribute(): int
    {
        return $this->projects->sum(function($project) {
            return $project->modules->count();
        });
    }
}