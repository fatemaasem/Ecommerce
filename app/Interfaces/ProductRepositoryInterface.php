<?php



namespace  App\Interfaces;

use App\DTOs\ProductFilterDTO;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
interface ProductRepositoryInterface
{
    public function getAll(ProductFilterDTO $productFilterDTO): LengthAwarePaginator;
    public function getByCategory(int $categoryId): Collection;
    public function findById(int $id): ?Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): bool;
    public function getProductQuantityAndPrice(int $productId): Product;
    public function decrementQuantity(int $productId, int $quantity): void;
}
