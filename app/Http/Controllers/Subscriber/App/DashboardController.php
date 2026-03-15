<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PlanUsageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $usageService;

    public function __construct(PlanUsageService $usageService)
    {
        $this->usageService = $usageService;
    }

    public function index()
    {
        $subscriber = Auth::guard('subscriber')->user();
        $store = $subscriber->store;
        $subscription = Subscription::where('store_id', $store->id)
            ->whereIn('status', ['active', 'trial'])
            ->with('plan.features')
            ->latest()
            ->first();

        $metrics = [
            'products' => $this->usageService->getDetailedStatus($store, 'max_products'),
            'customers' => $this->usageService->getDetailedStatus($store, 'max_customers'),
            'invoices' => $this->usageService->getDetailedStatus($store, 'max_invoices_per_month'),
            'users' => $this->usageService->getDetailedStatus($store, 'max_users'),
        ];

        return view('subscriber.app.dashboard', compact('subscriber', 'store', 'subscription', 'metrics'));
    }
}
