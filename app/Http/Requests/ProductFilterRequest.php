<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'featured' => 'nullable|boolean', // يمكن أن تكون true, false, أو غير موجودة
            'popular' => 'nullable|boolean',  // يمكن أن تكون true, false, أو غير موجودة
            'perPage' => 'nullable|integer|min:1|max:100', // عدد العناصر في الصفحة
        ];
    }
}
