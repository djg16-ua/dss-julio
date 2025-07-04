<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'assigned_to',
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

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
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

    public function comment(): HasOne
    {
        return $this->hasOne(Comment::class);
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

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
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
}
