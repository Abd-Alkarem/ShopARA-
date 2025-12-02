<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'stock',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLaptops($query)
    {
        return $query->where('category', 'laptop');
    }

    public function scopePhones($query)
    {
        return $query->where('category', 'phone');
    }

    public function getCategoryLabelAttribute()
    {
        return ucfirst($this->category);
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format((float) $this->price, 2);
    }
}
