<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Client::create([
            'city_id' => 1,
            'full_name' => 'Cliente Genérico',
            'phone_number' => '1234567890',
            'email' => 'cliente@example.com',
            'address' => 'Dirección Genérica',
        ]);

        $cities = City::all()->pluck('id')->toArray();
        for ($i = 2; $i <= 10; $i++) {
            # code...
            Client::create([
                'city_id' => fake()->randomElement($cities),
                'full_name' => fake()->name(),
                'phone_number' => fake()->unique()->phoneNumber(),
                'email' => fake()->unique()->safeEmail(),
                'address' => fake()->address(),
            ]);
        }
    }
}
