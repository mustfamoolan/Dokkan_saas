<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\UsageCounter;
use App\Services\NotificationService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $reportService;
    protected $notificationService;
    protected $planUsageService;

    public function __construct(
        ReportService $reportService, 
        NotificationService $notificationService,
        \App\Services\PlanUsageService $planUsageService
    ) {
        $this->reportService = $reportService;
        $this->notificationService = $notificationService;
        $this->planUsageService = $planUsageService;
    }

    public function index()
    {
        $subscriber = Auth::guard('subscriber')->user();
        $store = $subscriber->store;
        $storeId = $store->id;
        
        // Trigger alerts check
        $this->notificationService->checkSubscriptionAlerts($storeId);
        $this->notificationService->checkStockAlerts($storeId);
        $this->notificationService->checkUsageAlerts($storeId);
        
        // Get statistics and limits
        $metrics = [
            'products' => $this->planUsageService->getDetailedStatus($store, 'max_products'),
            'customers' => $this->planUsageService->getDetailedStatus($store, 'max_customers'),
            'invoices' => $this->planUsageService->getDetailedStatus($store, 'max_invoices_per_month'),
            'users' => $this->planUsageService->getDetailedStatus($store, 'max_users'),
        ];

        $summary = $this->reportService->getSummaryMetrics();
        $counters = UsageCounter::where('store_id', $storeId)->get()->pluck('current_value', 'counter_key');
        
        $subscription = $store->subscription; // Uses our new helper

        return view('subscriber.app.dashboard', compact('counters', 'metrics', 'summary', 'subscription', 'store'));
    }
}
