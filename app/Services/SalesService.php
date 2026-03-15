<?php

namespace App\Services;

use App\Models\SalesInvoice;
use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesService
{
    /**
     * Post a sales invoice and reduce stock.
     */
    public function post(SalesInvoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            throw new Exception('يمكن فقط ترحيل الفواتير التي في حالة مسودة.');
        }

        return DB::transaction(function () use ($invoice) {
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
    }

    /**
     * Generate a unique invoice number for the store.
     */
    public function generateInvoiceNumber($storeId)
    {
        $lastInvoice = SalesInvoice::where('store_id', $storeId)
            ->latest()
            ->first();

        $number = $lastInvoice ? (int) str_replace('SAL-', '', $lastInvoice->invoice_number) + 1 : 1;

        return 'SAL-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
