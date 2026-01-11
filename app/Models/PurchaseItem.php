<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PurchaseItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Variable estática para rastrear items procesados en esta request
     */
    public static $inventoryProcessedInCurrentRequest = [];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Recalcular los totales de la compra
     */
    protected static function recalculatePurchaseTotals($purchaseItem): void
    {
        $purchase = $purchaseItem->purchase;

        // Calcular subtotal (suma de todos los items sin descuentos globales)
        $purchase->subtotal = $purchase->items()->sum(DB::raw('(quantity * unit_price) - discount'));

        // Total amount = subtotal + taxes - descuentos globales (si aplican)
        $purchase->total_amount = $purchase->subtotal + $purchase->tax_amount - $purchase->discount_amount;

        $purchase->saveQuietly();

        \Illuminate\Support\Facades\Log::info("💰 Totales recalculados - Compra ID: {$purchase->id}, Subtotal: {$purchase->subtotal}, Total: {$purchase->total_amount}");
    }
}
