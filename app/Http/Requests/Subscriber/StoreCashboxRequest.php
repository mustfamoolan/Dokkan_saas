<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCashboxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('subscriber')->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'current_balance' => 'sometimes|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }
}
