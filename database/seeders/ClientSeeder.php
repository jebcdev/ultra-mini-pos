<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::all()->pluck('id')->toArray();
        $clients = [];

        // Cliente inicial genérico
        $clients[] = [
            'city_id' => 1,
            'full_name' => 'Cliente Genérico',
            'phone_number' => '1234567890',
            'email' => 'cliente@example.com',
            'address' => 'Dirección Genérica',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Generar clientes adicionales
        for ($i = 2; $i <= 10; $i++) {
            $clients[] = [
                'city_id' => fake()->randomElement($cities),
                'full_name' => fake()->name(),
                'phone_number' => fake()->unique()->phoneNumber(),
                'email' => fake()->unique()->safeEmail(),
                'address' => fake()->address(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar todos los registros de una sola vez
        Client::insert($clients);
    }
}
