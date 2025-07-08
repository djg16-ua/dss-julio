<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'end_date',
        'completed_at',
        'module_id',
        'created_by',
        'depends_on',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relaciones
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // NUEVA RELACIÓN: Many-to-Many con usuarios asignados
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dependency(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'depends_on');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(Task::class, 'depends_on');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // ACTUALIZADO: Scope para buscar tareas asignadas a un usuario específico
    public function scopeAssignedTo($query, $userId)
    {
        return $query->whereHas('assignedUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
            ->whereNotIn('status', ['DONE', 'CANCELLED']);
    }

    // Métodos auxiliares
    public function isCompleted(): bool
    {
        return $this->status === 'DONE';
    }

    public function isOverdue(): bool
    {
        return $this->end_date && $this->end_date->isPast() && !$this->isCompleted();
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'DONE',
            'completed_at' => now(),
        ]);
    }

    // NUEVOS MÉTODOS para manejar asignaciones
    public function assignUser($userId, $assignedAt = null): void
    {
        $this->assignedUsers()->attach($userId, [
            'assigned_at' => $assignedAt ?? now()
        ]);
    }

    public function unassignUser($userId): void
    {
        $this->assignedUsers()->detach($userId);
    }

    public function reassignUsers(array $userIds): void
    {
        $this->assignedUsers()->sync(
            collect($userIds)->mapWithKeys(function ($userId) {
                return [$userId => ['assigned_at' => now()]];
            })->toArray()
        );
    }

    public function isAssignedTo($userId): bool
    {
        return $this->assignedUsers()->where('user_id', $userId)->exists();
    }

    public function getAssignedUserNames(): string
    {
        return $this->assignedUsers->pluck('name')->join(', ');
    }
}