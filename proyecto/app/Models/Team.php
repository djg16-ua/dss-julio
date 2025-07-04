<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relaciones
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'joined_at', 'left_at', 'is_active')
            ->withTimestamps();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_team')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_team')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    // Scopes
    public function scopeWithActiveUsers($query)
    {
        return $query->whereHas('users', function ($q) {
            $q->where('is_active', true);
        });
    }

    // Métodos auxiliares
    public function getActiveUsersCount(): int
    {
        return $this->users()->where('is_active', true)->count();
    }
}