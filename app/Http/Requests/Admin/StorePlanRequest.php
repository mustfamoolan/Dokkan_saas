<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('create plans');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'is_free' => 'boolean',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
            'is_default' => 'boolean',
            'is_featured' => 'boolean',
            'trial_days' => 'required|integer|min:0',
            'sort_order' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_free' => $this->boolean('is_free'),
            'is_active' => $this->boolean('is_active'),
            'is_visible' => $this->boolean('is_visible'),
            'is_default' => $this->boolean('is_default'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
