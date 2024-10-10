<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($this?->product?->id)->where(function ($query) {
                return $query->where('shop_id', $this->input('shop_id'));
            }),],

            'sku'  => ['required', 'string', 'max:255', Rule::unique('products')->ignore($this?->product?->id)->where(function ($query) {
                return $query->where('shop_id', $this->input('shop_id'));
            }),],
            'category_id'   => 'required|integer',
            'shop_id'       => 'required|integer',
            'price'         => 'required|numeric',
            'sort_order'    => 'required|integer',
            'status'        => 'required|integer',
        ];
    }
}
