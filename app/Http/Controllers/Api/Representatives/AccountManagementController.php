<?php

namespace App\Http\Controllers\Api\Representatives;

use App\Http\Controllers\Controller;
use App\Http\Requests\Representatives\AddBalanceRequest;
use App\Http\Requests\Representatives\DirectWithdrawalRequest;
use App\Http\Resources\Representatives\RepresentativeResource;
use App\Models\Representative;
use App\Services\Representatives\RepresentativeAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountManagementController extends Controller
{
    public function __construct(
        protected RepresentativeAccountService $accountService
    ) {
    }

    /**
     * Display a listing of representatives with their accounts.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('representatives.view');

        $query = Representative::with([
            'transactions' => function ($q) {
                $q->latest()->limit(1);
            }
        ]);

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $representatives = $query->latest()->paginate($request->per_page ?? 15);

        // Calculate totals dynamically without executing a massive query if not needed, 
        // but since this is what the web controller does, we replicate it.
        $totalBalance = Representative::sum('balance');
        $totalPending = Representative::with('withdrawalRequests')
            ->get()
            ->sum(function ($rep) {
                return $rep->pending_withdrawals_amount;
            });

        return response()->json([
            'data' => RepresentativeResource::collection($representatives->items()),
            'totals' => [
                'total_balance' => $totalBalance,
                'total_pending' => $totalPending,
            ],
            'meta' => [
                'current_page' => $representatives->currentPage(),
                'last_page' => $representatives->lastPage(),
                'per_page' => $representatives->perPage(),
                'total' => $representatives->total(),
            ],
        ]);
    }

    /**
     * Display the specified representative's account statistics and recent transactions.
     */
    public function show(Representative $representative): JsonResponse
    {
        $this->authorize('representatives.view');

        $representative->load([
            'transactions' => function ($q) {
                $q->with(['creator', 'approver'])->latest()->limit(10);
            }
        ]);

        $statistics = $this->accountService->getAccountStatistics($representative);

        return response()->json([
            'data' => [
                'representative' => new RepresentativeResource($representative),
                'statistics' => $statistics,
                'recent_transactions' => $representative->transactions,
            ]
        ]);
    }

    /**
     * Get paginated transactions for a representative.
     */
    public function transactions(Representative $representative, Request $request): JsonResponse
    {
        $this->authorize('representatives.view');

        $query = $representative->transactions()->with(['creator', 'approver'])->latest();

        // Apply filters
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => $transactions->items(),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }

    /**
     * Add balance to a representative.
     */
    public function addBalance(Representative $representative, AddBalanceRequest $request): JsonResponse
    {
        $this->authorize('representatives.update');

        try {
            $transaction = $this->accountService->addBalance(
                $representative,
                (float) $request->amount,
                $request->type,
                $request->description ?? null,
                auth()->user()
            );

            return response()->json([
                'message' => 'تم إضافة الرصيد بنجاح.',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إضافة الرصيد.',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Direct withdrawal from a representative.
     */
    public function directWithdraw(Representative $representative, DirectWithdrawalRequest $request): JsonResponse
    {
        $this->authorize('representatives.update');

        try {
            $transaction = $this->accountService->directWithdraw(
                $representative,
                $request->validated(),
                auth()->user()
            );

            return response()->json([
                'message' => 'تم السحب بنجاح.',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء سحب الرصيد.',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
