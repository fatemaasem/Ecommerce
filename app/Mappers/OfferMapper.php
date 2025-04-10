<?php

namespace App\Mappers;

use App\DTOs\OfferDTO;
use App\Models\Offer;
use Carbon\Carbon;

class OfferMapper
{
    public static function toDTO(Offer $offer): OfferDTO
    {
        return new OfferDTO(
            id: $offer->id,
            title: $offer->title,
            description: $offer->description,
            discount_type: $offer->discount_type,
            discount_value: $offer->discount_value,
            applies_to: $offer->applies_to,
            category_id: $offer->category_id,
            start_date: $offer->start_date,
            end_date: $offer->end_date,
            product_ids: $offer->products->pluck('id')->toArray()
        );
    }

    public static function toDTOCollection($offers): array
    {
        return $offers->map(fn($offer) => self::toResponse($offer))->toArray();
    }

    public static function toResponse(Offer $offer): array
    {
        return [
            'id' => $offer->id,
            'title' => $offer->title,
            'description' => $offer->description,
            'discount_type' => $offer->discount_type,
            'discount_value' => $offer->discount_value,
            'applies_to' => $offer->applies_to,
            'category' => $offer->category ? [
                'id' => $offer->category->id,
                'name' => $offer->category->name
            ] : null,
            'start_date' => Carbon::parse($offer->start_date)->toDateTimeString(),
            'end_date' => Carbon::parse($offer->end_date)->toDateTimeString(),
            'affected_products' => $offer->getAffectedProducts()->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'discounted_price' => app('App\Services\OfferService')->calculateDiscountedPrice($product)
                ];
            }),

            'created_at' => $offer->created_at->toDateTimeString(),
            'updated_at' => $offer->updated_at->toDateTimeString()
        ];
    }
}
