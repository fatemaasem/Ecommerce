<?php


namespace App\Mappers;

use App\DTOs\CategoryDTO;
use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryMapper
{
    public static function toDTO(Category $category): CategoryDTO
    {
        return new CategoryDTO(
            name: $category->name,
            image_svg:$category->image_svg
        );
    }
     /**
     * تحويل مجموعة منتجات إلى مصفوفة من `ProductDTO`
     */
    public static function toDTOCollection($categories): array
    {
        return $categories->map(fn ($category) => self::toResponse($category))->toArray();
    }

       /**
     * تحويل `DTO` إلى كائن JSON يحتوي على بيانات إضافية
     */
    public static function toResponse(Category $category): array
    {
        return [
            'id'=>$category->id,
            'name' => $category->name,
            'image_svg'=>asset('storage/'.$category->image_svg)
           
        ];
    }

    public static function toModelArray(CategoryDTO $dto,$path): array
    {
        if($path){//image updtaed
            return [
                'name' => $dto->name,
                'image_svg'=>$path
                
            ];
        }
        else{
            return [
                'name' => $dto->name,
            ];
        }
    }
 
}
