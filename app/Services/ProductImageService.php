<?php

namespace App\Services;

use App\Interfaces\ProductImageRepositoryInterface ;
use App\DTOs\ProductImageDTO;
use App\Exceptions\CustomExceptions;
use App\Interfaces\ProductRepositoryInterface;
use App\Mappers\ProductImageMapper;
use App\Models\ProductImage;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    private ProductImageRepositoryInterface $repository;
    private ProductRepositoryInterface $product_repository;
    public function __construct(ProductImageRepositoryInterface $repository,ProductRepositoryInterface $product_repository) {
        $this->repository=$repository;
        $this->product_repository=$product_repository;
    }

    public function storeImage(ProductImageDTO $dto): array
    {
        //  حفظ الصورة في قاعدة البيانات
        $producImage=$this->repository->save($dto);
         return ProductImageMapper::toResponse($producImage);
    }

    public function getProductImages(int $productId): array
    {
        $product=$this->product_repository->findById($productId);
        if(!$product){
            throw CustomExceptions::notFoundError("Product with Id {$productId} not found");
        }
        return ProductImageMapper::toCollectionResponse($this->repository->getByProductId($productId));
    }

     public function deleteImage(int $id ): bool
    {
        $productImage=$this->repository->findById($id);
        if(!$productImage){
            throw CustomExceptions::notFoundError("Product image with Id {$id} not found");
        }
        try {
            // حذف الصورة من التخزين
            Storage::disk('public')->delete($productImage->image_url);
            // حذف السجل من قاعدة البيانات
            return $this->repository->delete($productImage);
        }  catch(Exception $e){
            throw new CustomExceptions("Failed to delete product image: " . $e->getMessage(), 500);
        }
    }
    
}
