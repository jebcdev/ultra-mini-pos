<?php

namespace App\Observers;

use App\Models\InvoiceItem;

class InvoiceItemObserver
{
    /**
     * Handle the InvoiceItem "creating" event.
     */
    public function creating(InvoiceItem $invoiceItem): void
    {
        // Asegurar que quantity y unit_price estén presentes
        if (!$invoiceItem->quantity) {
            $invoiceItem->quantity = 1;
        }
        if (!$invoiceItem->unit_price) {
            $invoiceItem->unit_price = $invoiceItem->product->sale_price;
        }
    }

    /**
     * Handle the InvoiceItem "created" event.
     */
    public function created(InvoiceItem $invoiceItem): void {
        // Reducir el stock del producto
            $product = $invoiceItem->product;
            $product->stock -= $invoiceItem->quantity;
            $product->saveQuietly();

            \Illuminate\Support\Facades\Log::info("📉 Stock reducido al CREAR InvoiceItem - Producto: {$product->name}, Cantidad: {$invoiceItem->quantity}, Stock actual: {$product->stock}");

            // Recalcular totales de la factura
            $invoiceItem::recalculateInvoiceTotals($invoiceItem);
    }

    /**
     * Handle the InvoiceItem "updating" event.
     */
    public function updating(InvoiceItem $invoiceItem): void
    {
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
                $invoiceItem::$inventoryProcessedInCurrentRequest[$key] = true;
            }
    }

    /**
     * Handle the InvoiceItem "updated" event.
     */
    public function updated(InvoiceItem $invoiceItem): void
    {
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
                $invoiceItem::recalculateInvoiceTotals($invoiceItem);
            }
    }

    /**
     * Handle the InvoiceItem "deleting" event.
     */
    public function deleting(InvoiceItem $invoiceItem): void
    {
        // Guardar cantidad antes de eliminar
            $invoiceItem->deleted_quantity = $invoiceItem->quantity;

            \Illuminate\Support\Facades\Log::info("🗑️ ELIMINANDO InvoiceItem ID: {$invoiceItem->id}, Cantidad a devolver: {$invoiceItem->deleted_quantity}");
    }

    /**
     * Handle the InvoiceItem "deleted" event.
     */
    public function deleted(InvoiceItem $invoiceItem): void
    {
        // Devolver el stock del producto
            $product = $invoiceItem->product;
            $product->stock += $invoiceItem->deleted_quantity;
            $product->saveQuietly();

            \Illuminate\Support\Facades\Log::info("📈 Stock devuelto al ELIMINAR - Producto: {$product->name}, Cantidad devuelta: {$invoiceItem->deleted_quantity}, Stock actual: {$product->stock}");

            // Recalcular totales de la factura
            $invoiceItem::recalculateInvoiceTotals($invoiceItem);
    }

    /**
     * Handle the InvoiceItem "restored" event.
     */
    public function restored(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "force deleted" event.
     */
    public function forceDeleted(InvoiceItem $invoiceItem): void
    {
        //
    }
}
