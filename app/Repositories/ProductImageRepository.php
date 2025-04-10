<?php

namespace App\Repositories;

use App\Interfaces\ProductImageRepositoryInterface;
use App\DTOs\ProductImageDTO;
use App\Exceptions\CustomExceptions;
use App\Models\ProductImage;
use Illuminate\Database\QueryException;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    public function save(ProductImageDTO $dto): ProductImage
    {
        try{
        return ProductImage::create([
            'product_id' => $dto->product_id,
            'image_url' => $dto->image_path,
        ]);
        }catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }

       
    }

    public function getByProductId(int $productId): \Illuminate\Database\Eloquent\Collection
    {
        return ProductImage::where('product_id', $productId)->get();
    }

    public function delete(ProductImage $image):bool{
        try{
        return $image->delete();}
        catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }
    }

    public function findById( int $id): ?ProductImage{
        return ProductImage::find($id);
    }
}
