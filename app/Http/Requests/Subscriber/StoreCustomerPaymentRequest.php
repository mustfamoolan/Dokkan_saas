<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCustomerPaymentRequest extends FormRequest
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
            'cashbox_id' => [
                'required',
                Rule::exists('cashboxes', 'id')->where('store_id', $storeId)
            ],
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
