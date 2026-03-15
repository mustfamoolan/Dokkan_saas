<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the settings form.
     */
    public function index()
    {
        $settings = [
            'platform_name' => $this->settingService->get('platform_name', config('app.name')),
            'platform_tagline' => $this->settingService->get('platform_tagline'),
            'support_email' => $this->settingService->get('support_email'),
            'support_phone' => $this->settingService->get('support_phone'),
            'company_address' => $this->settingService->get('company_address'),
            'default_currency' => $this->settingService->get('default_currency', 'USD'),
            'default_timezone' => $this->settingService->get('default_timezone', 'UTC'),
            'default_locale' => $this->settingService->get('default_locale', 'ar'),

            'registration_enabled' => $this->settingService->get('registration_enabled', true),
            'auto_activate_accounts' => $this->settingService->get('auto_activate_accounts', true),
            'trial_enabled' => $this->settingService->get('trial_enabled', true),
            'trial_days' => $this->settingService->get('trial_days', 14),

            'payment_receiver_name' => $this->settingService->get('payment_receiver_name'),
            'payment_phone' => $this->settingService->get('payment_phone'),
            'payment_account_number' => $this->settingService->get('payment_account_number'),
            'payment_instructions' => $this->settingService->get('payment_instructions'),

            'platform_logo' => $this->settingService->get('platform_logo'),
            'favicon' => $this->settingService->get('favicon'),
            'primary_color' => $this->settingService->get('primary_color', '#ff6c2f'),
        ];

        return view('admin.pages.settings.index', compact('settings'));
    }

    /**
     * Save the settings.
     */
    public function update(UpdateSettingsRequest $request)
    {
        \Log::info('Settings update attempt', $request->all());
        $data = $request->validated();

        // Handle checkboxes (boolean values)
        $checkboxes = ['registration_enabled', 'auto_activate_accounts', 'trial_enabled'];
        foreach ($checkboxes as $checkbox) {
            $data[$checkbox] = $request->has($checkbox) ? 1 : 0;
        }

        $this->settingService->updateMany($data);

        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}
