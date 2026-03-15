<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subscriber')->check();
    }

    public function rules(): bool|array
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;
        $categoryId = $this->route('category')?->id;

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('product_categories', 'name')
                    ->where('store_id', $storeId)
                    ->ignore($categoryId)
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }
}
