<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'priority',
        'category',
        'status',
        'project_id',
        'depends_on',
        'is_core',
    ];

    protected $casts = [
        'is_core' => 'boolean',
    ];

    // Relaciones
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function dependency(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'depends_on');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(Module::class, 'depends_on');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'module_team')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // Scopes
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCore($query)
    {
        return $query->where('is_core', true);
    }

    public function scopeTeamsForProject($query)
    {
        return $this->teams()->whereHas('project', function ($q) {
            $q->where('id', $this->project_id);
        });
    }

    // Métodos auxiliares
    public function isCore(): bool
    {
        return $this->is_core;
    }

    public function getTasksProgressPercentage(): float
    {
        $totalTasks = $this->tasks->count();
        $completedTasks = $this->tasks->where('status', 'DONE')->count();
        
        return $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
    }

}