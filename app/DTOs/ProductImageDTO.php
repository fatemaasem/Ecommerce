<?php


namespace App\DTOs;

use App\Http\Requests\ProductImageRequest;

class ProductImageDTO
{

    public function __construct(
        public readonly int $product_id,
        public readonly string $image_path
    ) {}

    public static function fromRequest(ProductImageRequest $request): self
    {
        return new self(
            product_id: $request->product_id,
            image_path: $request->file('file')->store("products/{$request->product_id}", 'public')
        );
    }
}
