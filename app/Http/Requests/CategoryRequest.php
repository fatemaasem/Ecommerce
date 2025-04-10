<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('category'); // Get the ID in case of update

        // Default rules for both store and update
        $rules = [
            'name' => ['required', 'string', 'max:100', "unique:categories,name,$id"],
        ];
    
        // Add conditional rule for image_svg
        if ($this->isMethod('post')) {
            // For store (POST request), image_svg is required
            $rules['image_svg'] = ['required', 'file', 'mimes:svg'];
        } else {
            // For update (PUT/PATCH request), image_svg is optional
            $rules['image_svg'] = ['sometimes', 'file', 'mimes:svg'];
        }
    
        return $rules;


    }
}
