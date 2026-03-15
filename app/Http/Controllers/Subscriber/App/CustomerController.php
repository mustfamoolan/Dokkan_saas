<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\UsageCounter;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    protected $usageService;

    public function __construct(PlanUsageService $usageService)
    {
        $this->usageService = $usageService;
    }

    public function index()
    {
        $customers = Customer::latest()->paginate(20);
        return view('subscriber.app.customers.index', compact('customers'));
    }

    public function create()
    {
        $store = Auth::guard('subscriber')->user()->store;
        
        if (!$this->usageService->isAllowed($store, 'max_customers')) {
            return redirect()->route('subscriber.app.customers.index')
                ->with('error', 'لقد وصلت للحد الأقصى للعملاء المسموح بهم في باقتك الحالية.');
        }

        return view('subscriber.app.customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $store = Auth::guard('subscriber')->user()->store;

        if (!$this->usageService->isAllowed($store, 'max_customers')) {
            return redirect()->route('subscriber.app.customers.index')
                ->with('error', 'لقد وصلت للحد الأقصى للعملاء المسموح بهم في باقتك الحالية.');
        }

        Customer::create($request->validated());

        $this->updateCounter($store);

        return redirect()->route('subscriber.app.customers.index')->with('success', 'تم إضافة العميل بنجاح.');
    }

    public function show(Customer $customer)
    {
        return view('subscriber.app.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('subscriber.app.customers.edit', compact('customer'));
    }

    public function update(StoreCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return redirect()->route('subscriber.app.customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    public function destroy(Customer $customer)
    {
        $store = Auth::guard('subscriber')->user()->store;
        $customer->delete();

        $this->updateCounter($store);

        return redirect()->route('subscriber.app.customers.index')->with('success', 'تم حذف العميل بنجاح.');
    }

    protected function updateCounter($store)
    {
        $count = Customer::count();
        UsageCounter::updateOrCreate(
            ['store_id' => $store->id, 'counter_key' => 'customers_count'],
            ['current_value' => $count]
        );
    }
}
