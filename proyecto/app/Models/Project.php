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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_project')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'project_team')
            ->withPivot('assigned_at')
            ->withTimestamps();
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

    // Métodos auxiliares
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
}