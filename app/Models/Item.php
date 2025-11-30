<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'is_active',
        'is_featured',
        'image',
        'images',
        'category_id',
        'weight',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
        'weight' => 'decimal:2',
        'attributes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function shoppingCarts()
    {
        return $this->hasMany(ShoppingCart::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format((float) $this->price, 2);
    }

    public function getFormattedSalePriceAttribute()
    {
        return $this->sale_price ? '$' . number_format((float) $this->sale_price, 2) : null;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->is_on_sale ? $this->sale_price : $this->price;
    }

    public function getFormattedCurrentPriceAttribute()
    {
        return '$' . number_format((float) $this->current_price, 2);
    }
}
