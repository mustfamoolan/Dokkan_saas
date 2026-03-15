<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('manage settings');
    }

    public function rules(): array
    {
        return [
            // General Settings
            'platform_name' => 'required|string|max:255',
            'platform_tagline' => 'nullable|string|max:255',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:500',
            'default_currency' => 'nullable|string|max:10',
            'default_timezone' => 'nullable|string|max:100',
            'default_locale' => 'nullable|string|max:5',

            // Registration Settings
            'registration_enabled' => 'nullable',
            'auto_activate_accounts' => 'nullable',
            'trial_enabled' => 'nullable',
            'trial_days' => 'nullable|integer|min:0',

            // Payment Settings
            'payment_receiver_name' => 'nullable|string|max:255',
            'payment_phone' => 'nullable|string|max:50',
            'payment_account_number' => 'nullable|string|max:100',
            'payment_instructions' => 'nullable|string',

            // Branding Settings
            'platform_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'primary_color' => 'nullable|string|max:20',
        ];
    }
}
