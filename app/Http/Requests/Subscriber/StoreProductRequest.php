<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subscriber')->check();
    }

    public function rules(): bool|array
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;
        $productId = $this->route('product')?->id;

        return [
            'name' => 'required|string|max:255',
            'category_id' => [
                'nullable',
                Rule::exists('product_categories', 'id')->where('store_id', $storeId)
            ],
            'sku' => [
                'nullable', 'string', 'max:100',
                Rule::unique('products', 'sku')->where('store_id', $storeId)->ignore($productId)
            ],
            'barcode' => [
                'nullable', 'string', 'max:100',
                Rule::unique('products', 'barcode')->where('store_id', $storeId)->ignore($productId)
            ],
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }
}
