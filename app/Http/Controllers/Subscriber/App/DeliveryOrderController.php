<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeliveryOrder;
use App\Models\Representative;
use App\Models\SalesInvoice;
use App\Services\DeliveryService;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryOrderController extends Controller
{
    protected $deliveryService;
    protected $usageService;

    public function __construct(DeliveryService $deliveryService, PlanUsageService $usageService)
    {
        $this->deliveryService = $deliveryService;
        $this->usageService = $usageService;
    }

    public function index(Request $request)
    {
        $query = DeliveryOrder::with(['customer', 'representative', 'invoice']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);
        return view('subscriber.app.orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $store = Auth::guard('subscriber')->user()->store;
        
        // Check monthly limit
        if (!$this->usageService->isAllowed($store, 'max_orders_per_month')) {
            return redirect()->route('subscriber.app.orders.index')
                ->with('error', 'لقد وصلت للحد الأقصى لطلبات التوصيل الشهرية المسموح بها في باقتك.');
        }

        $customers = Customer::where('is_active', true)->get();
        $representatives = Representative::where('is_active', true)->get();
        
        $selectedInvoice = null;
        if ($request->has('invoice_id')) {
            $selectedInvoice = SalesInvoice::find($request->invoice_id);
        }

        return view('subscriber.app.orders.create', compact('customers', 'representatives', 'selectedInvoice'));
    }

    public function store(Request $request)
    {
        $store = Auth::guard('subscriber')->user()->store;
        
        if (!$this->usageService->isAllowed($store, 'max_orders_per_month')) {
            return redirect()->route('subscriber.app.orders.index')
                ->with('error', 'لقد وصلت للحد الأقصى لطلبات التوصيل الشهرية المسموح بها في باقتك.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_invoice_id' => 'nullable|exists:sales_invoices,id',
            'representative_id' => 'nullable|exists:representatives,id',
            'order_number' => 'required|string|unique:delivery_orders,order_number',
            'order_date' => 'required|date',
            'delivery_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order = $this->deliveryService->createOrder($validated);

        return redirect()->route('subscriber.app.orders.index')
            ->with('success', 'تم إنشاء طلب التوصيل بنجاح.');
    }

    public function show(DeliveryOrder $order)
    {
        $order->load(['customer', 'representative', 'invoice']);
        $representatives = Representative::where('is_active', true)->get();
        return view('subscriber.app.orders.show', compact('order', 'representatives'));
    }

    public function edit(DeliveryOrder $order)
    {
        $customers = Customer::where('is_active', true)->get();
        $representatives = Representative::where('is_active', true)->get();
        return view('subscriber.app.orders.edit', compact('order', 'customers', 'representatives'));
    }

    public function update(Request $request, DeliveryOrder $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_invoice_id' => 'nullable|exists:sales_invoices,id',
            'representative_id' => 'nullable|exists:representatives,id',
            'order_number' => 'required|string|unique:delivery_orders,order_number,' . $order->id,
            'order_date' => 'required|date',
            'status' => 'required|in:new,preparing,out_for_delivery,delivered,cancelled',
            'delivery_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $oldRepId = $order->representative_id;
        $order->update($validated);

        if ($validated['representative_id'] && $validated['representative_id'] != $oldRepId) {
            $this->deliveryService->assignRepresentative($order, $validated['representative_id']);
        }

        if ($validated['status'] == 'delivered' && !$order->delivered_at) {
            $this->deliveryService->updateStatus($order, 'delivered');
        }

        return redirect()->route('subscriber.app.orders.index')
            ->with('success', 'تم تحديث طلب التوصيل بنجاح.');
    }

    public function updateStatus(Request $request, DeliveryOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,preparing,out_for_delivery,delivered,cancelled',
        ]);

        $this->deliveryService->updateStatus($order, $validated['status']);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }

    public function assign(Request $request, DeliveryOrder $order)
    {
        $validated = $request->validate([
            'representative_id' => 'required|exists:representatives,id',
        ]);

        $this->deliveryService->assignRepresentative($order, $validated['representative_id']);

        return back()->with('success', 'تم إسناد الطلب للمندوب بنجاح.');
    }
}
