<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offer;
use Carbon\Carbon;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'price', 'stock_quantity', 'category_id','featured','popular','stripe_price_id'
    ];
     /**
     * Append the discounted price to model arrays
     *
     * @var array
     */
    protected $appends = ['discounted_price'];
    // علاقة مع المستخدم (Product -> User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع التصنيف (Product -> Category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // علاقة مع الصور (Product -> ProductImages)
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    //get only featured scope
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
     //get only popular scope
    public function scopePopular($query)
    {
        return $query->where('popular', true);
    }



    // Relationship with offers (for specific_products scope)
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    
    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }    

    public function getDiscountedPriceAttribute(): ?float
    {
        return app('App\Services\OfferService')->calculateDiscountedPrice($this);
    }

    public function carts()
{
    return $this->belongsToMany(Cart::class, 'product_carts')
                ->withPivot('quantity')
                ->withTimestamps();
}
    
   
}
