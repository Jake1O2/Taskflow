<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'plan_id', 'stripe_subscription_id', 'status', 'current_period_start', 'current_period_end', 'cancel_at'];
    
    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancel_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canCreateProjects(): bool
    {
        $max = $this->plan->max_projects;
        if ($max === null) return true; // unlimited
        return $this->user->projects()->count() < $max;
    }

    public function canCreateTeams(): bool
    {
        $max = $this->plan->max_teams;
        if ($max === null) return true; // unlimited
        return $this->user->teams()->count() < $max;
    }
}