<?php

namespace App\Http\Controllers\Api\Representatives;

use App\Enums\WithdrawalStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Representatives\ApproveWithdrawalRequest;
use App\Http\Resources\Representatives\WithdrawalRequestResource;
use App\Models\WithdrawalRequest;
use App\Services\Representatives\RepresentativeAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    public function __construct(
        protected RepresentativeAccountService $accountService
    ) {
    }

    /**
     * Display a listing of withdrawal requests.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('representatives.view');

        $query = WithdrawalRequest::with(['representative', 'approver'])->latest('requested_at');

        // Apply filters
        if ($request->has('status')) {
            // Check if status is a number or string
            $status = $request->status;
            if (is_numeric($status)) {
                $query->where('status', $status);
            } else {
                // Try to find status from label/enum
                // For simplicity, we assume the client sends the raw enum value (0, 1, 2)
                $query->where('status', $status);
            }
        }

        if ($request->has('representative_id')) {
            $query->where('representative_id', $request->representative_id);
        }

        $withdrawals = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => WithdrawalRequestResource::collection($withdrawals->items()),
            'meta' => [
                'current_page' => $withdrawals->currentPage(),
                'last_page' => $withdrawals->lastPage(),
                'per_page' => $withdrawals->perPage(),
                'total' => $withdrawals->total(),
            ],
            'statistics' => [
                'pending_count' => WithdrawalRequest::where('status', WithdrawalStatus::PENDING)->count(),
                'approved_count' => WithdrawalRequest::where('status', WithdrawalStatus::APPROVED)->count(),
                'rejected_count' => WithdrawalRequest::where('status', WithdrawalStatus::REJECTED)->count(),
            ]
        ]);
    }

    /**
     * Display the specified withdrawal request.
     */
    public function show(WithdrawalRequest $withdrawalRequest): JsonResponse
    {
        $this->authorize('representatives.view');

        $withdrawalRequest->load(['representative', 'approver', 'transactions']);

        return response()->json([
            'data' => new WithdrawalRequestResource($withdrawalRequest)
        ]);
    }

    /**
     * Approve a withdrawal request.
     */
    public function approve(WithdrawalRequest $withdrawalRequest, ApproveWithdrawalRequest $request): JsonResponse
    {
        $this->authorize('representatives.update');

        try {
            $this->accountService->approveWithdrawalRequest(
                $withdrawalRequest,
                auth()->user(),
                $request->validated()['notes'] ?? null
            );

            return response()->json([
                'message' => 'تم الموافقة على طلب السحب بنجاح.',
                'data' => new WithdrawalRequestResource($withdrawalRequest->fresh(['representative', 'approver']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء الموافقة على طلب السحب.',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(WithdrawalRequest $withdrawalRequest, RejectWithdrawalRequest $request): JsonResponse
    {
        $this->authorize('representatives.update');

        try {
            $this->accountService->rejectWithdrawalRequest(
                $withdrawalRequest,
                auth()->user(),
                $request->validated()['reason']
            );

            return response()->json([
                'message' => 'تم رفض طلب السحب.',
                'data' => new WithdrawalRequestResource($withdrawalRequest->fresh(['representative', 'approver']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء رفض طلب السحب.',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
