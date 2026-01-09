<?php

namespace Database\Seeders;

use App\Models\Quality;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $name = "Calidad 0$i";
            $slug = Str::slug($name);

            Quality::create([
                'name' => $name,
                'description' => "Descripción de la calidad 0$i",
                'slug' => $slug,
                'image' => asset('assets/img/qualities.png'),
            ]);
        }
    }
}
