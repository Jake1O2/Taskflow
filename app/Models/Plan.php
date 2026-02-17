<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = ['name', 'price', 'stripe_price_id', 'features', 'max_projects', 'max_teams'];
    
    protected $casts = [
        'features' => 'array',
        'price' => 'integer',
        'max_projects' => 'integer',
        'max_teams' => 'integer',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}