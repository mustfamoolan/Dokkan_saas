<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\StatementService;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;
    protected $statementService;

    public function __construct(ReportService $reportService, StatementService $statementService)
    {
        $this->reportService = $reportService;
        $this->statementService = $statementService;
    }

    /**
     * Reports Dashboard (Overview)
     */
    public function index(Request $request)
    {
        $range = $this->getDateRange($request);
        $metrics = $this->reportService->getSummaryMetrics($range['start'], $range['end']);
        
        return view('subscriber.app.reports.index', compact('metrics', 'range'));
    }

    public function sales(Request $request)
    {
        $range = $this->getDateRange($request);
        $data = $this->reportService->getSalesReport($range['start'], $range['end']);
        return view('subscriber.app.reports.sales', compact('data', 'range'));
    }

    public function purchases(Request $request)
    {
        $range = $this->getDateRange($request);
        $data = $this->reportService->getPurchaseReport($range['start'], $range['end']);
        return view('subscriber.app.reports.purchases', compact('data', 'range'));
    }

    public function inventory(Request $request)
    {
        $warehouseId = $request->query('warehouse_id');
        $data = $this->reportService->getInventoryReport($warehouseId);
        return view('subscriber.app.reports.inventory', compact('data', 'warehouseId'));
    }

    public function customers()
    {
        $customers = Customer::where('is_active', true)->get();
        $data = $customers->map(function($customer) {
            return [
                'customer' => $customer,
                'balance' => $this->statementService->getCustomerBalance($customer)
            ];
        });
        return view('subscriber.app.reports.customers', compact('data'));
    }

    public function suppliers()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $data = $suppliers->map(function($supplier) {
            return [
                'supplier' => $supplier,
                'balance' => $this->statementService->getSupplierBalance($supplier)
            ];
        });
        return view('subscriber.app.reports.suppliers', compact('data'));
    }

    public function cashboxes()
    {
        $data = $this->reportService->getFinancialSummary();
        return view('subscriber.app.reports.cashboxes', compact('data'));
    }

    public function expenses(Request $request)
    {
        $range = $this->getDateRange($request);
        $data = $this->reportService->getExpenseReport($range['start'], $range['end']);
        return view('subscriber.app.reports.expenses', compact('data', 'range'));
    }

    /**
     * Helper to determine date range from request.
     */
    private function getDateRange(Request $request)
    {
        $filter = $request->get('filter', 'month');
        $start = null;
        $end = Carbon::now();

        switch ($filter) {
            case 'today':
                $start = Carbon::today();
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek();
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                } else {
                    $start = Carbon::now()->startOfMonth();
                }
                break;
            case 'month':
            default:
                $start = Carbon::now()->startOfMonth();
                break;
        }

        return [
            'start' => $start,
            'end' => $end,
            'filter' => $filter
        ];
    }
}
