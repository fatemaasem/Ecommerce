<?php 

namespace App\Services;

use App\DTOs\OfferDTO;
use App\Exceptions\CustomExceptions;
use App\Interfaces\OfferRepositoryInterface;
use App\Mappers\OfferMapper;
use App\Models\Offer;
use App\Models\Product;
use Carbon\Carbon;

class OfferService
{
    public function __construct(
        private OfferRepositoryInterface $offerRepository
    ) {}
    // Get active offers for a product
    public function getActiveOffer(Product $product)
    {
        return Offer::where(function ($query) use ($product) {
                // Offers that apply to all products
                $query->where('applies_to', 'all')
                        ->where('start_date', '<=', Carbon::now())
                        ->where('end_date', '>=', Carbon::now());
            })
            ->orWhere(function ($query) use ($product) {
                // Offers that apply to the product's category
                $query->where('applies_to', 'category')
                        ->where('category_id', $product->category_id)
                        ->where('start_date', '<=', Carbon::now())
                        ->where('end_date', '>=', Carbon::now());
            })
            ->orWhere(function ($query) use ($product) {
                // Offers that apply specifically to this product
                $query->where('applies_to', 'specific_products')
                        ->whereHas('products', function ($query) use ($product) {
                            $query->where('product_id', $product->id);
                        })
                        ->where('start_date', '<=', Carbon::now())
                        ->where('end_date', '>=', Carbon::now());
            })
            ->orderBy('created_at', 'desc') // Get the latest offer first
            ->first(); // Get just the first (most recent) one
    }
    // Calculate the discounted price for a product
    public function calculateDiscountedPrice(Product $product): float
    {
        $discountedPrice = $product->price; // Default to original price

        // Get active offers for this product
        $activeOffer = $this->getActiveOffer($product);

        // Apply each offer to the product
        if($activeOffer){
            $discountedPrice = $this->applyOfferDiscount($activeOffer, $discountedPrice);
        }
        
        
        return $discountedPrice;
    }

    // Apply a single offer's discount to the price
    private function applyOfferDiscount(Offer $offer, float $currentPrice): float
    {
        if ($offer->discount_type === 'percentage') {
            return $currentPrice * (1 - ($offer->discount_value / 100));
        } elseif ($offer->discount_type === 'fixed') {
            return max(0, $currentPrice - $offer->discount_value); // Ensure price doesn't go negative
        }

        return $currentPrice; // Fallback
    }

    public function getAllOffers(): array
    {
        $offers = $this->offerRepository->all();
        
        return OfferMapper::toDTOCollection($offers);
    }

    public function getOfferById(int $id): array
    {
        $offer = $this->offerRepository->find($id);
        
        if(!$offer){
            throw  CustomExceptions::notFoundError("Offer with ID { $id} not found.");
        }
        
        return OfferMapper::toResponse($offer);
    }

    public function createOffer(OfferDTO $offerDTO): array
    {
        $data = [
            'title' => $offerDTO->title,
            'description' => $offerDTO->description,
            'discount_type' => $offerDTO->discount_type,
            'discount_value' => $offerDTO->discount_value,
            'applies_to' => $offerDTO->applies_to,
            'category_id' => $offerDTO->category_id,
            'start_date' => $offerDTO->start_date,
            'end_date' => $offerDTO->end_date,
            'product_ids' => $offerDTO->product_ids
        ];
        
        $offer = $this->offerRepository->create($data);
        
        return OfferMapper::toResponse($offer);
    }

    public function updateOffer(int $id, OfferDTO $offerDTO): array
    {
        $offer = $this->offerRepository->find($id);
        
        if(!$offer){
            throw  CustomExceptions::notFoundError("Offer with ID { $id} not found.");
        }
        
        $data = [
            'title' => $offerDTO->title,
            'description' => $offerDTO->description,
            'discount_type' => $offerDTO->discount_type,
            'discount_value' => $offerDTO->discount_value,
            'applies_to' => $offerDTO->applies_to,
            'category_id' => $offerDTO->category_id,
            'start_date' => $offerDTO->start_date,
            'end_date' => $offerDTO->end_date,
            'product_ids' => $offerDTO->product_ids
        ];
        
        $offer = $this->offerRepository->update($id, $data);
        
        return OfferMapper::toResponse($offer);
    }

    public function deleteOffer(int $id): bool
    {
        $offer = $this->offerRepository->find($id);
        
        if(!$offer){
            throw  CustomExceptions::notFoundError("Offer with ID { $id} not found.");
        }
        return $this->offerRepository->delete($id);
    }

        
}