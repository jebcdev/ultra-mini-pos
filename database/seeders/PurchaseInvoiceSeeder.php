<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Purchase, PurchaseItem, Invoice, InvoiceItem, Product, User, Client, City};

class PurchaseInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();
        $clients = Client::all();
        $city = City::first(); // Asumir que hay al menos una ciudad

        if ($products->isEmpty() || $users->isEmpty() || $clients->isEmpty()) {
            $this->command->error('No hay productos, usuarios o clientes suficientes. Ejecuta los seeders correspondientes primero.');
            return;
        }

        // Crear 20 compras
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'purchase_number' => Purchase::generateUniquePurchaseNumber(),
                'purchase_date' => now()->subDays(rand(1, 365)),
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'notes' => 'Compra generada por seeder',
            ]);

            $subtotal = 0;
            $numItems = rand(1, 5);
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 10);
                $unitPrice = $product->purchase_price ?? rand(10, 100);
                $discount = rand(0, 5);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                ]);

                $totalPrice = ($quantity * $unitPrice) - $discount;
                $subtotal += $totalPrice;

                // Aumentar stock
                $product->increment('stock', $quantity);
            }

            $purchase->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
            ]);
        }

        // Crear 15 ventas
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $client = $clients->random();
            $invoice = Invoice::create([
                'client_id' => $client->id,
                'user_id' => $user->id,
                'invoice_number' => Invoice::generateUniqueInvoiceNumber(),
                'issue_date' => now()->subDays(rand(1, 365)),
                'due_date' => now()->addDays(rand(1, 30)),
                'city_id' => $city->id ?? 1,
                'delivery_address' => 'Dirección de entrega generada por seeder',
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'status' => 'pending',
                'notes' => 'Factura generada por seeder',
            ]);

            $subtotal = 0;
            $numItems = rand(1, 5);
            for ($j = 0; $j < $numItems; $j++) {
                $productsWithStock = $products->where('stock', '>', 0);
                if ($productsWithStock->isEmpty()) {
                    break; // No hay productos con stock
                }
                $product = $productsWithStock->random();
                $quantity = min(rand(1, 5), $product->stock);
                $unitPrice = $product->sale_price ?? rand(20, 200);
                $discount = rand(0, 10);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                ]);

                $totalPrice = ($quantity * $unitPrice) - $discount;
                $subtotal += $totalPrice;

                // Disminuir stock
                $product->decrement('stock', $quantity);
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
            ]);
        }
    }
}
