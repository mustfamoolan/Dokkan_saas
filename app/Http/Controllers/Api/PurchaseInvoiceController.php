<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        return PurchaseInvoice::with(['items.product', 'supplier'])->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|unique:purchase_invoices,invoice_number',
            'real_invoice_number' => 'nullable|string',
            'invoice_date' => 'required|date',
            'driver_cost' => 'numeric',
            'workers_cost' => 'numeric',
            'costs_on_supplier' => 'boolean',
            'total_amount' => 'numeric',
            'items' => 'required|array',
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Create Invoice
            $invoice = PurchaseInvoice::create($validated);

            // 2. Process Items
            foreach ($validated['items'] as $itemData) {
                // Save Item
                $item = $invoice->items()->create([
                    'product_id' => $itemData['product_id'],
                    'cartons' => $itemData['cartons'],
                    'units_per_carton' => $itemData['units_per_carton'],
                    'purchase_price' => $itemData['purchase_price'],
                    'cost_per_carton' => $itemData['cost_per_carton'],
                    'retail_price' => $itemData['retail_price'],
                    'wholesale_price' => $itemData['wholesale_price'],
                    'is_gift' => $itemData['is_gift'] ?? false,
                ]);

                // Update Stock
                $stock = ProductStock::firstOrCreate(
                    ['product_id' => $itemData['product_id']],
                    ['cartons' => 0, 'extra_units' => 0, 'total_units' => 0]
                );

                $addedUnits = $itemData['cartons'] * $itemData['units_per_carton'];
                $stock->increment('cartons', $itemData['cartons']);
                $stock->increment('total_units', $addedUnits);

                // Update Prices
                ProductPrice::create([
                    'product_id' => $itemData['product_id'],
                    'cost' => $itemData['purchase_price'],
                    'retail_price' => $itemData['retail_price'],
                    'wholesale_price' => $itemData['wholesale_price'],
                    'additional_costs' => $itemData['cost_per_carton'] - $itemData['purchase_price'],
                ]);
            }

            return response()->json($invoice->load('items'), 201);
        });
    }
}
