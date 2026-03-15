<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreCashboxRequest;
use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use App\Services\CashboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashboxController extends Controller
{
    protected $cashboxService;

    public function __construct(CashboxService $cashboxService)
    {
        $this->cashboxService = $cashboxService;
    }

    public function index()
    {
        $cashboxes = Cashbox::latest()->paginate(20);
        return view('subscriber.app.cashboxes.index', compact('cashboxes'));
    }

    public function create()
    {
        return view('subscriber.app.cashboxes.create');
    }

    public function store(StoreCashboxRequest $request)
    {
        $storeId = Auth::guard('subscriber')->user()->store->id;

        $cashbox = Cashbox::create([
            'store_id' => $storeId,
            'name' => $request->name,
            'current_balance' => 0, // Always starts at 0, adjustment creates the balance
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->filled('current_balance') && $request->current_balance > 0) {
            $this->cashboxService->recordTransaction([
                'cashbox_id' => $cashbox->id,
                'amount' => $request->current_balance,
                'direction' => 'in',
                'type' => 'adjustment',
                'notes' => 'رصيد افتتاحي عند إنشاء الصندوق',
            ]);
        }

        return redirect()->route('subscriber.app.cashboxes.index')->with('success', 'تم إنشاء الصندوق بنجاح.');
    }

    public function show(Cashbox $cashbox)
    {
        $transactions = $cashbox->transactions()->latest()->paginate(20);
        return view('subscriber.app.cashboxes.show', compact('cashbox', 'transactions'));
    }

    public function edit(Cashbox $cashbox)
    {
        return view('subscriber.app.cashboxes.edit', compact('cashbox'));
    }

    public function update(StoreCashboxRequest $request, Cashbox $cashbox)
    {
        $cashbox->update([
            'name' => $request->name,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('subscriber.app.cashboxes.index')->with('success', 'تم تحديث بيانات الصندوق بنجاح.');
    }

    public function adjust(Request $request, Cashbox $cashbox)
    {
        $request->validate([
            'new_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $this->cashboxService->adjustBalance($cashbox, $request->new_balance, $request->notes);

        return redirect()->back()->with('success', 'تم تسوية رصيد الصندوق بنجاح.');
    }
}
