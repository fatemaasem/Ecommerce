<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
       'id', 'title', 'description', 'discount_type', 'discount_value', 'applies_to', 'category_id', 'start_date', 'end_date'
    ];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with Products (for specific_products scope)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product');
    }

     
          // Method to get affected products based on applies_to
          public function getAffectedProducts()
          {
              $products = collect();
          
              if ($this->applies_to === 'all') {
                  $products = Product::all();
              } elseif ($this->applies_to === 'category') {
                  $products = Product::where('category_id', $this->category_id)->get();
              } elseif ($this->applies_to === 'specific_products') {
                  $products = $this->products;
              }
          
              // Filter out products that are assigned to newer offers
              return $products->filter(function ($product) {
                  $activeOffer = app('App\Services\OfferService')->getActiveOffer($product);
                  
                  // Include product if:
                  // 1. No active offer exists, OR
                  // 2. The active offer is this one
                  return !$activeOffer || $activeOffer->id === $this->id;
              });
          }
    
}
