<?php
namespace App\DTOs;

use App\Http\Requests\ProductFilterRequest;

class ProductFilterDTO
{
    public function __construct(
        public readonly bool $featured = false, // يمكن أن تكون true, false, أو null
        public readonly bool $popular = false,  // يمكن أن تكون true, false, أو null
        public readonly int $perPage = 50        // القيمة الافتراضية 50
    ) {}

    /**
     * تحويل الـ Request إلى DTO.
     */
    public static function fromRequest(ProductFilterRequest $request): self
    {
        return new self(
            featured: $request->featured ?? false, // القيمة الافتراضية false
            popular: $request->popular ?? false,   // القيمة الافتراضية false
            perPage: $request->perPage ?? 50       // القيمة الافتراضية 50
        );
    }
}