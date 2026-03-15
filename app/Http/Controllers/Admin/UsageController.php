<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\UsageLimitOverride;
use App\Models\UsageCounter;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsageController extends Controller
{
    protected $usageService;

    public function __construct(PlanUsageService $usageService)
    {
        $this->usageService = $usageService;
    }

    public function show(Store $store)
    {
        $store->load(['subscriber', 'counters', 'overrides']);

        $limits = [
            'max_users', 'max_branches', 'max_products', 'max_customers',
            'max_suppliers', 'max_representatives', 'max_warehouses',
            'max_invoices_per_month', 'max_orders_per_month', 'max_storage_mb'
        ];

        $booleans = [
            'has_reports', 'has_advanced_reports', 'has_pos', 'has_expenses',
            'has_debts', 'has_export_excel', 'has_export_pdf', 'has_printing',
            'has_multi_branch', 'has_multi_warehouse', 'has_barcode',
            'has_notifications', 'has_support', 'has_api_access'
        ];

        $usageData = [];
        foreach ($limits as $key) {
            $usageData['limits'][] = $this->usageService->getDetailedStatus($store, $key);
        }

        foreach ($booleans as $key) {
            $usageData['booleans'][] = $this->usageService->getDetailedStatus($store, $key);
        }

        return view('admin.pages.usage.show', compact('store', 'usageData'));
    }

    public function storeOverride(Request $request, Store $store)
    {
        if (!Auth::guard('admin')->user()->can('manage usage overrides')) {
            abort(403);
        }

        $request->validate([
            'feature_key' => 'required|string',
            'override_value' => 'required',
            'value_type' => 'required|in:limit,boolean',
            'notes' => 'nullable|string',
        ]);

        UsageLimitOverride::updateOrCreate(
            ['store_id' => $store->id, 'feature_key' => $request->feature_key],
            [
                'override_value' => $request->override_value,
                'value_type' => $request->value_type,
                'notes' => $request->notes,
            ]
        );

        return redirect()->back()->with('success', 'تم حفظ الاستثناء بنجاح.');
    }

    public function deleteOverride(Store $store, UsageLimitOverride $override)
    {
        if (!Auth::guard('admin')->user()->can('manage usage overrides')) {
            abort(403);
        }

        $override->delete();
        return redirect()->back()->with('success', 'تم حذف الاستثناء بنجاح.');
    }

    /**
     * Helper to manually update counters for testing (Admin only)
     */
    public function updateCounter(Request $request, Store $store)
    {
        $request->validate([
            'counter_key' => 'required|string',
            'current_value' => 'required|integer|min:0',
        ]);

        UsageCounter::updateOrCreate(
            ['store_id' => $store->id, 'counter_key' => $request->counter_key],
            ['current_value' => $request->current_value]
        );

        return redirect()->back()->with('success', 'تم تحديث العداد بنجاح.');
    }
}
