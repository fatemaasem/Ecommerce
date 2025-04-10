<?php namespace App\Repositories;

use App\DTOs\ProductFilterDTO;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use App\Exceptions\CustomExceptions;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(ProductFilterDTO $productFilterDTO):LengthAwarePaginator
{
    // Start building the query with eager loading for relationships
    $query = Product::with(['category', 'images', 'user']);

    // Apply the 'featured' filter if it is true
    if ($productFilterDTO->featured === true) {
        $query->featured(); // Assuming 'featured()' is a scope defined in the Product model
    }

    // Apply the 'popular' filter if it is true
    if ($productFilterDTO->popular === true) {
        $query->popular(); // Assuming 'popular()' is a scope defined in the Product model
    }

    // Always apply pagination using the 'perPage' value from the DTO (default is 50)
   
    return $query->paginate($productFilterDTO->perPage);
}

    public function getByCategory(int $categoryId): Collection
    {
        return Product::with(['category', 'images'])->where('category_id', $categoryId)->get();
    }

    public function findById(int $id): ?Product
    {
        return Product::with(['category', 'images'])->find($id);
    }

    public function create(array $data): Product
    {
        try {
            return Product::create($data);
        } catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }
    }

    public function update(Product $product, array $data): Product
    {
        try {
            $product->update($data);
            return $product;
        } catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }
    }

    public function delete(Product $product): bool
    {
        try {
            return $product->delete();
        } catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }
    }
    public function getProductQuantityAndPrice(int $productId): Product
    {
        $product = Product::select('stock_quantity','price')->find($productId);
        
        if (!$product) {
            throw CustomExceptions::notFoundError('Product not found');
        }

        return $product;
    }

    public function decrementQuantity(int $productId, int $quantity): void
    {
        
        DB::transaction(function () use ($productId, $quantity) {
            // Lock the product row for update
            $product = Product::where('id', $productId)
                ->first();

            if (!$product) {
                throw CustomExceptions::notFoundError("Product {$productId} not found");
            }

            
            if ($product->stock_quantity < $quantity) {
                throw CustomExceptions::insufficientQuantityError(
                    "Insufficient quantity for this product {$product->id} available quantity is {$product->stock_quantity} and requested quantity is {$quantity} .",
                  );
              
            }
            
            $product->decrement('stock_quantity', $quantity);
           
           

        });
    }
    
}