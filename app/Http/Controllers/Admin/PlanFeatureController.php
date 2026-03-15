<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Http\Requests\Admin\UpdatePlanFeaturesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanFeatureController extends Controller
{
    public function edit(Plan $plan)
    {
        $limits = [
            'max_users' => 'الحد الأقصى للمستخدمين',
            'max_branches' => 'الحد الأقصى للفروع',
            'max_products' => 'الحد الأقصى للمنتجات',
            'max_customers' => 'الحد الأقصى للعملاء',
            'max_suppliers' => 'الحد الأقصى للموردين',
            'max_representatives' => 'الحد الأقصى للمندوبين',
            'max_warehouses' => 'الحد الأقصى للمستودعات',
            'max_invoices_per_month' => 'الفواتير شهرياً',
            'max_orders_per_month' => 'الطلبات شهرياً',
            'max_storage_mb' => 'مساحة التخزين (MB)',
        ];

        $features = [
            'has_reports' => 'التقارير الأساسية',
            'has_advanced_reports' => 'التقارير المتقدمة',
            'has_pos' => 'نظام الكاشير (POS)',
            'has_expenses' => 'إدارة المصروفات',
            'has_debts' => 'إدارة الديون',
            'has_export_excel' => 'تصدير إكسل',
            'has_export_pdf' => 'تصدير PDF',
            'has_printing' => 'الطباعة',
            'has_multi_branch' => 'تعدد الفروع',
            'has_multi_warehouse' => 'تعدد المستودعات',
            'has_barcode' => 'نظام الباركود',
            'has_notifications' => 'التنبيهات',
            'has_support' => 'الدعم الفني',
            'has_api_access' => 'الوصول لـ API',
        ];

        $currentFeatures = $plan->features->pluck('feature_value', 'feature_key')->toArray();

        return view('admin.pages.plans.features', compact('plan', 'limits', 'features', 'currentFeatures'));
    }

    public function update(UpdatePlanFeaturesRequest $request, Plan $plan)
    {
        DB::transaction(function () use ($request, $plan) {
            // Update Limits
            foreach ($request->input('limits', []) as $key => $value) {
                PlanFeature::updateOrCreate(
                    ['plan_id' => $plan->id, 'feature_key' => $key],
                    ['feature_value' => $value, 'value_type' => 'integer']
                );
            }

            // Update Features (Booleans)
            // Note: UpdatePlanFeaturesRequest prepareForValidation ensures they are boolean
            $allFeatureKeys = [
                'has_reports', 'has_advanced_reports', 'has_pos', 'has_expenses', 'has_debts',
                'has_export_excel', 'has_export_pdf', 'has_printing', 'has_multi_branch',
                'has_multi_warehouse', 'has_barcode', 'has_notifications', 'has_support', 'has_api_access'
            ];

            foreach ($allFeatureKeys as $key) {
                $value = $request->has("features.$key") ? '1' : '0';
                PlanFeature::updateOrCreate(
                    ['plan_id' => $plan->id, 'feature_key' => $key],
                    ['feature_value' => $value, 'value_type' => 'boolean']
                );
            }
        });

        return redirect()->route('admin.plans')->with('success', 'تم تحديث مزايا الباقة بنجاح.');
    }
}
