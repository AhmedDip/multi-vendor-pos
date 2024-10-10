<?php

namespace App\Http\Requests;


use CheckProductSlot;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'order_date' => 'required',
            'shop_id'    => 'required',
            'status'     => 'required',
            'phone'      => 'required',        
        ];
    }
}
