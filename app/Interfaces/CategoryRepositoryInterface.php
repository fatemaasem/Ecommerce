<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Category;
    public function create(array $data): Category;
    public function update(Category $category, array $data): ?Category;
    public function delete(int $id): bool;
}
