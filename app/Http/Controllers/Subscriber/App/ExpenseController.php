<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreExpenseRequest;
use App\Models\Expense;
use App\Models\Cashbox;
use App\Services\CashboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    protected $cashboxService;

    public function __construct(CashboxService $cashboxService)
    {
        $this->cashboxService = $cashboxService;
    }

    public function index()
    {
        $expenses = Expense::with('cashbox')->latest()->paginate(20);
        return view('subscriber.app.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $cashboxes = Cashbox::where('is_active', true)->get();
        return view('subscriber.app.expenses.create', compact('cashboxes'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;

        $this->cashboxService->createExpense([
            'store_id' => $storeId,
            'cashbox_id' => $request->cashbox_id,
            'category' => $request->category,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('subscriber.app.expenses.index')->with('success', 'تم تسجيل المصروف بنجاح.');
    }
}
