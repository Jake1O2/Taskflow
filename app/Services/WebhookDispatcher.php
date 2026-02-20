<?php

namespace App\Services;

use App\Jobs\SendWebhook;
use App\Models\Webhook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebhookDispatcher
{
    /**
     * Dispatch an event to all active webhooks subscribed to it.
     *
     * This method finds all webhooks for a given user (or the authenticated user)
     * that are active and subscribed to the specified event, then dispatches a job
     * to send the payload to each of them asynchronously.
     *
     * Example usage in a controller:
     * app(WebhookDispatcher::class)->dispatch('project.created', $project->toArray());
     *
     * @param string $event The event name (e.g., 'task.created').
     * @param array $data The payload data to send.
     * @param int|null $userId The ID of the user who owns the webhooks. If null, the authenticated user is used.
     * @param bool $notifySlack Whether to trigger Slack notifier through notifyEvent.
     */
    public function dispatch(string $event, array $data, ?int $userId = null, bool $notifySlack = true): void
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) {
            return;
        }

        $webhooks = Webhook::where('user_id', $userId)
            ->where('active', true)
            ->whereJsonContains('events', $event)
            ->get();

        foreach ($webhooks as $webhook) {
            SendWebhook::dispatch($webhook, $event, $data);
        }

        if ($notifySlack) {
            try {
                app(SlackNotifier::class)->notifyEvent($event, $data);
            } catch (\Throwable $exception) {
                Log::warning('Slack notifier dispatch failed', [
                    'event' => $event,
                    'user_id' => $userId,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }
}
