<?php

namespace App\Http\Requests\Admin;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can('create subscriptions');
    }

    public function rules(): array
    {
        return [
            'subscriber_id' => 'required|exists:subscribers,id',
            'store_id' => 'required|exists:stores,id',
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
                $exists = Subscription::where('store_id', $this->store_id)
                    ->whereIn('status', ['active', 'trial'])
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('status', 'لا يمكن إضافة اشتراك نشط أو تجريبي جديد لأن المتجر لديه اشتراك قائم بالفعل.');
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
