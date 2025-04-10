<?php

namespace App\DTOs;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\CategoryRequest;
use App\Mappers\CategoryMapper;
use Illuminate\Database\Eloquent\Collection;
class CategoryDTO
{
    public function __construct(
        public string $name,
        public ?UploadedFile $image_svg // جعل الحقل اختياريًا
    ) {}

    public static function fromRequest(CategoryRequest $request): self
    {
        return new self(
            name: $request->name,
            image_svg: $request->file('image_svg')// قد يكون null إذا لم يتم تحميل ملف
        );
    }


}
