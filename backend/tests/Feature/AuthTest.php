<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'jose@example.com',
            'password' => bcrypt('Lumberjack'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jose@example.com',
            'password' => 'Lumberjack',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['access_token', 'token_type']
            ]);
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'jose@example.com',
            'password' => bcrypt('Lumberjack'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jose@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }
}
