<?php

namespace Database\Seeders;

use App\Enums\UnitOfMeasure;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1, // Categoría 01
                'quality_id' => 1, // Calidad 01
                'name' => 'Laptop Gaming Pro',
                'sku' => 'LAP-GAM-PRO-001',
                'images' => [asset('assets/img/products.png')],
                'description' => 'Laptop de alta gama para gaming con procesador Intel i7, 16GB RAM y tarjeta gráfica RTX 4060.',
                'purchase_price' => 4500000.00,
                'sale_price' => 5800000.00,
                'stock' => 3, // Cercano al mínimo (min 2 +1)
                'stock_min' => 2,
                'stock_max' => 20,
                'unit' => UnitOfMeasure::unit->value,
                'width' => 35, // cm
                'height' => 25, // cm
                'weight' => 2500, // grams
                'is_active' => true,
            ],
            [
                'category_id' => 2, // Categoría 02
                'quality_id' => 2, // Calidad 02
                'name' => 'Mouse Óptico Inalámbrico',
                'sku' => 'MOU-OPT-WIR-002',
                'images' => [asset('assets/img/products.png')],
                'description' => 'Mouse ergonómico inalámbrico con batería de larga duración y sensor óptico de 1600 DPI.',
                'purchase_price' => 45000.00,
                'sale_price' => 75000.00,
                'stock' => 99, // Cercano al máximo (max 100 -1)
                'stock_min' => 5,
                'stock_max' => 100,
                'unit' => UnitOfMeasure::unit->value,
                'width' => 6, // cm
                'height' => 10, // cm
                'weight' => 80, // grams
                'is_active' => true,
            ],
            [
                'category_id' => 3, // Categoría 03
                'quality_id' => 3, // Calidad 03
                'name' => 'Café Molido Premium 1kg',
                'sku' => 'CAF-MOL-PRE-003',
                'images' => [asset('assets/img/products.png')],
                'description' => 'Café 100% arábica molido, origen Colombia, tueste medio. Paquete de 1 kilogramo.',
                'purchase_price' => 28000.00,
                'sale_price' => 42000.00,
                'stock' => 25,
                'stock_min' => 3,
                'stock_max' => 50,
                'unit' => UnitOfMeasure::kilogram->value,
                'width' => 15, // cm
                'height' => 20, // cm
                'weight' => 1000, // grams
                'is_active' => true,
            ],
            [
                'category_id' => 4, // Categoría 04
                'quality_id' => 4, // Calidad 04
                'name' => 'Televisor LED 55" 4K UHD',
                'sku' => 'TV-LED-55-4K-004',
                'images' => [asset('assets/img/products.png')],
                'description' => 'Televisor LED de 55 pulgadas con resolución 4K UHD, HDR10 y Smart TV integrado.',
                'purchase_price' => 1650000.00,
                'sale_price' => 2200000.00,
                'stock' => 8,
                'stock_min' => 1,
                'stock_max' => 15,
                'unit' => UnitOfMeasure::unit->value,
                'width' => 123, // cm
                'height' => 71, // cm
                'weight' => 15000, // grams
                'is_active' => true,
            ],
            [
                'category_id' => 5, // Categoría 05
                'quality_id' => 5, // Calidad 05
                'name' => 'Juego de Llaves Allen 10 piezas',
                'sku' => 'LLA-ALL-10P-005',
                'images' => [asset('assets/img/products.png')],
                'description' => 'Set completo de llaves Allen métricas de 10 piezas, tamaños de 1.5mm a 10mm.',
                'purchase_price' => 18000.00,
                'sale_price' => 32000.00,
                'stock' => 30,
                'stock_min' => 4,
                'stock_max' => 60,
                'unit' => UnitOfMeasure::set->value,
                'width' => 10, // cm
                'height' => 2, // cm
                'weight' => 150, // grams
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);

            Product::create($product);
        }
    }
}
