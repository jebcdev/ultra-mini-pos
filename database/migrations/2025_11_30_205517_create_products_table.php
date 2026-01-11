<?php

use App\Enums\{UnitOfMeasure};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->onUpdate('set null')->onDelete('set null');
            $table->foreignId('quality_id')->nullable()->references('id')->on('qualities')->onUpdate('set null')->onDelete('set null');

            $table->string('name')->unique()->index();
            $table->string('slug')->unique()->index();
            $table->string('sku')->unique()->index();

            $table->json('images')->nullable()->default(json_encode([asset('assets/img/products.png')]));

            $table->text('description')->nullable();

            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);

            $table->unsignedBigInteger('stock')->default(0);
            $table->unsignedBigInteger('stock_min')->default(0);
            $table->unsignedBigInteger('stock_max')->default(0);

            $table->enum('unit', UnitOfMeasure::values())->default(UnitOfMeasure::unit->value);


            $table->unsignedBigInteger('width')->default(0); // in cm
            $table->unsignedBigInteger('height')->default(0); // in cm
            $table->unsignedBigInteger('weight')->default(0); // in grams


            $table->boolean('is_active')->default(true);


            // Índices adicionales para optimizar consultas comunes
            $table->index(['category_id', 'is_active']); // Para filtrar productos activos por categoría
            $table->index(['quality_id', 'is_active']); // Para filtrar productos activos por calidad
            $table->index('is_active'); // Para filtrar productos activos/inactivos
            $table->index('stock'); // Para consultas de stock bajo
            $table->index('sale_price'); // Para ordenar por precio de venta
            $table->index(['stock', 'is_active']); // Para alertas de stock en productos activos


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
