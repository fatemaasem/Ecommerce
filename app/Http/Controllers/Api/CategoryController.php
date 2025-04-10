<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CategoryDTO;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    // private CategoryService $categoryService;
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        
        return ApiController::successResponse([
            'data'=>$this->categoryService->getAll(),
            'message'=>'Category fetched successfully',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);
     
        return ApiController::successResponse([
            'data'=>$category,
            'message'=>'Category fetched successfully',
        ]);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
       
        $categoryDTO = CategoryDTO::fromRequest($request);
       
        return ApiController::successResponse([
            'data'=> $this->categoryService->create($categoryDTO),
            'message'=>'Category created successfully.',
        ]);
    }

    public function update(CategoryRequest $request, int $categoryId): JsonResponse
    {
        
        $categoryDTO = CategoryDTO::fromRequest($request);
        $response = $this->categoryService->update($categoryId, $categoryDTO);
      
        if ($response) {
            return $this->sendResponse('Category updated successfully.',  $response);
        } else {
            return $this->sendResponse('Category not found.', null, 404);
        }
    }
    

    public function destroy(int $id): JsonResponse
    {
        return $this->categoryService->delete($id)
            ? $this->sendResponse('Category deleted successfully.')
            :  $this->sendResponse('Category not found.', null, 404);;
    }
}