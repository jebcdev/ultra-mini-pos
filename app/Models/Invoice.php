<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'user_id',
        'invoice_number',
        'issue_date',
        'due_date',

        'city_id',
        'delivery_address',

        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'status' => 'string',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }


    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Generar un número de factura secuencial con formato FACT-###
     * Donde ### es el total de facturas + 1 con ceros a la izquierda
     */
    public static function generateUniqueInvoiceNumber(): string
    {
        $totalInvoices = self::count();
        $nextNumber = $totalInvoices + 1;
        $invoiceNumber = 'FACT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return $invoiceNumber;
    }
}
