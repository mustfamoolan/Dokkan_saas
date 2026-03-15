<?php

namespace App\Services;

use App\Models\SalesInvoice;
use App\Models\ProductStock;
use App\Models\CashboxTransaction;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Post a sales invoice and reduce stock.
     */
    public function post(SalesInvoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            throw new Exception('يمكن فقط ترحيل الفواتير التي في حالة مسودة.');
        }

        $result = DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $stock = ProductStock::where([
                    'store_id' => $invoice->store_id,
                    'warehouse_id' => $invoice->warehouse_id,
                    'product_id' => $item->product_id,
                ])->lockForUpdate()->first();

                if (!$stock || $stock->current_quantity < $item->quantity) {
                    $productName = $item->product->name;
                    throw new Exception("الكمية غير كافية للمنتج: {$productName} في المستودع المختار.");
                }

                $stock->decrement('current_quantity', $item->quantity);
            }

            $invoice->update(['status' => 'posted']);

            return true;
        });

        // Check for stock alerts after successful post
        if ($result) {
            $this->notificationService->checkStockAlerts($invoice->store_id);
        }

        return $result;
    }

    /**
     * Generate a unique invoice number for the store.
     */
    public function generateInvoiceNumber($storeId)
    {
        $store = \App\Models\Store::find($storeId);
        $prefix = ($store && $store->config) ? $store->config->sales_prefix : 'SAL-';

        $lastInvoice = SalesInvoice::where('store_id', $storeId)
            ->where('invoice_number', 'like', $prefix . '%')
            ->latest()
            ->first();

        if ($lastInvoice) {
            $number = (int) str_replace($prefix, '', $lastInvoice->invoice_number) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
