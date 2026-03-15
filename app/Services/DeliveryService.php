<?php

namespace App\Services;

use App\Models\DeliveryOrder;
use App\Models\UsageCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeliveryService
{
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $order = DeliveryOrder::create($data);
            
            if (!empty($data['representative_id'])) {
                $order->assigned_at = now();
                $order->save();
            }

            $this->updateMonthlyCounter($order->store_id);
            
            return $order;
        });
    }

    public function assignRepresentative(DeliveryOrder $order, $representativeId)
    {
        $order->representative_id = $representativeId;
        if (!$order->assigned_at) {
            $order->assigned_at = now();
        }
        $order->save();
        
        return $order;
    }

    public function updateStatus(DeliveryOrder $order, string $status)
    {
        $order->status = $status;
        
        if ($status === 'delivered') {
            $order->delivered_at = now();
        }

        $order->save();
        return $order;
    }

    protected function updateMonthlyCounter($storeId)
    {
        $count = DeliveryOrder::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        UsageCounter::updateOrCreate(
            ['store_id' => $storeId, 'counter_key' => 'orders_this_month'],
            ['current_value' => $count]
        );
    }
}
