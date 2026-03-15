<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanFeaturesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('edit plan features');
    }

    public function rules(): array
    {
        return [
            'limits.*' => 'required|integer|min:0',
            'features.*' => 'boolean',
        ];
    }
}
