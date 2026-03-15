<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreSupplierRequest;
use App\Models\Supplier;
use App\Models\UsageCounter;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    protected $usageService;

    public function __construct(PlanUsageService $usageService)
    {
        $this->usageService = $usageService;
    }

    public function index()
    {
        $suppliers = Supplier::latest()->paginate(20);
        return view('subscriber.app.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $store = Auth::guard('subscriber')->user()->store;
        
        if (!$this->usageService->isAllowed($store, 'max_suppliers')) {
            return redirect()->route('subscriber.app.suppliers.index')
                ->with('error', 'لقد وصلت للحد الأقصى للموردين المسموح بهم في باقتك الحالية.');
        }

        return view('subscriber.app.suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $store = Auth::guard('subscriber')->user()->store;

        if (!$this->usageService->isAllowed($store, 'max_suppliers')) {
            return redirect()->route('subscriber.app.suppliers.index')
                ->with('error', 'لقد وصلت للحد الأقصى للموردين المسموح بهم في باقتك الحالية.');
        }

        Supplier::create($request->validated());

        $this->updateCounter($store);

        return redirect()->route('subscriber.app.suppliers.index')->with('success', 'تم إضافة المورد بنجاح.');
    }

    public function show(Supplier $supplier)
    {
        return view('subscriber.app.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('subscriber.app.suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        return redirect()->route('subscriber.app.suppliers.index')->with('success', 'تم تحديث بيانات المورد بنجاح.');
    }

    public function destroy(Supplier $supplier)
    {
        $store = Auth::guard('subscriber')->user()->store;
        $supplier->delete();

        $this->updateCounter($store);

        return redirect()->route('subscriber.app.suppliers.index')->with('success', 'تم حذف المورد بنجاح.');
    }

    protected function updateCounter($store)
    {
        $count = Supplier::count();
        UsageCounter::updateOrCreate(
            ['store_id' => $store->id, 'counter_key' => 'suppliers_count'],
            ['current_value' => $count]
        );
    }
}
