<?php

namespace App\Services;

use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Expense;
use App\Models\Cashbox;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get summary metrics for a given date range.
     */
    public function getSummaryMetrics($start = null, $end = null)
    {
        $start = $start ? Carbon::parse($start)->startOfDay() : now()->startOfMonth();
        $end = $end ? Carbon::parse($end)->endOfDay() : now()->endOfDay();

        return [
            'total_sales' => SalesInvoice::where('status', 'posted')->whereBetween('invoice_date', [$start, $end])->sum('total_amount'),
            'sales_count' => SalesInvoice::where('status', 'posted')->whereBetween('invoice_date', [$start, $end])->count(),
            
            'total_purchases' => PurchaseInvoice::where('status', 'posted')->whereBetween('invoice_date', [$start, $end])->sum('total_amount'),
            'purchases_count' => PurchaseInvoice::where('status', 'posted')->whereBetween('invoice_date', [$start, $end])->count(),
            
            'total_expenses' => Expense::whereBetween('expense_date', [$start, $end])->sum('amount'),
            'expenses_count' => Expense::whereBetween('expense_date', [$start, $end])->count(),
            
            'products_count' => Product::count(),
            'customers_count' => Customer::count(),
            'suppliers_count' => Supplier::count(),
            'cashboxes_count' => Cashbox::count(),
            
            'total_cashbox_balance' => Cashbox::sum('current_balance'),
        ];
    }

    /**
     * Get Sales report data.
     */
    public function getSalesReport($start, $end)
    {
        $query = SalesInvoice::with('customer')
            ->where('status', 'posted')
            ->whereBetween('invoice_date', [$start, $end]);

        return [
            'total_amount' => (clone $query)->sum('total_amount'),
            'invoice_count' => (clone $query)->count(),
            'invoices' => (clone $query)->latest('invoice_date')->get(),
            'top_customers' => SalesInvoice::where('status', 'posted')
                ->whereBetween('invoice_date', [$start, $end])
                ->select('customer_id', DB::raw('SUM(total_amount) as total'))
                ->groupBy('customer_id')
                ->with('customer')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Get Purchase report data.
     */
    public function getPurchaseReport($start, $end)
    {
        $query = PurchaseInvoice::with('supplier')
            ->where('status', 'posted')
            ->whereBetween('invoice_date', [$start, $end]);

        return [
            'total_amount' => (clone $query)->sum('total_amount'),
            'invoice_count' => (clone $query)->count(),
            'invoices' => (clone $query)->latest('invoice_date')->get(),
            'top_suppliers' => PurchaseInvoice::where('status', 'posted')
                ->whereBetween('invoice_date', [$start, $end])
                ->select('supplier_id', DB::raw('SUM(total_amount) as total'))
                ->groupBy('supplier_id')
                ->with('supplier')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Get Inventory report data.
     */
    public function getInventoryReport($warehouseId = null)
    {
        $stockQuery = ProductStock::with(['product', 'warehouse']);
        
        if ($warehouseId) {
            $stockQuery->where('warehouse_id', $warehouseId);
        }

        $stocks = $stockQuery->get();

        return [
            'total_products' => Product::count(),
            'low_stock' => $stocks->filter(fn($s) => $s->quantity > 0 && $s->quantity <= 10), // Example threshold
            'out_of_stock' => $stocks->filter(fn($s) => $s->quantity <= 0),
            'all_stocks' => $stocks,
            'warehouses' => Warehouse::all(),
        ];
    }

    /**
     * Get Financial Summary (Balances).
     */
    public function getFinancialSummary()
    {
        // Simple aggregate for speed in reports. For detailed statement, use StatementService
        return [
            'cashboxes' => Cashbox::all(),
            'total_cash' => Cashbox::sum('current_balance'),
            'customers_summary' => [
                'count' => Customer::count(),
                // Note: Net balance is complex (Invoices - Payments). 
                // For report summary we might just show count or basic sums if stored.
                // Since we don't store balance, we provide the list.
            ],
            'suppliers_summary' => [
                'count' => Supplier::count(),
            ]
        ];
    }

    /**
     * Get Expense report data.
     */
    public function getExpenseReport($start, $end)
    {
        $query = Expense::with('cashbox')
            ->whereBetween('expense_date', [$start, $end]);

        return [
            'total_amount' => (clone $query)->sum('amount'),
            'count' => (clone $query)->count(),
            'expenses' => (clone $query)->latest('expense_date')->get(),
            'by_cashbox' => Expense::whereBetween('expense_date', [$start, $end])
                ->select('cashbox_id', DB::raw('SUM(amount) as total'))
                ->groupBy('cashbox_id')
                ->with('cashbox')
                ->get(),
        ];
    }
}
