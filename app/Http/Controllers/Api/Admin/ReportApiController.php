<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // 1. Basic Stats
        $ordersQuery = \App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)
            ->whereDate('completed_at', '>=', $startDate)
            ->whereDate('completed_at', '<=', $endDate);

        $totalRevenue = (float) $ordersQuery->sum('total_amount');
        $totalGrossProfit = (float) $ordersQuery->sum('final_profit');

        $totalExpenses = (float) \App\Models\Expense::whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate)
            ->sum('amount');

        $netProfit = $totalGrossProfit - $totalExpenses;

        // 2. Profit by Section
        $sectionProfits = \App\Models\OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
            ->where('orders.status', \App\Enums\OrderStatus::COMPLETED)
            ->whereDate('orders.completed_at', '>=', $startDate)
            ->whereDate('orders.completed_at', '<=', $endDate)
            ->selectRaw('COALESCE(parents.name, categories.name) as section_name, SUM(order_items.profit_subtotal) as profit')
            ->groupBy('section_name')
            ->get();

        // 3. Profit by Branch
        $branchProfits = \App\Models\OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', \App\Enums\OrderStatus::COMPLETED)
            ->whereDate('orders.completed_at', '>=', $startDate)
            ->whereDate('orders.completed_at', '<=', $endDate)
            ->selectRaw('categories.name as branch_name, SUM(order_items.profit_subtotal) as profit')
            ->groupBy('branch_name')
            ->get();

        // 4. Rep Performance
        $repPerformance = \App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)
            ->whereDate('completed_at', '>=', $startDate)
            ->whereDate('completed_at', '<=', $endDate)
            ->with([
                'representative' => function ($q) {
                    $q->select('id', 'name');
                }
            ])
            ->selectRaw('representative_id, SUM(final_profit) as total_profit, COUNT(*) as orders_count')
            ->groupBy('representative_id')
            ->orderByDesc('total_profit')
            ->take(10)
            ->get();

        // 5. Expense Breakdown
        $expenseCategories = \App\Models\Expense::whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate)
            ->selectRaw('COALESCE(category, "عام") as category_name, SUM(amount) as total')
            ->groupBy('category_name')
            ->get();

        return response()->json([
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_gross_profit' => $totalGrossProfit,
                'total_expenses' => $totalExpenses,
                'net_profit' => $netProfit,
            ],
            'section_profits' => $sectionProfits,
            'branch_profits' => $branchProfits,
            'rep_performance' => $repPerformance,
            'expense_categories' => $expenseCategories,
            'dates' => [
                'start' => $startDate,
                'end' => $endDate,
            ]
        ]);
    }
}
