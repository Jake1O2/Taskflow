<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlackWorkspace extends Model
{
    protected $fillable = [
        'user_id',
        'team_id',
        'slack_team_id',
        'slack_token',
        'slack_channel_id',
        'channel_name',
        'active',
        'events',
    ];

    protected $casts = [
        'events' => 'array',
        'active' => 'boolean',
        'slack_token' => 'encrypted',
    ];

    protected $hidden = [
        'slack_token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
