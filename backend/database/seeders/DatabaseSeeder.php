<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sucursal;
use App\Models\Empleado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jose@example.com'],
            [
                'name' => 'Jose Vivas',
                'password' => Hash::make('Lumberjack'),
            ]
        );

        // Creamos 30 sucursales, cada una con 50 empleados para probar fuerte el paginado
        Sucursal::factory()
            ->count(30)
            ->has(Empleado::factory()->count(50), 'empleados')
            ->create();
    }
}
