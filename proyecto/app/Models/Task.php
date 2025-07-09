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

    // AGREGADO: Simular el campo assigned_to para el controlador
    protected $appends = ['assigned_to'];

    // Relaciones
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // Relación Many-to-Many con usuarios asignados (tabla task_user)
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    // AGREGADO: Simular assignedUser para las vistas que lo esperan
    public function assignedUser(): BelongsTo
    {
        // Retorna el primer usuario asignado como si fuera una relación directa
        $firstUser = $this->assignedUsers()->first();
        if (!$firstUser) {
            return $this->belongsTo(User::class, 'fake_assigned_to')->whereRaw('1 = 0');
        }
        
        // Simular la relación
        return $this->belongsTo(User::class)->where('id', $firstUser->id);
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

    // ACCESSOR: Simular el campo assigned_to que espera el controlador
    public function getAssignedToAttribute()
    {
        $firstUser = $this->assignedUsers()->first();
        return $firstUser ? $firstUser->id : null;
    }

    // MUTATOR: Para cuando se trate de asignar via assigned_to
    public function setAssignedToAttribute($userId)
    {
        if ($userId) {
            // Si ya tiene usuarios asignados, limpiar primero
            $this->assignedUsers()->detach();
            // Asignar el nuevo usuario
            $this->assignedUsers()->attach($userId, ['assigned_at' => now()]);
        } else {
            // Si es null, desasignar todos
            $this->assignedUsers()->detach();
        }
    }

    // Scopes modificados para funcionar con la relación real
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

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

    // SCOPE PERSONALIZADO: Para que el controlador pueda usar whereNotNull('assigned_to')
    public function scopeWhereNotNull($query, $column)
    {
        if ($column === 'assigned_to') {
            return $query->whereHas('assignedUsers');
        }
        return $query->whereNotNull($column);
    }

    // SCOPE PERSONALIZADO: Para que el controlador pueda usar whereNull('assigned_to')
    public function scopeWhereNull($query, $column)
    {
        if ($column === 'assigned_to') {
            return $query->whereDoesntHave('assignedUsers');
        }
        return $query->whereNull($column);
    }

    // Métodos auxiliares originales
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

    // Métodos para manejar asignaciones (mantenidos del original)
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