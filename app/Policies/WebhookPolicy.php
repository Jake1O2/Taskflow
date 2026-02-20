<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Webhook;

class WebhookPolicy
{
    public function view(User $user, Webhook $webhook): bool
    {
        return $webhook->user_id === $user->id;
    }

    public function update(User $user, Webhook $webhook): bool
    {
        return $webhook->user_id === $user->id;
    }
}
