<?php



namespace App\DTOs;

use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductDTO
{

    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly ?string $description,
        public readonly int $category_id,
        public readonly int $stock_quantity,
        public readonly bool $featured = false, // يمكن أن تكون true, false
        public readonly bool $popular = false,  // يمكن أن تكون true, false
    ) {}

    public static function fromRequest(ProductRequest $request): self
    {
        return new self(
            name: $request->name,
            price: $request->price,
            description: $request->description,
            category_id: $request->category_id,
            stock_quantity: $request->stock_quantity,
            featured: $request->featured ?? false, // القيمة الافتراضية false
            popular: $request->popular ?? false,   // القيمة الافتراضية false
        );
    }
}
