<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesExecutiveRequest extends FormRequest
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
            'name'             => 'required|max:255|min:3',
            'email'            => 'required|email|unique:users' ,
            'phone'            => 'numeric|unique:users',
            'password'         => 'required|min:8',
        ];
    }
}
