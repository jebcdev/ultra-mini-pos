<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quality;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsUnifiedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================================
        // 1. CREAR CALIDADES (QUALITIES)
        // ============================================================
        $qualities = [
            [
                'name' => 'AA',
                'description' => 'Réplica básica con detalles aproximados. Materiales simples pero aceptables.',
                'slug' => 'aa',
            ],
            [
                'name' => 'AAA',
                'description' => 'Réplica mejorada con detalles más precisos. Materiales de mejor calidad.',
                'slug' => 'aaa',
            ],
            [
                'name' => '1:1',
                'description' => 'Réplica de alta fidelidad casi idéntica al original. Excelentes materiales y acabados.',
                'slug' => '1-1',
            ],
            [
                'name' => '1:1 Superior',
                'description' => 'Réplica premium con materiales de lujo. Casi indistinguible del original.',
                'slug' => '1-1-superior',
            ],
            [
                'name' => 'Tipo Clone',
                'description' => 'Réplica superior con materiales premium. Máxima calidad y precisión en detalles.',
                'slug' => 'tipo-clone',
            ],
        ];

        foreach ($qualities as $quality) {
            Quality::updateOrCreate(
                ['slug' => $quality['slug']],
                [
                    'name' => $quality['name'],
                    'description' => $quality['description'],
                    'image' => '/images/qualities/default.jpg',
                ]
            );
        }

        // ============================================================
        // 2. CREAR CATEGORÍAS (CATEGORIES)
        // ============================================================
        $categories = [
            [
                'name' => 'Bolsos de Mano',
                'description' => 'Clásicos bolsos de mano para llevar en la mano o en el brazo.',
                'slug' => 'bolsos-de-mano',
            ],
            [
                'name' => 'Tote Bags',
                'description' => 'Bolsos grandes y espaciosos ideales para el día a día.',
                'slug' => 'tote-bags',
            ],
            [
                'name' => 'Bolsos Cruzados (Crossbody)',
                'description' => 'Bolsos que se llevan cruzados al cuerpo para máxima comodidad.',
                'slug' => 'bolsos-cruzados',
            ],
            [
                'name' => 'Mochilas (Backpacks)',
                'description' => 'Mochilas elegantes de lujo para viajes y uso diario.',
                'slug' => 'mochilas',
            ],
            [
                'name' => 'Bolsos de Noche (Clutches)',
                'description' => 'Pequeños bolsos de lujo perfectos para eventos nocturnos.',
                'slug' => 'bolsos-noche',
            ],
            [
                'name' => 'Bolsos de Viaje',
                'description' => 'Bolsos grandes y funcionales para viajes y desplazamientos.',
                'slug' => 'bolsos-viaje',
            ],
            [
                'name' => 'Bolsos Bandolera',
                'description' => 'Bolsos que se llevan en bandolera con correa ajustable.',
                'slug' => 'bolsos-bandolera',
            ],
            [
                'name' => 'Bolsos Satchel',
                'description' => 'Bolsos estructurados con asa superior y estilo clásico.',
                'slug' => 'bolsos-satchel',
            ],
            [
                'name' => 'Bolsos Hobo',
                'description' => 'Bolsos de estilo bohemio con forma relajada y suave.',
                'slug' => 'bolsos-hobo',
            ],
            [
                'name' => 'Carteras/Billeteras',
                'description' => 'Carteras y billeteras de diseñador para dinero y documentos.',
                'slug' => 'carteras-billeteras',
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $category) {
            $categoryModels[$category['slug']] = Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'image' => '/images/categories/default.jpg',
                ]
            );
        }

        // ============================================================
        // 3. OBTENER IDS DE CALIDADES
        // ============================================================
        $qualityMap = Quality::pluck('id', 'slug')->toArray();

        // ============================================================
        // 4. DEFINIR DATOS DE PRODUCTOS POR MARCA
        // ============================================================
        $brands = [
            // CHANEL - 5 modelos
            'chanel' => [
                ['model' => 'Classic Flap Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Boy Bag Large', 'category' => 'bolsos-de-mano'],
                ['model' => '19 Bag Medium', 'category' => 'bolsos-bandolera'],
                ['model' => 'Grand Shopping Tote', 'category' => 'tote-bags'],
                ['model' => 'Gabrielle Backpack', 'category' => 'mochilas'],
            ],
            // LOUIS VUITTON - 5 modelos
            'louis-vuitton' => [
                ['model' => 'Speedy 30', 'category' => 'bolsos-de-mano'],
                ['model' => 'Neverfull MM', 'category' => 'tote-bags'],
                ['model' => 'Alma BB', 'category' => 'bolsos-de-mano'],
                ['model' => 'Pochette Métis', 'category' => 'bolsos-bandolera'],
                ['model' => 'Capucines MM', 'category' => 'bolsos-satchel'],
            ],
            // GUCCI - 5 modelos
            'gucci' => [
                ['model' => 'Marmont Matelassé Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Dionysus Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Jackie 1961 Medium', 'category' => 'bolsos-cruzados'],
                ['model' => 'Ophidia GG Medium', 'category' => 'bolsos-cruzados'],
                ['model' => 'Soho Disco Shoulder', 'category' => 'bolsos-bandolera'],
            ],
            // PRADA - 5 modelos
            'prada' => [
                ['model' => 'Galleria Large', 'category' => 'bolsos-de-mano'],
                ['model' => 'Cahier Leather', 'category' => 'bolsos-de-mano'],
                ['model' => 'Re-Edition 2005 Nylon', 'category' => 'tote-bags'],
                ['model' => 'Cleo Leather Shoulder', 'category' => 'bolsos-cruzados'],
                ['model' => 'Double Bag Medium', 'category' => 'bolsos-satchel'],
            ],
            // DIOR - 5 modelos
            'dior' => [
                ['model' => 'Lady Dior Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Saddle Bag Large', 'category' => 'bolsos-bandolera'],
                ['model' => 'Book Tote Embroidered', 'category' => 'tote-bags'],
                ['model' => 'Diorama Flap Bag', 'category' => 'bolsos-cruzados'],
                ['model' => 'Bobby Shoulder', 'category' => 'bolsos-noche'],
            ],
            // FENDI - 5 modelos
            'fendi' => [
                ['model' => 'Baguette Nylon', 'category' => 'bolsos-noche'],
                ['model' => 'Peekaboo X-Lite Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Kan I Leather', 'category' => 'bolsos-cruzados'],
                ['model' => 'First Medium Leather', 'category' => 'bolsos-de-mano'],
                ['model' => 'Mon Trésor Crossbody', 'category' => 'bolsos-cruzados'],
            ],
            // SAINT LAURENT - 5 modelos
            'saint-laurent' => [
                ['model' => 'LouLou Small', 'category' => 'bolsos-noche'],
                ['model' => 'Sac de Jour Medium', 'category' => 'bolsos-satchel'],
                ['model' => 'Kate Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Niki Medium', 'category' => 'bolsos-cruzados'],
                ['model' => 'College Medium', 'category' => 'bolsos-de-mano'],
            ],
            // CELINE - 5 modelos
            'celine' => [
                ['model' => 'Luggage Phantom', 'category' => 'bolsos-viaje'],
                ['model' => 'Belt Bag Waist', 'category' => 'bolsos-bandolera'],
                ['model' => 'Trio Leather', 'category' => 'bolsos-cruzados'],
                ['model' => 'Classic Box Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Tabou Leather', 'category' => 'bolsos-de-mano'],
            ],
            // BALENCIAGA - 5 modelos
            'balenciaga' => [
                ['model' => 'City Bag Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Hourglass Medium', 'category' => 'bolsos-de-mano'],
                ['model' => 'Le Cagole XL', 'category' => 'tote-bags'],
                ['model' => 'Neo Classic M', 'category' => 'bolsos-cruzados'],
                ['model' => 'Crush Chain Bag', 'category' => 'bolsos-noche'],
            ],
            // BOTTEGA VENETA - 5 modelos
            'bottega-veneta' => [
                ['model' => 'Cassette Intrecciato', 'category' => 'bolsos-de-mano'],
                ['model' => 'Jodie Intrecciato', 'category' => 'bolsos-cruzados'],
                ['model' => 'Pouch Leather', 'category' => 'bolsos-noche'],
                ['model' => 'Arco Large', 'category' => 'bolsos-cruzados'],
                ['model' => 'Cabat Tote', 'category' => 'tote-bags'],
            ],
        ];

        // ============================================================
        // 5. CREAR PRODUCTOS
        // ============================================================
        $qualities_list = ['aa', 'aaa', '1-1', '1-1-superior', 'tipo-clone'];
        $qualityIndex = 0;

        foreach ($brands as $brandKey => $models) {
            $brandName = $this->getBrandName($brandKey);
            $basePrice = $this->getBasePriceRange($brandKey);

            foreach ($models as $index => $model) {
                $skuNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                $qualitySlug = $qualities_list[$qualityIndex % 5];
                $qualityId = $qualityMap[$qualitySlug];

                // Obtener rango de precios según calidad
                $priceRange = $this->getPriceRangeByQuality($qualitySlug);

                $purchasePrice = rand($priceRange['purchase_min'], $priceRange['purchase_max']);
                $salePrice = $purchasePrice + rand(50, 400);

                // Obtener dimensiones realistas
                $dimensions = $this->getDimensionsByCategory($model['category']);

                Product::updateOrCreate(
                    ['sku' => strtoupper(substr($brandKey, 0, 3)) . '-' . strtoupper(substr(str_replace(' ', '', $model['model']), 0, 3)) . '-' . $skuNumber],
                    [
                        'name' => $brandName . ' ' . $model['model'],
                        'slug' => Str::slug($brandName . ' ' . $model['model']),
                        'sku' => strtoupper(substr($brandKey, 0, 3)) . '-' . strtoupper(substr(str_replace(' ', '', $model['model']), 0, 3)) . '-' . $skuNumber,
                        'category_id' => $categoryModels[$model['category']]->id,
                        'quality_id' => $qualityId,
                        'description' => $this->generateDescription($brandName, $model['model']),
                        'images' => ['/images/products/default.jpg'],
                        'purchase_price' => $purchasePrice,
                        'sale_price' => $salePrice,
                        'stock' => rand(5, 50),
                        'stock_min' => 3,
                        'stock_max' => 100,
                        'unit' => 'unit',
                        'width' => $dimensions['width'],
                        'height' => $dimensions['height'],
                        'weight' => $dimensions['weight'],
                        'is_active' => true,
                    ]
                );

                $qualityIndex++;
            }
        }
    }

    /**
     * Obtener el nombre completo de la marca
     */
    private function getBrandName(string $brandKey): string
    {
        $brands = [
            'chanel' => 'Chanel',
            'louis-vuitton' => 'Louis Vuitton',
            'gucci' => 'Gucci',
            'prada' => 'Prada',
            'dior' => 'Dior',
            'fendi' => 'Fendi',
            'saint-laurent' => 'Saint Laurent',
            'celine' => 'Celine',
            'balenciaga' => 'Balenciaga',
            'bottega-veneta' => 'Bottega Veneta',
        ];

        return $brands[$brandKey] ?? 'Luxury Brand';
    }

    /**
     * Obtener descripción realista del producto
     */
    private function generateDescription(string $brand, string $model): string
    {
        $descriptions = [
            'Réplica de alta calidad del icónico ' . $brand . ' ' . $model . '. Materiales premium y detalles meticulosos.',
            'Bolso de lujo ' . $brand . ' ' . $model . ' con acabados impecables. Diseño elegante y funcional.',
            'Auténtica réplica del ' . $brand . ' ' . $model . ' con máxima precisión. Perfecto para amantes de la moda.',
            'Bolso de diseñador ' . $brand . ' ' . $model . ' de excelente calidad. Ideal para cualquier ocasión.',
            'Replica premium del famoso ' . $brand . ' ' . $model . '. Durabilidad y estilo garantizados.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Obtener rango de precios base por marca
     */
    private function getBasePriceRange(string $brandKey): array
    {
        $baseRanges = [
            'chanel' => ['min' => 80, 'max' => 300],
            'louis-vuitton' => ['min' => 90, 'max' => 280],
            'gucci' => ['min' => 70, 'max' => 250],
            'prada' => ['min' => 100, 'max' => 320],
            'dior' => ['min' => 110, 'max' => 330],
            'fendi' => ['min' => 85, 'max' => 290],
            'saint-laurent' => ['min' => 95, 'max' => 310],
            'celine' => ['min' => 105, 'max' => 340],
            'balenciaga' => ['min' => 80, 'max' => 270],
            'bottega-veneta' => ['min' => 90, 'max' => 300],
        ];

        return $baseRanges[$brandKey] ?? ['min' => 80, 'max' => 300];
    }

    /**
     * Obtener rango de precios según la calidad
     */
    private function getPriceRangeByQuality(string $quality): array
    {
        $ranges = [
            'aa' => ['purchase_min' => 50, 'purchase_max' => 150],
            'aaa' => ['purchase_min' => 100, 'purchase_max' => 250],
            '1-1' => ['purchase_min' => 150, 'purchase_max' => 350],
            '1-1-superior' => ['purchase_min' => 200, 'purchase_max' => 450],
            'tipo-clone' => ['purchase_min' => 300, 'purchase_max' => 600],
        ];

        return $ranges[$quality] ?? ['purchase_min' => 80, 'purchase_max' => 200];
    }

    /**
     * Obtener dimensiones realistas por categoría
     */
    private function getDimensionsByCategory(string $category): array
    {
        $dimensions = [
            'bolsos-de-mano' => ['width' => 30, 'height' => 20, 'weight' => 800],
            'tote-bags' => ['width' => 40, 'height' => 35, 'weight' => 1200],
            'bolsos-cruzados' => ['width' => 25, 'height' => 18, 'weight' => 600],
            'mochilas' => ['width' => 32, 'height' => 42, 'weight' => 1500],
            'bolsos-noche' => ['width' => 20, 'height' => 12, 'weight' => 400],
            'bolsos-viaje' => ['width' => 50, 'height' => 30, 'weight' => 2000],
            'bolsos-bandolera' => ['width' => 28, 'height' => 22, 'weight' => 700],
            'bolsos-satchel' => ['width' => 35, 'height' => 25, 'weight' => 1000],
            'bolsos-hobo' => ['width' => 38, 'height' => 28, 'weight' => 900],
            'carteras-billeteras' => ['width' => 12, 'height' => 10, 'weight' => 200],
        ];

        return $dimensions[$category] ?? ['width' => 30, 'height' => 20, 'weight' => 800];
    }
}
