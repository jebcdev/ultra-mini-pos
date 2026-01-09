<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i <=5 ; $i++) {
            $name = "Categoría 0$i";
            $slug = Str::slug($name);

            Category::create([
                'name' => $name,
                'description' => "Descripción de la categoría 0$i",
                'slug' => $slug,
                'image' => asset('assets/img/categories.png'),
            ]);
        }
    }
}
