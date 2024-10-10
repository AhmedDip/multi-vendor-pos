<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerApiRequest extends FormRequest
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
            'name'    => 'required|string|max:255|min:3',
            'phone'   => [
                'required',
                'string',
                'regex:/^(\+8801[1-9][0-9]{8}|01[1-9][0-9]{8}|(\+\d{1,3})?\d{10,14})$/',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('shop_id', $this->header('shop_id'));
                })->ignore($this?->customer->id ?? null),
            ],
        ];
    }
}
