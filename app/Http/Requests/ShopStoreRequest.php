<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopStoreRequest extends FormRequest
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
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:shops,slug',
            'email'       => 'required|string|email|max:255|unique:shops,email',
            'phone'       => 'required|string|unique:shops,phone|max:11',
            // 'description' => 'required|string',
            'address'     => 'string|max:255',
        ];
    }
}
