<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Models\UsageCounter;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $metrics = $this->reportService->getSummaryMetrics();
        $counters = UsageCounter::all()->pluck('current_value', 'counter_key');
        
        return view('subscriber.app.dashboard', compact('counters', 'metrics'));
    }
}
