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
        $user = User::first();
        $clients = Client::with('city')->take(3)->get();
        $products = Product::active()->take(5)->get();

        $invoices = [];
        $items = [];

        // Factura 1
        $invoices[] = [
            'client_id' => $clients->get(0)->id,
            'user_id' => $user->id,
            'invoice_number' => 'INV-2025-001',
            'issue_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(30),
            'city_id' => $clients->get(0)->city_id,
            'delivery_address' => 'Carrera 7 #23-45, Barrio La Candelaria, Bogotá D.C., Colombia',
            'subtotal' => 11683000,
            'tax_amount' => 2219770,
            'discount_amount' => 50000,
            'total_amount' => 13852770,
            'status' => InvoiceStatus::paid,
            'notes' => 'Factura pagada en efectivo. Cliente satisfecho con la entrega.',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $invoice1_items = [
            ['product_id' => $products->get(0)->id, 'quantity' => 2, 'unit_price' => 5800000, 'discount' => 0],
            ['product_id' => $products->get(1)->id, 'quantity' => 1, 'unit_price' => 75000, 'discount' => 5000],
            ['product_id' => $products->get(2)->id, 'quantity' => 3, 'unit_price' => 42000, 'discount' => 0],
        ];

        // Factura 2
        $invoices[] = [
            'client_id' => $clients->get(1)->id ?? $clients->get(0)->id,
            'user_id' => $user->id,
            'invoice_number' => 'INV-2025-002',
            'issue_date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(25),
            'city_id' => ($clients->get(1) ?? $clients->get(0))->city_id,
            'delivery_address' => 'Calle 10 #32-15, El Poblado, Medellín, Antioquia, Colombia',
            'subtotal' => 4535000,
            'tax_amount' => 861650,
            'discount_amount' => 0,
            'total_amount' => 5396650,
            'status' => InvoiceStatus::pending,
            'notes' => 'Factura pendiente de pago. Cliente solicitó entrega express.',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $invoice2_items = [
            ['product_id' => $products->get(1)->id, 'quantity' => 1, 'unit_price' => 75000, 'discount' => 0],
            ['product_id' => $products->get(3)->id, 'quantity' => 2, 'unit_price' => 2200000, 'discount' => 100000],
            ['product_id' => $products->get(4)->id, 'quantity' => 1, 'unit_price' => 32000, 'discount' => 2000],
        ];

        // Factura 3
        $invoices[] = [
            'client_id' => $clients->get(2)->id ?? $clients->get(0)->id,
            'user_id' => $user->id,
            'invoice_number' => 'INV-2025-003',
            'issue_date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->addDays(20),
            'city_id' => ($clients->get(2) ?? $clients->get(0))->city_id,
            'delivery_address' => 'Avenida 6N #23-45, Granada, Cali, Valle del Cauca, Colombia',
            'subtotal' => 9542000,
            'tax_amount' => 1812980,
            'discount_amount' => 150000,
            'total_amount' => 11204980,
            'status' => InvoiceStatus::overdue,
            'notes' => 'Factura vencida. Contactar al cliente para recordatorio de pago.',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $invoice3_items = [
            ['product_id' => $products->get(0)->id, 'quantity' => 1, 'unit_price' => 5800000, 'discount' => 0],
            ['product_id' => $products->get(2)->id, 'quantity' => 4, 'unit_price' => 42000, 'discount' => 10000],
            ['product_id' => $products->get(3)->id, 'quantity' => 1, 'unit_price' => 2200000, 'discount' => 0],
            ['product_id' => $products->get(4)->id, 'quantity' => 2, 'unit_price' => 32000, 'discount' => 5000],
        ];

        // Insertar facturas
        $createdInvoices = Invoice::insertGetId($invoices);

        // Preparar items de todas las facturas
        $allInvoiceIds = Invoice::latest()->take(3)->pluck('id')->reverse();

        $itemIndex = 0;
        foreach ([$invoice1_items, $invoice2_items, $invoice3_items] as $invoiceItems) {
            $invoiceId = $allInvoiceIds->values()[$itemIndex] ?? null;

            if ($invoiceId) {
                foreach ($invoiceItems as $item) {
                    $items[] = [
                        'invoice_id' => $invoiceId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            $itemIndex++;
        }

        // Insertar todos los items de una sola vez
        InvoiceItem::insert($items);
    }
}
