<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogCategoryRequest extends FormRequest
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
            'name'             => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('blog_categories')->where(function ($query) {
                return $query->where('shop_id', $this->input('shop_id'));
            }),],            
            // 'description'      => 'nullable|string',
            'parent_id'        => 'nullable|integer|exists:blog_categories,id',
            'status'           => 'required|integer|in:1,2',
            'display_order'    => 'nullable|integer',
            'photo'            => 'nullable',
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'og_image'         => 'nullable',
        ];
    }
}
