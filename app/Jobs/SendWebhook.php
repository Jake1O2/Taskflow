<?php

namespace App\Jobs;

use App\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;

    public array $backoff = [1, 2, 4];

    public function __construct(
        public Webhook $webhook,
        public string $event,
        public array $payload
    ) {}

    public function handle(): void
    {
        $timestamp = now()->timestamp;
        $body = json_encode($this->payload);
        $signature = hash_hmac('sha256', $body, $this->webhook->secret ?? '');

        $response = Http::timeout(10)
            ->withHeaders([
                'X-TaskFlow-Event' => $this->event,
                'X-TaskFlow-Timestamp' => (string) $timestamp,
                'X-TaskFlow-Signature' => $signature,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->webhook->url, $this->payload);

        $this->logAttempt($response);

        if (! $response->successful()) {
            throw new \RuntimeException("Webhook delivery failed: HTTP {$response->status()}");
        }
    }

    private function logAttempt(Response $response): void
    {
        $this->webhook->logs()->create([
            'event' => $this->event,
            'payload' => $this->payload,
            'response' => $response->body(),
            'status_code' => $response->status(),
            'delivered_at' => $response->successful() ? now() : null,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->webhook->logs()->create([
            'event' => $this->event,
            'payload' => $this->payload,
            'response' => $exception->getMessage(),
            'status_code' => null,
            'delivered_at' => null,
        ]);
    }
}
