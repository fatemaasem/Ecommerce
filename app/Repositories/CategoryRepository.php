<?php


namespace App\Repositories;

use App\Exceptions\CustomExceptions;
use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        return Category::all();
    }

    public function getById(int $id): ?Category
    {
        return Category::find($id)??null;
    }

    public function create(array $data): Category
    {
        try{
            return Category::create($data);}
        catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }
    }

    public function update(Category $category, array $data): ?Category
    {
        try{
        $category->update($data) ;
            return $category;
        }catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }
    }

    public function delete(int $id): bool
    {
        try{
            return Category::destroy($id);
        }
        catch (QueryException $e) {
            throw CustomExceptions::queryError($e);
        }
    }
}
