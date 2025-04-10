<?php

namespace App\Interfaces;

use App\DTOs\ProductImageDTO;
use App\Models\ProductImage;

interface ProductImageRepositoryInterface
{
    public function save(ProductImageDTO $dto): ProductImage;

    public function getByProductId(int $productId):  \Illuminate\Database\Eloquent\Collection;

    public function delete (ProductImage $image):bool;

    public function findById(int $id):?ProductImage;
}
