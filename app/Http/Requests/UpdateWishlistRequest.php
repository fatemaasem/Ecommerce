<?php

namespace App\Http\Requests;

use App\Exceptions\CustomExceptions;
use App\Models\Wishlist;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWishlistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       
        if (!$this->user()->can('update', Wishlist::find($this->route('wishlistId')))) {
            throw CustomExceptions::authorizationError('You are not authorized to add items to wishlist');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           
            'quantity' => 'required|integer|min:1',
        ];
    }
}
