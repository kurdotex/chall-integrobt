<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sucursal>
 */
class SucursalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company() . ' Branch',
            'ciudad' => $this->faker->city(),
            'pais' => $this->faker->country(),
            'latitud' => $this->faker->latitude(-35, -31),
            'longitud' => $this->faker->longitude(-60, -55),
        ];
    }
}
