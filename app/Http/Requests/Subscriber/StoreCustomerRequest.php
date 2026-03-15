<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subscriber')->check();
    }

    public function rules(): array
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;
        $customerId = $this->route('customer')?->id;

        return [
            'name' => 'required|string|max:255',
            'phone' => [
                'required', 'string', 'max:20',
                Rule::unique('customers', 'phone')->where('store_id', $storeId)->ignore($customerId)
            ],
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'opening_balance' => 'required|numeric|min:0',
            'opening_balance_type' => 'required|in:debit,credit,none',
            'is_active' => 'boolean',
        ];
    }
}
