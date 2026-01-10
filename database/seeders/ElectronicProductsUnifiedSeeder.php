<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quality;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ElectronicProductsUnifiedSeeder extends Seeder
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
                'name' => 'Celulares',
                'description' => 'Teléfonos inteligentes y móviles de última generación.',
                'slug' => 'celulares',
            ],
            [
                'name' => 'Laptops',
                'description' => 'Computadoras portátiles para trabajo y entretenimiento.',
                'slug' => 'laptops',
            ],
            [
                'name' => 'Computadores de Escritorio',
                'description' => 'PC de escritorio para gaming y productividad.',
                'slug' => 'computadores-escritorio',
            ],
            [
                'name' => 'Tablets',
                'description' => 'Tablets y dispositivos táctiles para movilidad.',
                'slug' => 'tablets',
            ],
            [
                'name' => 'Audífonos',
                'description' => 'Audífonos inalámbricos y con cable para música y llamadas.',
                'slug' => 'audifonos',
            ],
            [
                'name' => 'Diademas',
                'description' => 'Diademas gaming y profesionales para audio inmersivo.',
                'slug' => 'diademas',
            ],
            [
                'name' => 'Micrófonos',
                'description' => 'Micrófonos USB y condensadores para streaming y grabación.',
                'slug' => 'microfonos',
            ],
            [
                'name' => 'Monitores',
                'description' => 'Monitores LED y gaming de alta resolución.',
                'slug' => 'monitores',
            ],
            [
                'name' => 'Teclados',
                'description' => 'Teclados mecánicos y gaming para productividad.',
                'slug' => 'teclados',
            ],
            [
                'name' => 'Mouse',
                'description' => 'Mouse inalámbricos y gaming de precisión.',
                'slug' => 'mouse',
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
            // APPLE - 5 modelos
            'apple' => [
                ['model' => 'iPhone 15 Pro Max', 'category' => 'celulares'],
                ['model' => 'MacBook Pro 16"', 'category' => 'laptops'],
                ['model' => 'iPad Pro 12.9"', 'category' => 'tablets'],
                ['model' => 'AirPods Pro', 'category' => 'audifonos'],
                ['model' => 'iMac 24"', 'category' => 'computadores-escritorio'],
            ],
            // SAMSUNG - 5 modelos
            'samsung' => [
                ['model' => 'Galaxy S24 Ultra', 'category' => 'celulares'],
                ['model' => 'Galaxy Book3 Pro', 'category' => 'laptops'],
                ['model' => 'Galaxy Tab S9', 'category' => 'tablets'],
                ['model' => 'Galaxy Buds2 Pro', 'category' => 'audifonos'],
                ['model' => 'Odyssey G9', 'category' => 'monitores'],
            ],
            // SONY - 5 modelos
            'sony' => [
                ['model' => 'Xperia 1 V', 'category' => 'celulares'],
                ['model' => 'WH-1000XM5', 'category' => 'audifonos'],
                ['model' => 'WF-1000XM5', 'category' => 'audifonos'],
                ['model' => 'INZONE H9', 'category' => 'diademas'],
                ['model' => 'INZONE H3', 'category' => 'diademas'],
            ],
            // LOGITECH - 5 modelos
            'logitech' => [
                ['model' => 'MX Master 3S', 'category' => 'mouse'],
                ['model' => 'MX Keys', 'category' => 'teclados'],
                ['model' => 'G915 TKL', 'category' => 'teclados'],
                ['model' => 'G Pro X Superlight', 'category' => 'mouse'],
                ['model' => 'Blue Yeti USB', 'category' => 'microfonos'],
            ],
            // ASUS - 5 modelos
            'asus' => [
                ['model' => 'ROG Strix G15', 'category' => 'laptops'],
                ['model' => 'TUF Gaming F15', 'category' => 'laptops'],
                ['model' => 'ROG Strix Scar 18', 'category' => 'laptops'],
                ['model' => 'TUF Gaming VG289Q1A', 'category' => 'monitores'],
                ['model' => 'ROG Strix Scope NX', 'category' => 'teclados'],
            ],
            // DELL - 5 modelos
            'dell' => [
                ['model' => 'XPS 13', 'category' => 'laptops'],
                ['model' => 'XPS 15', 'category' => 'laptops'],
                ['model' => 'Alienware Aurora R16', 'category' => 'computadores-escritorio'],
                ['model' => 'UltraSharp U2723QE', 'category' => 'monitores'],
                ['model' => 'Dell Pro Wireless Keyboard', 'category' => 'teclados'],
            ],
            // HP - 5 modelos
            'hp' => [
                ['model' => 'Spectre x360 14', 'category' => 'laptops'],
                ['model' => 'Envy 16', 'category' => 'laptops'],
                ['model' => 'Omen 45L', 'category' => 'computadores-escritorio'],
                ['model' => 'Pavilion 27', 'category' => 'monitores'],
                ['model' => 'HyperX Cloud II', 'category' => 'diademas'],
            ],
            // MICROSOFT - 5 modelos
            'microsoft' => [
                ['model' => 'Surface Pro 9', 'category' => 'tablets'],
                ['model' => 'Surface Laptop 5', 'category' => 'laptops'],
                ['model' => 'Surface Studio 2+', 'category' => 'computadores-escritorio'],
                ['model' => 'Xbox Wireless Headset', 'category' => 'diademas'],
                ['model' => 'Surface Arc Mouse', 'category' => 'mouse'],
            ],
            // RAZER - 5 modelos
            'razer' => [
                ['model' => 'DeathAdder V3 Pro', 'category' => 'mouse'],
                ['model' => 'BlackWidow V4 Pro', 'category' => 'teclados'],
                ['model' => 'Kraken V3 Pro', 'category' => 'diademas'],
                ['model' => 'Barracuda X', 'category' => 'diademas'],
                ['model' => 'Seiren V2 Pro', 'category' => 'microfonos'],
            ],
            // AUDIO-TECHNICA - 5 modelos
            'audio-technica' => [
                ['model' => 'AT2020', 'category' => 'microfonos'],
                ['model' => 'AT2020USB-X', 'category' => 'microfonos'],
                ['model' => 'ATH-S200BT', 'category' => 'audifonos'],
                ['model' => 'ATH-S300BT', 'category' => 'audifonos'],
                ['model' => 'AT-LP60X', 'category' => 'audifonos'],
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
                        'stock' => 0,
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
            'apple' => 'Apple',
            'samsung' => 'Samsung',
            'sony' => 'Sony',
            'logitech' => 'Logitech',
            'asus' => 'ASUS',
            'dell' => 'Dell',
            'hp' => 'HP',
            'microsoft' => 'Microsoft',
            'razer' => 'Razer',
            'audio-technica' => 'Audio-Technica',
        ];

        return $brands[$brandKey] ?? 'Electronic Brand';
    }

    /**
     * Obtener descripción realista del producto
     */
    private function generateDescription(string $brand, string $model): string
    {
        $descriptions = [
            'Producto electrónico de alta calidad ' . $brand . ' ' . $model . '. Tecnología avanzada y diseño premium.',
            'Dispositivo ' . $brand . ' ' . $model . ' con características innovadoras. Ideal para usuarios exigentes.',
            'Réplica premium del ' . $brand . ' ' . $model . ' con máxima fidelidad. Rendimiento excepcional.',
            'Tecnología de vanguardia en el ' . $brand . ' ' . $model . '. Durabilidad y funcionalidad garantizadas.',
            'Producto ' . $brand . ' ' . $model . ' de excelente calidad. Perfecto para gaming y productividad.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Obtener rango de precios base por marca (en pesos colombianos)
     */
    private function getBasePriceRange(string $brandKey): array
    {
        $baseRanges = [
            'apple' => ['min' => 2000000, 'max' => 8000000], // iPhones, Macs caros
            'samsung' => ['min' => 1000000, 'max' => 6000000], // Galaxy series
            'sony' => ['min' => 500000, 'max' => 3000000], // Audífonos y gaming
            'logitech' => ['min' => 200000, 'max' => 1500000], // Periféricos
            'asus' => ['min' => 1500000, 'max' => 7000000], // Laptops gaming
            'dell' => ['min' => 1200000, 'max' => 6000000], // XPS y Alienware
            'hp' => ['min' => 1000000, 'max' => 5000000], // Spectre y Omen
            'microsoft' => ['min' => 1500000, 'max' => 8000000], // Surface
            'razer' => ['min' => 300000, 'max' => 2000000], // Gaming peripherals
            'audio-technica' => ['min' => 400000, 'max' => 2000000], // Audio equipment
        ];

        return $baseRanges[$brandKey] ?? ['min' => 500000, 'max' => 3000000];
    }

    /**
     * Obtener rango de precios según la calidad (en pesos colombianos)
     */
    private function getPriceRangeByQuality(string $quality): array
    {
        $ranges = [
            'aa' => ['purchase_min' => 300000, 'purchase_max' => 1500000],
            'aaa' => ['purchase_min' => 800000, 'purchase_max' => 3000000],
            '1-1' => ['purchase_min' => 1500000, 'purchase_max' => 5000000],
            '1-1-superior' => ['purchase_min' => 2500000, 'purchase_max' => 8000000],
            'tipo-clone' => ['purchase_min' => 4000000, 'purchase_max' => 12000000],
        ];

        return $ranges[$quality] ?? ['purchase_min' => 500000, 'purchase_max' => 3000000];
    }

    /**
     * Obtener dimensiones realistas por categoría
     */
    private function getDimensionsByCategory(string $category): array
    {
        $dimensions = [
            'celulares' => ['width' => 7, 'height' => 15, 'weight' => 200], // cm, gramos
            'laptops' => ['width' => 35, 'height' => 25, 'weight' => 1500],
            'computadores-escritorio' => ['width' => 20, 'height' => 40, 'weight' => 8000], // Torre
            'tablets' => ['width' => 25, 'height' => 17, 'weight' => 500],
            'audifonos' => ['width' => 20, 'height' => 18, 'weight' => 300],
            'diademas' => ['width' => 20, 'height' => 10, 'weight' => 400],
            'microfonos' => ['width' => 15, 'height' => 25, 'weight' => 600],
            'monitores' => ['width' => 60, 'height' => 35, 'weight' => 5000],
            'teclados' => ['width' => 45, 'height' => 15, 'weight' => 800],
            'mouse' => ['width' => 7, 'height' => 12, 'weight' => 100],
        ];

        return $dimensions[$category] ?? ['width' => 20, 'height' => 15, 'weight' => 500];
    }
}
