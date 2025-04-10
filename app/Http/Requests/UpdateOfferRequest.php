<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'discount_type' => ['sometimes', Rule::in(['percentage', 'fixed'])],
            'discount_value' => 'sometimes|numeric|min:0',
            'applies_to' => ['sometimes', Rule::in(['all', 'category', 'specific_products'])],
            'category_id' => 'nullable|required_if:applies_to,category|exists:categories,id',
            'product_ids' => 'nullable|required_if:applies_to,specific_products|array',
            'product_ids.*' => 'exists:products,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ];
    }
}