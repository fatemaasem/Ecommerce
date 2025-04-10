<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image_url'];

    // علاقة مع المنتج (ProductImage -> Product)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor لإرجاع رابط الصورة الكامل
    public function getFullImageUrlAttribute(): string
    {
        return isset($this->attributes['image_url']) 
            ? asset("storage/{$this->attributes['image_url']}") 
            : '';
    }
}
