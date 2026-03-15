<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreSupplierPaymentRequest;
use App\Models\SupplierPayment;
use App\Models\Supplier;
use App\Models\Cashbox;
use App\Services\CashboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class SupplierPaymentController extends Controller
{
    protected $cashboxService;

    public function __construct(CashboxService $cashboxService)
    {
        $this->cashboxService = $cashboxService;
    }

    public function index()
    {
        $payments = SupplierPayment::with(['supplier', 'cashbox'])->latest()->paginate(20);
        return view('subscriber.app.supplier_payments.index', compact('payments'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $cashboxes = Cashbox::where('is_active', true)->get();
        return view('subscriber.app.supplier_payments.create', compact('suppliers', 'cashboxes'));
    }

    public function store(StoreSupplierPaymentRequest $request)
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;

        try {
            $this->cashboxService->recordSupplierPayment([
                'store_id' => $storeId,
                'supplier_id' => $request->supplier_id,
                'cashbox_id' => $request->cashbox_id,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
            ]);

            return redirect()->route('subscriber.app.supplier-payments.index')->with('success', 'تم تسجيل سند الصرف بنجاح.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(SupplierPayment $supplierPayment)
    {
        $supplierPayment->load(['supplier', 'cashbox', 'transaction']);
        return view('subscriber.app.supplier_payments.show', compact('supplierPayment'));
    }

    // Editing is disabled for financial integrity as per instructions.
}
