<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ProductImageDTO;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;
use App\Mappers\ProductImageMapper;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductImageController extends ApiController
{
    private ProductImageService $service;
    public function __construct(ProductImageService $service) {
        $this->service=$service;
    }

    public function store(ProductImageRequest $request): JsonResponse
    {
        $dto=ProductImageDTO::fromRequest($request);

        return ApiController::successResponse([
            'data'=>$this->service->storeImage($dto),
            'message'=>'Product image created successfully',
        ],201);
    }

    public function getByProductId(int $productId)
    {
      
        return ApiController::successResponse([
            'data'=>$this->service->getProductImages($productId),
            'message'=>'Product images fetched successfully',
        ]);
        
    }

    public function destroy(int $id)
    {
        $this->service->deleteImage($id);
        return ApiController::successResponse([
            'message'=>'Product images deleted successfully',
        ]);
       
    }
}
