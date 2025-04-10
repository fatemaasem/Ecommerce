<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Exceptions\CustomExceptions;
use App\Models\Product;
use App\DTOs\ProductDTO;
use App\DTOs\ProductFilterDTO;
use App\Http\Requests\ProductFilterRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface ;
use App\Mappers\ProductMapper;
use App\Repositories\ProductRepository;

class ProductService
{
    private ProductRepositoryInterface $ProductRepository;
    private  CategoryRepositoryInterface $categoryRepository;
    public function __construct( ProductRepositoryInterface $repository, CategoryRepositoryInterface $categoryRepository) {
        $this->ProductRepository=$repository;
        $this->categoryRepository=$categoryRepository;
    }

    public function getAll(ProductFilterDTO $productFilterDto): array
    {
    
        return ProductMapper::toDTOCollection($this->ProductRepository->getAll($productFilterDto));
    }

    public function getByCategory(int $categoryId): array
    {
        $category=$this->categoryRepository->getById($categoryId);
        if (!$category) {
            throw  CustomExceptions::notFoundError("Category with ID { $categoryId} not found.");
        }
        return ProductMapper::toDTOCollection($this->ProductRepository->getByCategory($categoryId));
    }

    public function findById(int $id): array
    {
        $product = $this->ProductRepository->findById($id);
        if (!$product) {
            throw  CustomExceptions::notFoundError("Product with ID { $id} not found.");
        }
        return ProductMapper::toResponse($product);
    }

    public function create(ProductDTO $dto): array
    {
        $product = $this->ProductRepository->create(ProductMapper::toModelArray($dto));
        return ProductMapper::toResponse($product);
    }

    public function update(int $productId, ProductDTO $dto): bool
    {
        $product = $this->ProductRepository->findById($productId);

        if (!$product) {
            throw  CustomExceptions::notFoundError("Product with ID { $productId} not found.");
        }
        try {
            // Update the product in the repository
            $this->ProductRepository->update($product, ProductMapper::toModelArray($dto));
    
            // Return true to indicate success
            return true;
    
        } catch (\Exception $e) {
           
            // Throw a custom exception with a user-friendly message
            throw new CustomExceptions("Failed to update product. Please try again later.". $e->getMessage(),500);
        }
    }


    /**
     * @throws CustomExceptions
     */
    public function delete(int $id)
    {
        $product = $this->ProductRepository->findById($id);

        if (!$product) {
            throw  CustomExceptions::notFoundError("Product with ID {$id} not found.");
        }
        try {
            // Define storage folder path
            $folderPath = "products/{$product->id}";

            // Delete product folder in storage
            Storage::deleteDirectory($folderPath);

            // Delete from public storage if symbolic link exists
            $publicPath = public_path("storage/products/{$product->id}");
            if (File::exists($publicPath)) {
                File::deleteDirectory($publicPath);
            }

            // Delete the product from the database
            return $this->ProductRepository->delete($product);

        } catch (\Exception $e) {
            throw  new CustomExceptions("Failed to delete product: " . $e->getMessage(), 500);
        }
    }}
