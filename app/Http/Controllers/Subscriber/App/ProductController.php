<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UsageCounter;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $usageService;

    public function __construct(PlanUsageService $usageService)
    {
        $this->usageService = $usageService;
    }

    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('subscriber.app.products.index', compact('products'));
    }

    public function create()
    {
        // Plan Enforcement Check
        if (!$this->usageService->isAllowed(Auth::guard('subscriber')->user()->store, 'max_products')) {
            return redirect()->route('subscriber.app.products.index')
                ->with('error', 'لقد وصلت للحد الأقصى للمنتجات المسموح بها في باقتك الحالية.');
        }

        $categories = ProductCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('subscriber.app.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $store = Auth::guard('subscriber')->user()->store;

        // Plan Enforcement Check (Double check on store)
        if (!$this->usageService->isAllowed($store, 'max_products')) {
            return redirect()->route('subscriber.app.products.index')
                ->with('error', 'لقد وصلت للحد الأقصى للمنتجات المسموح بها في باقتك الحالية.');
        }

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        // Update Usage Counter
        $this->updateCounter($store);

        return redirect()->route('subscriber.app.products.index')->with('success', 'تم إضافة المنتج بنجاح.');
    }

    public function show(Product $product)
    {
        return view('subscriber.app.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('subscriber.app.products.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::Disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('subscriber.app.products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product)
    {
        $store = Auth::guard('subscriber')->user()->store;
        
        if ($product->image) {
            Storage::Disk('public')->delete($product->image);
        }
        $product->delete();

        // Update Usage Counter
        $this->updateCounter($store);

        return redirect()->route('subscriber.app.products.index')->with('success', 'تم حذف المنتج بنجاح.');
    }

    protected function updateCounter($store)
    {
        $count = Product::count(); // Scoped by global scope already
        UsageCounter::updateOrCreate(
            ['store_id' => $store->id, 'counter_key' => 'products_count'],
            ['current_value' => $count]
        );
    }
}
