<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'image',
    ];

    // Relación inversa
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Scopes útiles
    public function scopeActiveProducts($query)
    {
        return $query->with(['products' => function ($q) {
            $q->active();
        }]);
    }
}
