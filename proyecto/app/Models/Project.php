<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'public',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'public' => 'boolean',
    ];

    // Relaciones
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function generalTeam(): HasMany
    {
        return $this->hasMany(Team::class)->where('is_general', true);
    }

    public function customTeams(): HasMany
    {
        return $this->hasMany(Team::class)->where('is_general', false);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function getProgressPercentage(): float
    {
        $totalTasks = $this->modules->sum(fn($module) => $module->tasks->count());
        $completedTasks = $this->modules->sum(fn($module) => $module->tasks->where('status', 'DONE')->count());
        
        return $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
    }

    // Obtener todos los usuarios del proyecto (del equipo general)
    public function users()
    {
        $generalTeam = $this->teams()->where('is_general', true)->first();
        return $generalTeam ? $generalTeam->users()->where('is_active', true) : collect();
    }

    // Obtener el equipo general del proyecto
    public function getGeneralTeam(): ?Team
    {
        return $this->teams()->where('is_general', true)->first();
    }

    // Boot method para crear automáticamente el equipo general
    protected static function boot()
    {
        parent::boot();

        static::created(function ($project) {
            // Crear automáticamente el equipo general al crear un proyecto
            $project->teams()->create([
                'name' => 'General',
                'description' => 'Equipo general del proyecto - incluye todos los miembros',
                'is_general' => true,
            ]);
        });
    }
}