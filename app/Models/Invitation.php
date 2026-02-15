<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = ['team_id', 'email', 'token', 'status', 'accepted_at'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}