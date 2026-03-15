<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreWarehouseRequest;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Warehouse;
use App\Models\UsageCounter;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    protected $usageService;

    public function __construct(PlanUsageService $usageService)
    {
        $this->usageService = $usageService;
    }

    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(20);
        return view('subscriber.app.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        $store = Auth::guard('subscriber')->user()->store;
        
        if (!$this->usageService->isAllowed($store, 'max_warehouses')) {
            return redirect()->route('subscriber.app.warehouses.index')
                ->with('error', 'لقد وصلت للحد الأقصى للمستودعات المسموح بها في باقتك الحالية.');
        }

        return view('subscriber.app.warehouses.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        $store = Auth::guard('subscriber')->user()->store;

        if (!$this->usageService->isAllowed($store, 'max_warehouses')) {
            return redirect()->route('subscriber.app.warehouses.index')
                ->with('error', 'لقد وصلت للحد الأقصى للمستودعات المسموح بها في باقتك الحالية.');
        }

        DB::transaction(function () use ($request, $store) {
            $warehouse = Warehouse::create($request->validated());

            // If this is the first warehouse, or marked as default, 
            // we might want to initialize stocks for existing products.
            // For simplicity, we'll initialize stock for all products if it's the first one.
            if (Warehouse::count() === 1) {
                $products = Product::all();
                foreach ($products as $product) {
                    ProductStock::create([
                        'store_id' => $store->id,
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id,
                        'current_quantity' => $product->quantity, // Transitioning quantity
                        'opening_quantity' => $product->quantity,
                    ]);
                }
            }

            $this->updateCounter($store);
        });

        return redirect()->route('subscriber.app.warehouses.index')->with('success', 'تم إضافة المستودع بنجاح.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('subscriber.app.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('subscriber.app.warehouses.edit', compact('warehouse'));
    }

    public function update(StoreWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated());
        return redirect()->route('subscriber.app.warehouses.index')->with('success', 'تم تحديث بيانات المستودع بنجاح.');
    }

    public function stock(Warehouse $warehouse)
    {
        $stocks = ProductStock::where('warehouse_id', $warehouse->id)
            ->with(['product', 'product.category'])
            ->paginate(20);

        return view('subscriber.app.warehouses.stock', compact('warehouse', 'stocks'));
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->is_default) {
            return redirect()->back()->with('error', 'لا يمكن حذف المستودع الافتراضي.');
        }

        $store = Auth::guard('subscriber')->user()->store;
        $warehouse->delete();

        $this->updateCounter($store);

        return redirect()->route('subscriber.app.warehouses.index')->with('success', 'تم حذف المستودع بنجاح.');
    }

    protected function updateCounter($store)
    {
        $count = Warehouse::count();
        UsageCounter::updateOrCreate(
            ['store_id' => $store->id, 'counter_key' => 'warehouses_count'],
            ['current_value' => $count]
        );
    }
}
