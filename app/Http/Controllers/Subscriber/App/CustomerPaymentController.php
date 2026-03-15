<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreCustomerPaymentRequest;
use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\Cashbox;
use App\Services\CashboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPaymentController extends Controller
{
    protected $cashboxService;

    public function __construct(CashboxService $cashboxService)
    {
        $this->cashboxService = $cashboxService;
    }

    public function index()
    {
        $payments = CustomerPayment::with(['customer', 'cashbox'])->latest()->paginate(20);
        return view('subscriber.app.customer_payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $cashboxes = Cashbox::where('is_active', true)->get();
        return view('subscriber.app.customer_payments.create', compact('customers', 'cashboxes'));
    }

    public function store(StoreCustomerPaymentRequest $request)
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;

        $this->cashboxService->recordCustomerPayment([
            'store_id' => $storeId,
            'customer_id' => $request->customer_id,
            'cashbox_id' => $request->cashbox_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('subscriber.app.customer-payments.index')->with('success', 'تم تسجيل دفعة العميل بنجاح.');
    }

    public function show(CustomerPayment $customerPayment)
    {
        $customerPayment->load(['customer', 'cashbox', 'transaction']);
        return view('subscriber.app.customer_payments.show', compact('customerPayment'));
    }

    // Editing is disabled for financial integrity as per instructions.
}
