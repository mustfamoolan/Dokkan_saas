<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('edit subscribers');
    }

    public function rules(): array
    {
        $subscriberId = $this->route('subscriber')->id;

        return [
            // Subscriber info
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:subscribers,phone,' . $subscriberId,
            'email' => 'nullable|email|max:255|unique:subscribers,email,' . $subscriberId,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|string|in:active,pending,suspended',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',

            // Store info
            'store_name' => 'required|string|max:255',
            'store_phone' => 'nullable|string|max:20',
            'store_address' => 'nullable|string',
            'store_logo' => 'nullable|image|max:2048',
            'currency' => 'required|string|max:10',
            'locale' => 'required|string|max:10',
            'timezone' => 'required|string|max:255',
            'store_status' => 'required|string|in:active,inactive',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
