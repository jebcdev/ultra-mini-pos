<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'quality_id',
        'name',
        'slug',
        'sku',
        'images',
        'description',
        'purchase_price',
        'sale_price',
        'stock',
        'stock_min',
        'stock_max',
        'unit',
        'width',
        'height',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'stock_min' => 'integer',
        'stock_max' => 'integer',
        'unit' => 'string',
        'width' => 'integer',
        'height' => 'integer',
        'weight' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quality(): BelongsTo
    {
        return $this->belongsTo(Quality::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Scopes útiles
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByQuality($query, $qualityId)
    {
        return $query->where('quality_id', $qualityId);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_min');
    }

    public function scopeHighStock($query)
    {
        return $query->whereColumn('stock', '>=', 'stock_max');
    }

    // Accessors/Mutators si es necesario
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->sale_price, 2);
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->stock_min;
    }
}
