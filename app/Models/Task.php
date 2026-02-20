<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Schema;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'priority',
        'assigned_to',
        'assigned_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'assigned_at' => 'datetime',
        ];
    }

    /**
     * Get the project that the task belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the comments for the task.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * User assigned to this task.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * User that created/assigned this task when created_by exists.
     */
    public function assigner(): BelongsTo
    {
        $foreignKey = Schema::hasColumn($this->getTable(), 'created_by') ? 'created_by' : 'assigned_to';

        return $this->belongsTo(User::class, $foreignKey);
    }

    /**
     * Assignment history for this task.
     */
    public function assignmentLogs(): HasMany
    {
        return $this->hasMany(TaskAssignmentLog::class);
    }

    /**
     * Activity history for this task.
     */
    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable')->latest('created_at');
    }

    /**
     * Scope pour filtrer par statut.
     */
    public function scopeWhereStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour filtrer par priorité.
     */
    public function scopeWherePriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    /**
     * Check if task is assigned to a given user.
     */
    public function isAssignedTo(int|string|null $userId): bool
    {
        if ($userId === null) {
            return false;
        }

        return (int) $this->assigned_to === (int) $userId;
    }

    /**
     * Scope for tasks assigned to a user.
     */
    public function scopeWhereAssignedTo(Builder $query, int|string $userId): Builder
    {
        return $query->where('assigned_to', (int) $userId);
    }
}
