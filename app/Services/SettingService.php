<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    /**
     * Get a setting value by key.
     */
    public function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key.
     */
    public function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.$key");
    }

    /**
     * Update multiple settings at once.
     */
    public function updateMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $value = $this->handleFileUpload($key, $value);
            }
            $this->set($key, $value);
        }
    }

    /**
     * Handle file uploads for settings (e.g., logo).
     */
    private function handleFileUpload(string $key, $file): string
    {
        $oldValue = $this->get($key);
        if ($oldValue) {
            Storage::disk('public')->delete($oldValue);
        }

        return $file->store('settings', 'public');
    }
}
