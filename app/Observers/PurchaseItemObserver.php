<?php

namespace App\Observers;

use App\Models\PurchaseItem;

class PurchaseItemObserver
{
    /**
     * Handle the PurchaseItem "creating" event.
     */
    public function creating(PurchaseItem $purchaseItem): void
    {
        // Asegurar que quantity y unit_price estén presentes
        if (!$purchaseItem->quantity) {
            $purchaseItem->quantity = 1;
        }
        if (!$purchaseItem->unit_price) {
            $purchaseItem->unit_price = $purchaseItem->product->cost_price ?? 0;
        }
    }

    /**
     * Handle the PurchaseItem "created" event.
     */
    public function created(PurchaseItem $purchaseItem): void
    {
        // AUMENTAR el stock del producto (entrada de inventario)
        $product = $purchaseItem->product;
        $product->stock += $purchaseItem->quantity;
        $product->saveQuietly();

        \Illuminate\Support\Facades\Log::info("📈 Stock aumentado al CREAR PurchaseItem - Producto: {$product->name}, Cantidad: {$purchaseItem->quantity}, Stock actual: {$product->stock}");

        // Recalcular totales de la compra
        $purchaseItem::recalculatePurchaseTotals($purchaseItem);
    }

    /**
     * Handle the PurchaseItem "updating" event.
     */
    public function updating(PurchaseItem $purchaseItem): void
    {
        // Verificar si realmente hay cambios
        $key = "update_{$purchaseItem->id}";

        // Si ya procesamos este item en esta request, ignorar
        if (isset(self::$inventoryProcessedInCurrentRequest[$key])) {
            \Illuminate\Support\Facades\Log::info("⏭️ PurchaseItem ID: {$purchaseItem->id} YA FUE PROCESADO EN ESTA REQUEST, IGNORANDO");
            return;
        }

        // Si NO hay cambios, salir
        if (!$purchaseItem->isDirty()) {
            \Illuminate\Support\Facades\Log::info("⏭️ PurchaseItem ID: {$purchaseItem->id} NO tiene cambios, IGNORANDO");
            return;
        }

        // Guardar cantidad original SOLO si cambió la cantidad
        if ($purchaseItem->isDirty('quantity')) {
            $purchaseItem->original_quantity = $purchaseItem->getOriginal('quantity');
            \Illuminate\Support\Facades\Log::info("🔄 ACTUALIZANDO PurchaseItem ID: {$purchaseItem->id}, Cantidad original: {$purchaseItem->original_quantity}, Nueva cantidad: {$purchaseItem->quantity}");

            // Marcar que estamos procesando este item
            $purchaseItem::$inventoryProcessedInCurrentRequest[$key] = true;
        }
    }

    /**
     * Handle the PurchaseItem "updated" event.
     */
    public function updated(PurchaseItem $purchaseItem): void
    {
        // Ajustar inventario SOLO si cambió la cantidad
        if ($purchaseItem->wasChanged('quantity')) {
            $product = $purchaseItem->product;
            $oldQuantity = $purchaseItem->original_quantity ?? $purchaseItem->getOriginal('quantity');
            $newQuantity = $purchaseItem->quantity;
            $difference = $newQuantity - $oldQuantity;

            // Solo ajustar si hay diferencia real
            if ($difference != 0) {
                $product->stock += $difference;
                $product->saveQuietly();

                \Illuminate\Support\Facades\Log::info("🔄 Stock ajustado al ACTUALIZAR - Producto: {$product->name}, Cantidad antigua: {$oldQuantity}, Cantidad nueva: {$newQuantity}, Diferencia: {$difference}, Stock actual: {$product->stock}");
            }
        }

        // Recalcular totales SOLO si hubo cambios relevantes
        if ($purchaseItem->wasChanged(['quantity', 'unit_price', 'discount', 'product_id'])) {
            $purchaseItem::recalculatePurchaseTotals($purchaseItem);
        }
    }

    /**
     * Handle the PurchaseItem "deleting" event.
     */
    public function deleting(PurchaseItem $purchaseItem): void
    {
        // Guardar cantidad antes de eliminar
        $purchaseItem->deleted_quantity = $purchaseItem->quantity;

        \Illuminate\Support\Facades\Log::info("🗑️ ELIMINANDO PurchaseItem ID: {$purchaseItem->id}, Cantidad a revertir: {$purchaseItem->deleted_quantity}");
    }

    /**
     * Handle the PurchaseItem "deleted" event.
     */
    public function deleted(PurchaseItem $purchaseItem): void
    {
        // Revertir el stock del producto (reducir porque estamos cancelando la entrada)
        $product = $purchaseItem->product;
        $product->stock -= $purchaseItem->deleted_quantity;
        $product->saveQuietly();

        \Illuminate\Support\Facades\Log::info("📉 Stock revertido al ELIMINAR - Producto: {$product->name}, Cantidad revertida: {$purchaseItem->deleted_quantity}, Stock actual: {$product->stock}");

        // Recalcular totales de la compra
        $purchaseItem::recalculatePurchaseTotals($purchaseItem);
    }

    /**
     * Handle the PurchaseItem "restored" event.
     */
    public function restored(PurchaseItem $purchaseItem): void
    {
        //
    }

    /**
     * Handle the PurchaseItem "force deleted" event.
     */
    public function forceDeleted(PurchaseItem $purchaseItem): void
    {
        //
    }
}
