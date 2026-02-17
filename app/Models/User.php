<?php
﻿<?php

namespace App\Models;

use App\Models\Team;
use App\Models\Project;
use App\Models\Webhook;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the projects for the user.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get the teams owned by the user.
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the teams the user is a member of.
     */
    public function teamMemberships(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members')->withTimestamps();
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the webhooks for the user.
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    /**
     * Get unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Get the user's current subscription.
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    /**
     * Check if the user has a specific plan.
     */
    public function hasPlan(string $planName): bool
    {
        return $this->subscription?->plan?->name === $planName;
    }

    /**
     * Get the features associated with the user's plan.
     */
    public function getPlanFeatures(): array
    {
        return $this->subscription?->plan?->features ?? [];
    }

    /**
     * Stats (projects, tasks, teams) avec cache pour limiter les requêtes.
     */
    public function getCachedStats(int $ttlSeconds = 120): array
    {
        return Cache::remember(
            'taskflow.stats.' . $this->id,
            $ttlSeconds,
            fn () => $this->computeStats()
        );
    }

    /**
     * Calcule les stats avec requêtes optimisées (eager loading / withCount).
     */
    protected function computeStats(): array
    {
        $projects = $this->projects()->withCount('tasks')->get();
        return [
            'projects' => $projects->count(),
            'tasks' => $projects->sum('tasks_count'),
            'teams' => $this->teams()->count(),
        ];
    }

    /**
     * Invalide le cache des stats (à appeler après create/update/delete).
     */
    public function forgetStatsCache(): void
    {
        Cache::forget('taskflow.stats.' . $this->id);
    }
}
