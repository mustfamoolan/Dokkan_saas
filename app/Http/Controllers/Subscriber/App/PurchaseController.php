<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StorePurchaseInvoiceRequest;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Product;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index()
    {
        $invoices = PurchaseInvoice::with(['supplier', 'warehouse'])->latest()->paginate(20);
        return view('subscriber.app.purchases.index', compact('invoices'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $invoiceNumber = $this->purchaseService->generateInvoiceNumber(Auth::guard('subscriber')->user()->store->id);

        return view('subscriber.app.purchases.create', compact('suppliers', 'warehouses', 'products', 'invoiceNumber'));
    }

    public function store(StorePurchaseInvoiceRequest $request)
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;

        $invoice = DB::transaction(function () use ($request, $storeId) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_cost'];
            }

            $invoice = PurchaseInvoice::create([
                'store_id' => $storeId,
                'supplier_id' => $request->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'invoice_number' => $this->purchaseService->generateInvoiceNumber($storeId),
                'invoice_date' => $request->invoice_date,
                'status' => 'draft', // Always draft initially to handle posting separately
                'subtotal' => $subtotal,
                'discount_amount' => $request->discount_amount,
                'total_amount' => $subtotal - $request->discount_amount,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $item['quantity'] * $item['unit_cost'],
                ]);
            }

            return $invoice;
        });

        if ($request->status === 'posted') {
            $this->purchaseService->post($invoice);
        }

        return redirect()->route('subscriber.app.purchases.index')->with('success', 'تم حفظ فاتورة الشراء بنجاح.');
    }

    public function show(PurchaseInvoice $purchase)
    {
        $purchase->load(['supplier', 'warehouse', 'items.product']);
        return view('subscriber.app.purchases.show', compact('purchase'));
    }

    public function edit(PurchaseInvoice $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->back()->with('error', 'لا يمكن تعديل الفواتير المعتمدة أو الملغاة.');
        }

        $suppliers = Supplier::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('subscriber.app.purchases.edit', compact('purchase', 'suppliers', 'warehouses', 'products'));
    }

    public function update(StorePurchaseInvoiceRequest $request, PurchaseInvoice $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->back()->with('error', 'لا يمكن تعديل الفواتير المعتمدة أو الملغاة.');
        }

        $storeId = Auth::guard('subscriber')->user()->store->id;

        DB::transaction(function () use ($request, $purchase) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_cost'];
            }

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'invoice_date' => $request->invoice_date,
                'subtotal' => $subtotal,
                'discount_amount' => $request->discount_amount,
                'total_amount' => $subtotal - $request->discount_amount,
                'notes' => $request->notes,
            ]);

            $purchase->items()->delete();
            foreach ($request->items as $item) {
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $item['quantity'] * $item['unit_cost'],
                ]);
            }
        });

        if ($request->status === 'posted') {
            $this->purchaseService->post($purchase);
        }

        return redirect()->route('subscriber.app.purchases.index')->with('success', 'تم تحديث فاتورة الشراء بنجاح.');
    }

    public function post(PurchaseInvoice $purchase)
    {
        if ($this->purchaseService->post($purchase)) {
            return redirect()->back()->with('success', 'تم اعتماد الفاتورة وتحديث المخزون.');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء اعتماد الفاتورة.');
    }

    public function cancel(PurchaseInvoice $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->back()->with('error', 'يمكن إلغاء الفواتير المسودة فقط.');
        }

        $purchase->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'تم إلغاء الفاتورة.');
    }
}
