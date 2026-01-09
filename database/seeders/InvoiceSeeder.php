<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use App\Models\City;
use App\Enums\InvoiceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos existentes para referencias
        $user = User::first(); // Asumimos que hay al menos un usuario
        $clients = Client::with('city')->take(3)->get();
        $products = Product::active()->take(5)->get();

        // Factura 1: Cliente en Bogotá
        $client1 = $clients->first();
        $invoice1 = Invoice::create([
            'client_id' => $client1->id,
            'user_id' => $user->id,
            'invoice_number' => 'INV-2025-001',
            'issue_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(30),
            'city_id' => $client1->city_id,
            'delivery_address' => 'Carrera 7 #23-45, Barrio La Candelaria, Bogotá D.C., Colombia',
            'subtotal' => 0, // Se calculará después
            'tax_amount' => 0,
            'discount_amount' => 50000,
            'total_amount' => 0,
            'status' => InvoiceStatus::paid,
            'notes' => 'Factura pagada en efectivo. Cliente satisfecho con la entrega.',
        ]);

        // Items para factura 1
        $items1 = [
            ['product' => $products->get(0), 'quantity' => 2, 'unit_price' => 5800000, 'discount' => 0],
            ['product' => $products->get(1), 'quantity' => 1, 'unit_price' => 75000, 'discount' => 5000],
            ['product' => $products->get(2), 'quantity' => 3, 'unit_price' => 42000, 'discount' => 0],
        ];

        $subtotal1 = 0;
        foreach ($items1 as $itemData) {
            $totalPrice = ($itemData['quantity'] * $itemData['unit_price']) - $itemData['discount'];
            InvoiceItem::create([
                'invoice_id' => $invoice1->id,
                'product_id' => $itemData['product']->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'discount' => $itemData['discount'],
                // 'total_price' => $totalPrice, // Removido porque es columna generada
            ]);
            $subtotal1 += $totalPrice;
        }

        // Calcular totales
        $tax1 = $subtotal1 * 0.19; // IVA 19%
        $total1 = $subtotal1 + $tax1 - $invoice1->discount_amount;
        $invoice1->update([
            'subtotal' => $subtotal1,
            'tax_amount' => $tax1,
            'total_amount' => $total1,
        ]);

        // Factura 2: Cliente en Medellín
        $client2 = $clients->skip(1)->first() ?? $client1;
        $invoice2 = Invoice::create([
            'client_id' => $client2->id,
            'user_id' => $user->id,
            'invoice_number' => 'INV-2025-002',
            'issue_date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(25),
            'city_id' => $client2->city_id,
            'delivery_address' => 'Calle 10 #32-15, El Poblado, Medellín, Antioquia, Colombia',
            'subtotal' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 0,
            'status' => InvoiceStatus::pending,
            'notes' => 'Factura pendiente de pago. Cliente solicitó entrega express.',
        ]);

        // Items para factura 2
        $items2 = [
            ['product' => $products->get(1), 'quantity' => 1, 'unit_price' => 75000, 'discount' => 0],
            ['product' => $products->get(3), 'quantity' => 2, 'unit_price' => 2200000, 'discount' => 100000],
            ['product' => $products->get(4), 'quantity' => 1, 'unit_price' => 32000, 'discount' => 2000],
        ];

        $subtotal2 = 0;
        foreach ($items2 as $itemData) {
            $totalPrice = ($itemData['quantity'] * $itemData['unit_price']) - $itemData['discount'];
            InvoiceItem::create([
                'invoice_id' => $invoice2->id,
                'product_id' => $itemData['product']->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'discount' => $itemData['discount'],
                // 'total_price' => $totalPrice, // Removido porque es columna generada
            ]);
            $subtotal2 += $totalPrice;
        }

        $tax2 = $subtotal2 * 0.19;
        $total2 = $subtotal2 + $tax2 - $invoice2->discount_amount;
        $invoice2->update([
            'subtotal' => $subtotal2,
            'tax_amount' => $tax2,
            'total_amount' => $total2,
        ]);

        // Factura 3: Cliente en Cali
        $client3 = $clients->skip(2)->first() ?? $client1;
        $invoice3 = Invoice::create([
            'client_id' => $client3->id,
            'user_id' => $user->id,
            'invoice_number' => 'INV-2025-003',
            'issue_date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->addDays(20),
            'city_id' => $client3->city_id,
            'delivery_address' => 'Avenida 6N #23-45, Granada, Cali, Valle del Cauca, Colombia',
            'subtotal' => 0,
            'tax_amount' => 0,
            'discount_amount' => 150000,
            'total_amount' => 0,
            'status' => InvoiceStatus::overdue,
            'notes' => 'Factura vencida. Contactar al cliente para recordatorio de pago.',
        ]);

        // Items para factura 3
        $items3 = [
            ['product' => $products->get(0), 'quantity' => 1, 'unit_price' => 5800000, 'discount' => 0],
            ['product' => $products->get(2), 'quantity' => 4, 'unit_price' => 42000, 'discount' => 10000],
            ['product' => $products->get(3), 'quantity' => 1, 'unit_price' => 2200000, 'discount' => 0],
            ['product' => $products->get(4), 'quantity' => 2, 'unit_price' => 32000, 'discount' => 5000],
        ];

        $subtotal3 = 0;
        foreach ($items3 as $itemData) {
            $totalPrice = ($itemData['quantity'] * $itemData['unit_price']) - $itemData['discount'];
            InvoiceItem::create([
                'invoice_id' => $invoice3->id,
                'product_id' => $itemData['product']->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'discount' => $itemData['discount'],
                // 'total_price' => $totalPrice, // Removido porque es columna generada
            ]);
            $subtotal3 += $totalPrice;
        }

        $tax3 = $subtotal3 * 0.19;
        $total3 = $subtotal3 + $tax3 - $invoice3->discount_amount;
        $invoice3->update([
            'subtotal' => $subtotal3,
            'tax_amount' => $tax3,
            'total_amount' => $total3,
        ]);
    }
}
