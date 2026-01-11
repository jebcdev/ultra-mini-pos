<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class InvoiceItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        // 'total_price', // Removido porque es una columna generada
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

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Bootstrap the model and its traits.
     */
   /*  protected static function booted(): void
    {
        static::creating(function ($invoiceItem) {
            // Asegurar que quantity y unit_price estén presentes
            if (!$invoiceItem->quantity) {
                $invoiceItem->quantity = 1;
            }
            if (!$invoiceItem->unit_price) {
                $invoiceItem->unit_price = $invoiceItem->product->sale_price;
            }
        });

        static::created(function ($invoiceItem) {
            // Reducir el stock del producto
            $product = $invoiceItem->product;
            $product->stock -= $invoiceItem->quantity;
            $product->saveQuietly();

            \Illuminate\Support\Facades\Log::info("📉 Stock reducido al CREAR InvoiceItem - Producto: {$product->name}, Cantidad: {$invoiceItem->quantity}, Stock actual: {$product->stock}");

            // Recalcular totales de la factura
            self::recalculateInvoiceTotals($invoiceItem);
        });

        static::updating(function ($invoiceItem) {
            // Verificar si realmente hay cambios
            $key = "update_{$invoiceItem->id}";

            // Si ya procesamos este item en esta request, ignorar
            if (isset(self::$inventoryProcessedInCurrentRequest[$key])) {
                \Illuminate\Support\Facades\Log::info("⏭️ InvoiceItem ID: {$invoiceItem->id} YA FUE PROCESADO EN ESTA REQUEST, IGNORANDO");
                return;
            }

            // Si NO hay cambios, salir
            if (!$invoiceItem->isDirty()) {
                \Illuminate\Support\Facades\Log::info("⏭️ InvoiceItem ID: {$invoiceItem->id} NO tiene cambios, IGNORANDO");
                return;
            }

            // Guardar cantidad original SOLO si cambió la cantidad
            if ($invoiceItem->isDirty('quantity')) {
                $invoiceItem->original_quantity = $invoiceItem->getOriginal('quantity');
                \Illuminate\Support\Facades\Log::info("🔄 ACTUALIZANDO InvoiceItem ID: {$invoiceItem->id}, Cantidad original: {$invoiceItem->original_quantity}, Nueva cantidad: {$invoiceItem->quantity}");

                // Marcar que estamos procesando este item
                self::$inventoryProcessedInCurrentRequest[$key] = true;
            }
        });

        static::updated(function ($invoiceItem) {
            // Ajustar inventario SOLO si cambió la cantidad
            if ($invoiceItem->wasChanged('quantity')) {
                $product = $invoiceItem->product;
                $oldQuantity = $invoiceItem->original_quantity ?? $invoiceItem->getOriginal('quantity');
                $newQuantity = $invoiceItem->quantity;
                $difference = $newQuantity - $oldQuantity;

                // Solo ajustar si hay diferencia real
                if ($difference != 0) {
                    $product->stock -= $difference;
                    $product->saveQuietly();

                    \Illuminate\Support\Facades\Log::info("🔄 Stock ajustado al ACTUALIZAR - Producto: {$product->name}, Cantidad antigua: {$oldQuantity}, Cantidad nueva: {$newQuantity}, Diferencia: {$difference}, Stock actual: {$product->stock}");
                }
            }

            // Recalcular totales SOLO si hubo cambios relevantes
            if ($invoiceItem->wasChanged(['quantity', 'unit_price', 'discount', 'product_id'])) {
                self::recalculateInvoiceTotals($invoiceItem);
            }
        });

        static::deleting(function ($invoiceItem) {
            // Guardar cantidad antes de eliminar
            $invoiceItem->deleted_quantity = $invoiceItem->quantity;

            \Illuminate\Support\Facades\Log::info("🗑️ ELIMINANDO InvoiceItem ID: {$invoiceItem->id}, Cantidad a devolver: {$invoiceItem->deleted_quantity}");
        });

        static::deleted(function ($invoiceItem) {
            // Devolver el stock del producto
            $product = $invoiceItem->product;
            $product->stock += $invoiceItem->deleted_quantity;
            $product->saveQuietly();

            \Illuminate\Support\Facades\Log::info("📈 Stock devuelto al ELIMINAR - Producto: {$product->name}, Cantidad devuelta: {$invoiceItem->deleted_quantity}, Stock actual: {$product->stock}");

            // Recalcular totales de la factura
            self::recalculateInvoiceTotals($invoiceItem);
        });
    } */

    /**
     * Recalcular los totales de la factura
     */
    protected static function recalculateInvoiceTotals($invoiceItem): void
    {
        $invoice = $invoiceItem->invoice;

        // Calcular subtotal (suma de todos los items sin descuentos globales)
        $invoice->subtotal = $invoice->items()->sum(DB::raw('(quantity * unit_price) - discount'));

        // Por ahora, asumir que tax_amount se calcula como porcentaje (ajustar según lógica de negocio)
        // $invoice->tax_amount = $invoice->subtotal * 0.16; // Ejemplo: 16% IVA

        // Total amount = subtotal + taxes - descuentos globales (si aplican)
        $invoice->total_amount = $invoice->subtotal + $invoice->tax_amount - $invoice->discount_amount;

        $invoice->saveQuietly();

        \Illuminate\Support\Facades\Log::info("💰 Totales recalculados - Factura ID: {$invoice->id}, Subtotal: {$invoice->subtotal}, Total: {$invoice->total_amount}");
    }
}
