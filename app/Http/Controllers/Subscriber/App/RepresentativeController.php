<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\UsageCounter;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RepresentativeController extends Controller
{
    protected $usageService;

    public function __construct(PlanUsageService $usageService)
    {
        $this->usageService = $usageService;
    }

    public function index()
    {
        $representatives = Representative::latest()->paginate(20);
        return view('subscriber.app.representatives.index', compact('representatives'));
    }

    public function create()
    {
        $store = Auth::guard('subscriber')->user()->store;
        if (!$this->usageService->isAllowed($store, 'max_representatives')) {
            return redirect()->route('subscriber.app.representatives.index')
                ->with('error', 'لقد وصلت للحد الأقصى للمناديب المسموح بهم في باقتك الحالية.');
        }

        return view('subscriber.app.representatives.create');
    }

    public function store(Request $request)
    {
        $store = Auth::guard('subscriber')->user()->store;
        if (!$this->usageService->isAllowed($store, 'max_representatives')) {
            return redirect()->route('subscriber.app.representatives.index')
                ->with('error', 'لقد وصلت للحد الأقصى للمناديب المسموح بهم في باقتك الحالية.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:representatives,phone',
            'email' => 'nullable|email|max:255',
            'commission_type' => 'nullable|in:fixed,percentage',
            'commission_value' => 'nullable|numeric|min:0',
            'password' => 'required|string|min:6|confirmed',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['store_id'] = $store->id; // Enforce store_id from server

        if (!isset($validated['is_active'])) {
             $validated['is_active'] = false;
        }

        Representative::create($validated);
        
        $this->updateCounter($store);

        return redirect()->route('subscriber.app.representatives.index')
            ->with('success', 'تم إضافة المندوب بنجاح.');
    }

    public function show(Representative $representative)
    {
        return view('subscriber.app.representatives.show', compact('representative'));
    }

    public function edit(Representative $representative)
    {
        return view('subscriber.app.representatives.edit', compact('representative'));
    }

    public function update(Request $request, Representative $representative)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:representatives,phone,' . $representative->id,
            'email' => 'nullable|email|max:255',
            'commission_type' => 'nullable|in:fixed,percentage',
            'commission_value' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
            'password' => 'nullable|string|min:6|confirmed',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $representative->update($validated);

        return redirect()->route('subscriber.app.representatives.index')
            ->with('success', 'تم تحديث بيانات المندوب بنجاح.');
    }

    public function destroy(Representative $representative)
    {
        $store = Auth::guard('subscriber')->user()->store;
        $representative->delete();
        $this->updateCounter($store);

        return redirect()->route('subscriber.app.representatives.index')
            ->with('success', 'تم حذف المندوب بنجاح.');
    }

    protected function updateCounter($store)
    {
        $count = Representative::count();
        UsageCounter::updateOrCreate(
            ['store_id' => $store->id, 'counter_key' => 'representatives_count'],
            ['current_value' => $count]
        );
    }
}
