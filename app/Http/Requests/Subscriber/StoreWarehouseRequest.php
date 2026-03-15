<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subscriber')->check();
    }

    public function rules(): array
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;
        $warehouseId = $this->route('warehouse')?->id;

        return [
            'name' => 'required|string|max:255',
            'code' => [
                'nullable', 'string', 'max:50',
                Rule::unique('warehouses', 'code')->where('store_id', $storeId)->ignore($warehouseId)
            ],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }
}
