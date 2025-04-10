<?php

namespace App\Repositories;

use App\Interfaces\OfferRepositoryInterface;
use App\Models\Offer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OfferRepository implements OfferRepositoryInterface
{
    public function all(): Collection
    {
        return Offer::with(['category'])->get();
    }

    public function find(int $id): ?Offer
    {
        return Offer::with(['category'])->find($id);
    }

    public function create(array $data): Offer
    {
        return DB::transaction(function () use ($data) {
            $offer = Offer::create($data);
            
            if ($data['applies_to'] === 'specific_products' && !empty($data['product_ids'])) {
                $offer->products()->attach($data['product_ids']);
            }
            
            return $offer->load(['category', 'products']);
        });
    }

    public function update(int $id, array $data): Offer
    {
        return DB::transaction(function () use ($id, $data) {
            $offer = Offer::findOrFail($id);
            $offer->update($data);
            
            if ($data['applies_to'] === 'specific_products') {
                $offer->products()->sync($data['product_ids'] ?? []);
            } elseif ($offer->applies_to !== $data['applies_to'] && $offer->applies_to === 'specific_products') {
                $offer->products()->detach();
            }
            
            return $offer->load(['category', 'products']);
        });
    }

    public function delete(int $id): bool
    {
        $offer = Offer::findOrFail($id);
        return $offer->delete();
    }

    public function getAffectedProducts(int $offerId): Collection
    {
        $offer = Offer::findOrFail($offerId);
        return $offer->getAffectedProducts();
    }
}