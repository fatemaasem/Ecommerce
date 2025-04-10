<?php

namespace App\DTOs;

use Carbon\Carbon;

class OfferDTO
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public string $discount_type,
        public float $discount_value,
        public string $applies_to,
        public ?int $category_id,
        public Carbon $start_date,
        public Carbon $end_date,
        public ?array $product_ids = []
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'],
            description: $data['description'],
            discount_type: $data['discount_type'],
            discount_value: $data['discount_value'],
            applies_to: $data['applies_to'],
            category_id: $data['category_id'] ?? null,
            start_date: Carbon::parse($data['start_date']),
            end_date: Carbon::parse($data['end_date']),
            product_ids: $data['product_ids'] ?? []
        );
    }
}