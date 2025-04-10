<?php

namespace App\Mappers;

use App\Models\ProductImage;

class ProductImageMapper
{
    public static function toResponse(ProductImage $image): array
    {
        return [
            'id' => $image->id,
            'image_url' =>$image->full_image_url,
        ];
    }

    public static function toCollectionResponse($images): array
    {
        return $images->map(fn ($image) => self::toResponse($image))->toArray();
    }
}
