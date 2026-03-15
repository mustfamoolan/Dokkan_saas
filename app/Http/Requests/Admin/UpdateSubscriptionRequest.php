<?php

namespace App\Http\Requests\Admin;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('edit subscriptions');
    }

    public function rules(): array
    {
        return [
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|string|in:monthly,yearly,trial,custom',
            'status' => 'required|string|in:pending,active,trial,expired,suspended,cancelled',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_trial' => 'boolean',
            'trial_days' => 'required_if:is_trial,1|integer|min:0',
            'auto_renew' => 'boolean',
            'notes' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->status == 'active' || $this->status == 'trial') {
                $exists = Subscription::where('store_id', $this->route('subscription')->store_id)
                    ->where('id', '!=', $this->route('subscription')->id)
                    ->whereIn('status', ['active', 'trial'])
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('status', 'لا يمكن تفعيل هذا الاشتراك لأن المتجر لديه اشتراك نشط أو تجريبي آخر.');
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_trial' => $this->boolean('is_trial'),
            'auto_renew' => $this->boolean('auto_renew'),
        ]);
    }
}
