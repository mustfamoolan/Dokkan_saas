<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ProductStock;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Create a new notification for a store.
     */
    public function notify($storeId, $type, $title, $message, $severity = 'info', $actionUrl = null, $meta = [])
    {
        // Simple deduplication: don't create the same unread notification for the same type/store within the last 24 hours
        $exists = Notification::where('store_id', $storeId)
            ->where('type', $type)
            ->where('is_read', false)
            ->where('created_at', '>', Carbon::now()->subDay())
            ->exists();

        if ($exists) {
            return null;
        }

        return Notification::create([
            'store_id' => $storeId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'severity' => $severity,
            'action_url' => $actionUrl,
            'meta' => $meta,
        ]);
    }

    /**
     * Get unread notifications count for a store.
     */
    public function getUnreadCount($storeId)
    {
        return Notification::where('store_id', $storeId)->unread()->count();
    }

    /**
     * Get latest notifications for a store.
     */
    public function getLatest($storeId, $limit = 5)
    {
        return Notification::where('store_id', $storeId)->latest()->limit($limit)->get();
    }

    /**
     * Check and generate stock alerts.
     */
    public function checkStockAlerts($storeId)
    {
        $lowStocks = ProductStock::where('store_id', $storeId)
            ->whereNotNull('alert_quantity')
            ->whereColumn('current_quantity', '<=', 'alert_quantity')
            ->with('product', 'warehouse')
            ->get();

        foreach ($lowStocks as $stock) {
            $type = 'stock_low_' . $stock->id;
            $severity = $stock->current_quantity <= 0 ? 'danger' : 'warning';
            $status = $stock->current_quantity <= 0 ? 'نفد من المخزون' : 'منخفض المخزون';
            
            $this->notify(
                $storeId,
                $type,
                "تنبيه مخزون: {$stock->product->name}",
                "المنتج {$stock->product->name} في مستودع {$stock->warehouse->name} {$status}. الكمية الحالية: {$stock->current_quantity}",
                $severity,
                route('subscriber.app.warehouses.stock', $stock->warehouse_id)
            );
        }
    }

    /**
     * Check and generate subscription alerts.
     */
    public function checkSubscriptionAlerts($storeId)
    {
        $subscription = Subscription::where('store_id', $storeId)->latest()->first();

        if (!$subscription) return;

        if ($subscription->status === 'expired') {
            $this->notify(
                $storeId,
                'subscription_expired',
                'انتهى الاشتراك',
                'اشتراكك منتهي الصلاحية. يرجى التجديد لمواصلة استخدام كافة الميزات.',
                'danger',
                route('subscriber.onboarding.plan-selection')
            );
        } elseif ($subscription->ends_at && $subscription->ends_at->isFuture() && $subscription->ends_at->diffInDays(now()) <= 7) {
            $days = $subscription->ends_at->diffInDays(now());
            $this->notify(
                $storeId,
                'subscription_expiring',
                'قرب انتهاء الاشتراك',
                "سينتهي اشتراكك خلال {$days} أيام. يرجى التجديد لضمان استمرارية الخدمة.",
                'warning',
                route('subscriber.onboarding.status')
            );
        }
    }

    /**
     * Check and generate usage limit alerts.
     */
    public function checkUsageAlerts($storeId)
    {
        $store = \App\Models\Store::with('subscription.plan')->find($storeId);
        if (!$store || !$store->subscription || !$store->subscription->plan) return;

        $plan = $store->subscription->plan;
        $counters = \App\Models\UsageCounter::where('store_id', $storeId)->get()->pluck('current_value', 'counter_key');

        $limits = [
            'max_products' => ['label' => 'المنتجات', 'key' => 'products', 'route' => route('subscriber.app.products.index')],
            'max_customers' => ['label' => 'العملاء', 'key' => 'customers', 'route' => route('subscriber.app.customers.index')],
            'max_suppliers' => ['label' => 'الموردين', 'key' => 'suppliers', 'route' => route('subscriber.app.suppliers.index')],
        ];

        foreach ($limits as $planKey => $meta) {
            $limitValue = $plan->getFeatureValue($planKey);
            if ($limitValue === -1 || $limitValue <= 0) continue; // Skip if unlimited or zero (to avoid division by zero)

            $currentValue = $counters->get($meta['key'], 0);
            $percentage = ($currentValue / $limitValue) * 100;

            if ($percentage >= 90) {
                $status = $percentage >= 100 ? 'تم بلوغ' : 'اقتربت من بلوغ';
                $severity = $percentage >= 100 ? 'danger' : 'warning';
                
                $this->notify(
                    $storeId,
                    "usage_limit_{$meta['key']}",
                    "تنبيه استهلاك: {$meta['label']}",
                    "{$status} الحد الأقصى المسموح به لـ {$meta['label']} ({$currentValue}/{$limitValue}).",
                    $severity,
                    $meta['route']
                );
            }
        }
    }
}
