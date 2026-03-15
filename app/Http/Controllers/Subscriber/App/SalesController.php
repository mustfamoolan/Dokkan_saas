<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreSalesInvoiceRequest;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Product;
use App\Services\SalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesController extends Controller
{
    protected $salesService;

    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    public function index()
    {
        $invoices = SalesInvoice::with(['customer', 'warehouse'])->latest()->paginate(20);
        return view('subscriber.app.sales.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $invoiceNumber = $this->salesService->generateInvoiceNumber(Auth::guard('subscriber')->user()->store->id);

        return view('subscriber.app.sales.create', compact('customers', 'warehouses', 'products', 'invoiceNumber'));
    }

    public function store(StoreSalesInvoiceRequest $request)
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
                    'invoice_date' => $request->invoice_date,
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

            if ($request->status === 'posted') {
                $this->salesService->post($invoice);
            }

            return redirect()->route('subscriber.app.sales.index')->with('success', 'تم حفظ فاتورة البيع بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(SalesInvoice $sale)
    {
        $sale->load(['customer', 'warehouse', 'items.product']);
        return view('subscriber.app.sales.show', compact('sale'));
    }

    public function edit(SalesInvoice $sale)
    {
        if ($sale->status !== 'draft') {
            return redirect()->back()->with('error', 'لا يمكن تعديل الفواتير المعتمدة أو الملغاة.');
        }

        $customers = Customer::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('subscriber.app.sales.edit', compact('sale', 'customers', 'warehouses', 'products'));
    }

    public function update(StoreSalesInvoiceRequest $request, SalesInvoice $sale)
    {
        if ($sale->status !== 'draft') {
            return redirect()->back()->with('error', 'لا يمكن تعديل الفواتير المعتمدة أو الملغاة.');
        }

        try {
            DB::transaction(function () use ($request, $sale) {
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['quantity'] * $item['unit_price'];
                }

                $sale->update([
                    'customer_id' => $request->customer_id,
                    'warehouse_id' => $request->warehouse_id,
                    'invoice_date' => $request->invoice_date,
                    'subtotal' => $subtotal,
                    'discount_amount' => $request->discount_amount,
                    'total_amount' => $subtotal - $request->discount_amount,
                    'notes' => $request->notes,
                ]);

                $sale->items()->delete();
                foreach ($request->items as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'line_total' => $item['quantity'] * $item['unit_price'],
                    ]);
                }
            });

            if ($request->status === 'posted') {
                $this->salesService->post($sale);
            }

            return redirect()->route('subscriber.app.sales.index')->with('success', 'تم تحديث فاتورة البيع بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function post(SalesInvoice $sale)
    {
        try {
            $this->salesService->post($sale);
            return redirect()->back()->with('success', 'تم اعتماد الفاتورة وتحديث المخزون.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel(SalesInvoice $sale)
    {
        if ($sale->status !== 'draft') {
            return redirect()->back()->with('error', 'يمكن إلغاء الفواتير المسودة فقط.');
        }

        $sale->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'تم إلغاء الفاتورة.');
    }
}
