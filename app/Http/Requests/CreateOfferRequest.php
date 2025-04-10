<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => 'required|numeric|min:0',
            'applies_to' => ['required', Rule::in(['all', 'category', 'specific_products'])],
            'category_id' => 'nullable|required_if:applies_to,category|exists:categories,id',
            'product_ids' => 'nullable|required_if:applies_to,specific_products|array',
            'product_ids.*' => 'exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }
}