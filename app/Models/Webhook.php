<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'events',
        'active',
        'secret',
    ];

    protected $casts = [
        'events' => 'array',
        'active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    /** For views that still use status (active/inactive). */
    public function getStatusAttribute(): string
    {
        return $this->active ? 'active' : 'inactive';
    }
}
