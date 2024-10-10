<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        return[
            'name'    => 'required|string|max:255|min:3',
            'phone'   => [
                'required',
                'string',
                'regex:/^(\+8801[1-9][0-9]{8}|01[1-9][0-9]{8}|(\+\d{1,3})?\d{10,14})$/',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('shop_id', $this->input('shop_id'));
                })->ignore($this->route('customer')) 
            ],
            'shop_id' => 'required'
        ];
        
    }

      /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    final public function messages()
    {
        return [
            'phone.unique' => 'There is an existing customer with the same phone number and shop id.',
        ];
    }
}





 