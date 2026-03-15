<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StorePOSRequest;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SalesInvoice;
use App\Services\SalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class POSController extends Controller
{
    protected $salesService;

    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    public function index()
    {
        $customers = Customer::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        
        // Find default warehouse or first one
        $defaultWarehouse = $warehouses->where('is_default', true)->first() ?? $warehouses->first();

        return view('subscriber.app.pos.index', compact('customers', 'warehouses', 'defaultWarehouse'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $warehouseId = $request->get('warehouse_id');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $storeId = Auth::guard('subscriber')->user()->store->id;

        $products = Product::where('store_id', $storeId)
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhere('barcode', 'LIKE', "%{$query}%");
            })
            ->with(['stocks' => function($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            }])
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sale_price' => (float) $product->sale_price,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'stock' => (float) ($product->stocks->first()->current_quantity ?? 0),
                ];
            });

        return response()->json($products);
    }

    public function store(StorePOSRequest $request)
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;

        try {
            $invoice = DB::transaction(function () use ($request, $storeId) {
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['quantity'] * $item['unit_price'];
                }

                $invoice = SalesInvoice::create([
                    'store_id' => $storeId,
                    'customer_id' => $request->customer_id,
                    'warehouse_id' => $request->warehouse_id,
                    'invoice_number' => $this->salesService->generateInvoiceNumber($storeId),
                    'invoice_date' => now(), // POS is always now
                    'status' => 'draft',
                    'subtotal' => $subtotal,
                    'discount_amount' => $request->discount_amount,
                    'total_amount' => $subtotal - $request->discount_amount,
                    'notes' => $request->notes,
                ]);

                foreach ($request->items as $item) {
                    $invoice->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'line_total' => $item['quantity'] * $item['unit_price'],
                    ]);
                }

                return $invoice;
            });

            // Auto-post for POS
            $this->salesService->post($invoice);

            return response()->json([
                'success' => true,
                'message' => 'تمت عملية البيع بنجاح.',
                'invoice_id' => $invoice->id,
                'redirect_url' => route('subscriber.app.sales.show', $invoice->id)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
