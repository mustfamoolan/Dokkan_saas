<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StorePOSRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subscriber')->check();
    }

    public function rules(): array
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;

        return [
            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')->where('store_id', $storeId)
            ],
            'warehouse_id' => [
                'required',
                Rule::exists('warehouses', 'id')->where('store_id', $storeId)
            ],
            'notes' => 'nullable|string',
            'discount_amount' => 'required|numeric|min:0',
            
            'items' => 'required|array|min:1',
            'items.*.product_id' => [
                'required',
                Rule::exists('products', 'id')->where('store_id', $storeId)
            ],
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'items.required' => 'يجب إضافة منتج واحد على الأقل في السلة.',
            'items.*.product_id.required' => 'يجب اختيار المنتج.',
            'items.*.quantity.min' => 'يجب أن تكون الكمية أكبر من صفر.',
        ];
    }
}
