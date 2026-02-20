<?php

namespace Tests\Feature;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_stores_hashed_token_and_plain_token_can_authenticate(): void
    {
        $password = 'secret-password';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertOk()->assertJsonPath('success', true);
        $plainToken = $response->json('data.token');

        $this->assertNotEmpty($plainToken);

        $apiToken = ApiToken::where('user_id', $user->id)->first();
        $this->assertNotNull($apiToken);
        $this->assertSame(hash('sha256', $plainToken), $apiToken->token);
        $this->assertNotSame($plainToken, $apiToken->token);

        $projectsResponse = $this->withToken($plainToken)->getJson('/api/projects');
        $projectsResponse->assertOk()->assertJsonPath('success', true);
    }

    public function test_legacy_plaintext_token_is_still_accepted_and_rehashed(): void
    {
        $user = User::factory()->create();
        $legacyPlainToken = 'sk_live_legacy_token_abcdefghijklmnopqrstuvwxyz1234';

        $token = ApiToken::create([
            'user_id' => $user->id,
            'name' => 'Legacy token',
            'token' => $legacyPlainToken,
            'expires_at' => now()->addHour(),
        ]);

        $response = $this->withToken($legacyPlainToken)->getJson('/api/projects');
        $response->assertOk()->assertJsonPath('success', true);

        $token->refresh();
        $this->assertSame(hash('sha256', $legacyPlainToken), $token->token);
    }
}
