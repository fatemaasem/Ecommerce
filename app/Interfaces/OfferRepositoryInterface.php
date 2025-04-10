<?php

namespace App\Interfaces;

use App\Models\Offer;
use Illuminate\Support\Collection;

interface OfferRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Offer;
    public function create(array $data): Offer;
    public function update(int $id, array $data): Offer;
    public function delete(int $id): bool;
    public function getAffectedProducts(int $offerId): Collection;
}