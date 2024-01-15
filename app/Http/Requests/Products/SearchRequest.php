<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => ['nullable', 'string', 'max:255', 'min:3'],
            'filters.productLine' => ['nullable', 'string'],
            'filters.qty.min' => ['nullable', 'integer', $this->filled('filters.qty.max') ? 'lte:filters.qty.max' : '', 'min:0'],
            'filters.qty.max' => ['nullable', 'integer', $this->filled('filters.qty.min') ? 'gte:filters.qty.min' : '', 'min:0'],
            'filters.price.min' => ['nullable', 'numeric', $this->filled('filters.price.max') ? 'lte:filters.price.max' : '', 'min:0'],
            'filters.price.max' => ['nullable', 'numeric', $this->filled('filters.price.min') ? 'gte:filters.price.min' : '', 'min:0'],
        ];
    }
}
