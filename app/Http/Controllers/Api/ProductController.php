<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ProductDTO;
use App\DTOs\ProductFilterDTO;
use App\Exceptions\CustomExceptions;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\ApiResponses;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use TypeError;
class ProductController extends ApiController

{

    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index(ProductFilterRequest $request): JsonResponse
    {
       
        $productFilterDto=ProductFilterDTO::fromRequest($request);
        $products =$this->productService->getAll($productFilterDto);
        return ApiController::successResponse([
            'data'=>$products,
            'message'=>'Product fetched successfully',
            'count'=>count($products['data'])
        ]);


    }

    public function getByCategory(int $category_id): JsonResponse
    {
        $products = $this->productService->getByCategory($category_id);
        return ApiController::successResponse([
            'data'=>$products,
            'message'=>'Product fetched successfully',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);
        return ApiController::successResponse([
            'data'=>$product,
            'message'=>'Product fetched successfully',
        ]);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $dto = ProductDTO::fromRequest($request);
        $products = $this->productService->create($dto);

        return ApiController::successResponse([
            'data'=>$products,
            'message'=>'Product created successfully',
        ],201);
    }

    public function update(ProductRequest $request, int $productId): JsonResponse
    {
            $dto = ProductDTO::fromRequest($request);

            $this->productService->update($productId, $dto);

            return ApiController::successResponse([
                'message' => 'Product updated successfully',
            ]);
    }

    public function destroy(int $id): JsonResponse
    {
         $this->productService->delete($id);
        return ApiController::successResponse([
            'message'=>'Product deleted successfully',
        ]);    }


}
