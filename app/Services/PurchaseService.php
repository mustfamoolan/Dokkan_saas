<?php

namespace App\Services;

use App\Models\PurchaseInvoice;
use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseService
{
    /**
     * Post a purchase invoice and update stock.
     */
    public function post(PurchaseInvoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return false;
        }

        return DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $stock = ProductStock::firstOrCreate(
                    [
                        'store_id' => $invoice->store_id,
                        'warehouse_id' => $invoice->warehouse_id,
                        'product_id' => $item->product_id,
                    ],
                    [
                        'current_quantity' => 0,
                        'opening_quantity' => 0,
                    ]
                );

                $stock->increment('current_quantity', $item->quantity);
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
        $lastInvoice = PurchaseInvoice::where('store_id', $storeId)
            ->latest()
            ->first();

        $number = $lastInvoice ? (int) str_replace('PUR-', '', $lastInvoice->invoice_number) + 1 : 1;

        return 'PUR-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
