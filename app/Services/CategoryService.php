<?php


namespace App\Services;

use App\DTOs\CategoryDTO;
use App\Exceptions\CustomExceptions;
use App\Interfaces\CategoryRepositoryInterface;
use App\Mappers\CategoryMapper;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    private  $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll(): array
    {
        $categories=$this->categoryRepository->getAll();

        return CategoryMapper::toDTOCollection($categories);
    }

    public function getById(int $id): ?array
    {
        $category = $this->categoryRepository->getById($id);
        if(!$category){
            throw  CustomExceptions::notFoundError("Category with ID { $id} not found.");
        }
        return CategoryMapper::toResponse($category) ;
    }

    public function create(CategoryDTO $categoryDTO): array
    {
          // Store the uploaded file
       try{
        $path=$categoryDTO->image_svg->store('categories','public');
        $category = $this->categoryRepository->create(CategoryMapper::toModelArray($categoryDTO,$path));
        return  CategoryMapper::toResponse($category);
       }
       catch(Exception $e){
        throw new CustomExceptions("Failed to create category: " . $e->getMessage(), 500);
       }
    }

    public function update($id, CategoryDTO $categoryDTO) 
    {
        $oldCategory=$this->categoryRepository->getById($id);

        if(! $oldCategory){
            throw  CustomExceptions::notFoundError("Category with ID { $id} not found.");
        }
        try{
            // If a new file is uploaded, delete  the old image
            $path='';
        if ($categoryDTO->image_svg) {
            
                $path=$categoryDTO->image_svg->store('categories','public');
                // Delete the old file
                
                Storage::disk('public')->delete($oldCategory->image_svg);
            }
            
        $category= $this->categoryRepository->update($oldCategory,CategoryMapper::toModelArray($categoryDTO,$path) );
        return  CategoryMapper::toResponse($category);
        }
        catch (Exception $e){
            throw  new CustomExceptions("Failed to update category: " . $e->getMessage(), 500);
        }
    }

    public function delete(int $id): bool
    {
        $category=$this->categoryRepository->getById($id);

        if(!$category){
            throw  CustomExceptions::notFoundError("Category with ID { $id} not found.");
        }
        try{
        Storage::disk('public')->delete($category->image_svg);
        return $this->categoryRepository->delete($id);
        }
        catch(Exception $e){
            throw new CustomExceptions("Failed to delete category: " . $e->getMessage(), 500);
        }
    }
}
