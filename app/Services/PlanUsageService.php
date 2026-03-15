<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Subscription;
use App\Models\UsageCounter;
use App\Models\UsageLimitOverride;
use App\Models\PlanFeature;

class PlanUsageService
{
    /**
     * Get the final limit for a specific feature key.
     * Checks for overrides first, then falls back to the active plan.
     */
    public function getLimit(Store $store, string $featureKey)
    {
        // 1. Check for manual override
        $override = UsageLimitOverride::where('store_id', $store->id)
            ->where('feature_key', $featureKey)
            ->first();

        if ($override) {
            return $this->castValue($override->override_value, $override->value_type);
        }

        // 2. Get from active subscription plan
        $subscription = $store->subscriber->payments() // Simplified: assuming subscriber has one store or we link store to sub
            // Wait, Subscriber has store. Let's use Subscription linked to store.
            ? Subscription::where('store_id', $store->id)
                ->whereIn('status', ['active', 'trial'])
                ->with('plan.features')
                ->first()
            : null;

        if ($subscription && $subscription->plan) {
            $feature = $subscription->plan->features->where('feature_key', $featureKey)->first();
            if ($feature) {
                return $this->castValue($feature->feature_value, $feature->value_type);
            }
        }

        return null;
    }

    /**
     * Get current usage for a counter key.
     */
    public function getUsage(Store $store, string $counterKey): int
    {
        $counter = UsageCounter::where('store_id', $store->id)
            ->where('counter_key', $counterKey)
            ->first();

        return $counter ? $counter->current_value : 0;
    }

    /**
     * Check if an action is allowed based on limits.
     */
    public function isAllowed(Store $store, string $key): bool
    {
        $limit = $this->getLimit($store, $key);

        // If it's a boolean feature
        if (is_bool($limit)) {
            return $limit;
        }

        // If it's a numeric limit
        if (is_numeric($limit)) {
            $usage = $this->getUsage($store, $this->mapLimitToCounter($key));
            return $usage < $limit;
        }

        return false;
    }

    /**
     * Get comprehensive status for a feature/limit.
     */
    public function getDetailedStatus(Store $store, string $key): array
    {
        // 1. Check override
        $override = UsageLimitOverride::where('store_id', $store->id)
            ->where('feature_key', $key)
            ->first();

        // 2. Check Plan
        $subscription = Subscription::where('store_id', $store->id)
            ->whereIn('status', ['active', 'trial'])
            ->with('plan.features')
            ->first();
        
        $planFeature = $subscription ? $subscription->plan->features->where('feature_key', $key)->first() : null;

        $finalLimit = null;
        $source = 'none';

        if ($override) {
            $finalLimit = $this->castValue($override->override_value, $override->value_type);
            $source = 'override';
        } elseif ($planFeature) {
            $finalLimit = $this->castValue($planFeature->feature_value, $planFeature->value_type);
            $source = 'plan';
        }

        $usage = is_numeric($finalLimit) ? $this->getUsage($store, $this->mapLimitToCounter($key)) : null;

        return [
            'key' => $key,
            'limit' => $finalLimit,
            'usage' => $usage,
            'remaining' => is_numeric($finalLimit) ? max(0, $finalLimit - $usage) : null,
            'allowed' => is_bool($finalLimit) ? $finalLimit : (is_numeric($finalLimit) ? $usage < $finalLimit : false),
            'source' => $source,
            'type' => is_bool($finalLimit) ? 'boolean' : (is_numeric($finalLimit) ? 'limit' : 'unknown')
        ];
    }

    private function castValue($value, $type)
    {
        if ($type === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        if ($type === 'limit' || is_numeric($value)) {
            return (int) $value;
        }
        return $value;
    }

    private function mapLimitToCounter(string $limitKey): string
    {
        $map = [
            'max_users' => 'users_count',
            'max_branches' => 'branches_count',
            'max_products' => 'products_count',
            'max_customers' => 'customers_count',
            'max_suppliers' => 'suppliers_count',
            'max_representatives' => 'representatives_count',
            'max_warehouses' => 'warehouses_count',
            'max_invoices_per_month' => 'invoices_this_month',
            'max_orders_per_month' => 'orders_this_month',
            'max_storage_mb' => 'storage_used_mb',
        ];

        return $map[$limitKey] ?? $limitKey;
    }
}
