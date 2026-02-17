<?php

namespace App\Services;

use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookDispatcher
{
    /**
     * Dispatch an event to all active webhooks subscribed to it.
     */
    public function dispatch(string $event, array $data, int $userId): void
    {
        $webhooks = Webhook::where('user_id', $userId)
            ->where('status', 'active')
            ->get();

        foreach ($webhooks as $webhook) {
            if (in_array($event, $webhook->events)) {
                $this->sendPayload($webhook->url, [
                    'event' => $event,
                    'timestamp' => now()->toIso8601String(),
                    'data' => $data,
                ]);
            }
        }
    }

    /**
     * Send the actual HTTP request.
     */
    protected function sendPayload(string $url, array $payload): void
    {
        try {
            Http::timeout(5)->post($url, $payload);
        }
        catch (\Exception $e) {
            Log::error("Webhook failed for URL: {$url}. Error: " . $e->getMessage());
        }
    }
}
