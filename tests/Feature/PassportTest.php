<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Client;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\Token;
use Laravel\Passport\RefreshToken;
use Tests\TestCase;

use function PHPUnit\Framework\assertJson;

class PassportTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_request_token(): void
    {
        $adminUser = User::where('email', 'adminz@gmail.com')->first();
        $passwordClient = Client::where('name', 'Laravel Password Grant Client')->first();

        $host = env('app.url', 'http://localhost:8000');
        $data = [
            'grant_type' => 'password',
            'client_id' => $passwordClient->id,
            'client_secret' => $passwordClient->secret,
            'username' => $adminUser->email,
            'password' => '123123',
            'scope' => '',
        ];

        // $response = Http::asForm()->post($url, $data);
        // $this->assertTrue($response->status() === 200);
        // $this->assertTrue($response->json() !== null);
        // $this->assertArrayHasKey("token_type", $response->json());
        // $this->assertArrayHasKey("expires_in", $response->json());
        // $this->assertArrayHasKey("access_token", $response->json());
        // $this->assertArrayHasKey("refresh_token", $response->json());

        $response1 = $this->json('POST', $host . '/oauth/token', $data);
        $response1->assertStatus(200);
        $response1->assertJsonStructure([
            "token_type",
            "expires_in",
            "access_token",
            "refresh_token"
        ]);

        $headers = ['Authorization' => $response1->json('token_type') . ' ' . $response1->json('access_token')];
        $response2 = $this->json('GET', $host . '/api/user', [], $headers);
        $response2->assertStatus(200);
        $response2->assertJsonStructure([
            "id",
            "name",
            "email",
            "email_verified_at",
            "created_at",
            "updated_at"
        ]);
    }
}
