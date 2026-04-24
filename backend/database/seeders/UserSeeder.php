<?php

namespace database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jose@example.com'],
            [
                'name' => 'Jose',
                'password' => Hash::make('Lumberjack'),
            ]
        );
    }
}
