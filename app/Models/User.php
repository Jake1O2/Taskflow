<?php

namespace App\Models;

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
        'stripe_customer_id',
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
     * Get Slack workspace integrations for the user.
     */
    public function slackWorkspaces(): HasMany
    {
        return $this->hasMany(SlackWorkspace::class);
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
        $plan = $this->subscription?->plan;
        if (! $plan) {
            return [];
        }

        $isUnlimitedPlan = $plan->max_projects === null && $plan->max_teams === null;

        if ($isUnlimitedPlan) {
            static $allPlanFeatures = null;

            if ($allPlanFeatures === null) {
                $allPlanFeatures = Plan::query()
                    ->pluck('features')
                    ->flatten()
                    ->filter(fn ($feature) => $feature !== 'all_features')
                    ->unique()
                    ->values()
                    ->all();
            }

            return $allPlanFeatures;
        }

        return $plan->features ?? [];
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

    /**
     * Get the API tokens for the user.
     */
    public function apiTokens(): HasMany
    {
        return $this->hasMany(\App\Models\ApiToken::class);
    }

    /**
     * Get tasks currently assigned to this user.
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Assignment logs created by this user.
     */
    public function taskAssignmentsMade(): HasMany
    {
        return $this->hasMany(TaskAssignmentLog::class, 'assigned_by');
    }

    /**
     * Assignment logs where this user is the assignee.
     */
    public function taskAssignmentsReceived(): HasMany
    {
        return $this->hasMany(TaskAssignmentLog::class, 'assigned_to');
    }

    /**
     * Activity entries performed by this user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
