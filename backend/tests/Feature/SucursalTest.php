<?php

namespace Tests\Feature;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class SucursalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_can_list_sucursales()
    {
        Sucursal::factory()->count(3)->create();

        $response = $this->getJson('/api/sucursales');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_sucursal_with_valid_data()
    {
        $data = [
            'nombre' => 'Central Lab',
            'ciudad' => 'Buenos Aires',
            'pais' => 'Argentina',
            'latitud' => -34.6037,
            'longitud' => -58.3816
        ];

        $response = $this->postJson('/api/sucursales', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('sucursales', ['nombre' => 'Central Lab']);
    }

    public function test_cannot_create_sucursal_with_numeric_country()
    {
        $data = [
            'nombre' => 'Invalid Branch',
            'ciudad' => 'Madrid',
            'pais' => '12345',
            'latitud' => 40.4168,
            'longitud' => -3.7038
        ];

        $response = $this->postJson('/api/sucursales', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['pais']);
    }
}
