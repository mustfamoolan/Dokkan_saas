<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('edit admins');
    }

    public function rules(): array
    {
        $adminId = $this->route('admin')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $adminId,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
            'role' => 'required|exists:roles,name',
        ];
    }
}
