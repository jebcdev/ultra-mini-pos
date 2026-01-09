<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\InvoiceStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('client_id')->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');

            // Información de la factura
            $table->string('invoice_number')->unique()->index();
            $table->date('issue_date');
            $table->date('due_date')->nullable();

            // informacion de entrega
            $table->foreignId('city_id')->nullable()->references('id')->on('cities')->onUpdate('set null')->onDelete('set null');
            $table->text('delivery_address')->nullable();

            // Montos financieros
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            // Estado y notas
            $table->enum('status', InvoiceStatus::values())->default(InvoiceStatus::pending->value);
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
