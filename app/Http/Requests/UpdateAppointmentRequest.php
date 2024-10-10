<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
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
            'category_id' => 'required',
            // 'product_id' =>'required',
            'date'      => 'required',
            'name'      => 'required|string|max:255',
            'phone'     =>  ['required','string',
                'regex:/^(\+8801[1-9][0-9]{8}|01[1-9][0-9]{8}|(\+\d{1,3})?\d{10,14})$/',
            ],
        ];
    }
}
