<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    final public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'slug'          => 'required|string|max:255|unique:shops,slug,'. $this?->shop?->id,
        
            'address'       => 'required|string|max:255',
            'phone'         => 'required|string|max:11|unique:shops,phone,' . $this?->shop?->id,
            'shop_owner_id' => 'required|integer',
        ];
    }
}
