<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Orders\OrderCommissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderCommissionSettingsController extends Controller
{
    public function __construct(
        protected OrderCommissionService $commissionService
    ) {
    }

    /**
     * Get commission settings and exceptions.
     */
    public function index(): JsonResponse
    {
        $settings = $this->commissionService->getCommissionSettings()->first();
        $exceptions = $this->commissionService->getCommissionExceptions();

        $users = \App\Models\User::where('is_active', true)->select('id', 'name')->get();
        $representatives = \App\Models\Representative::where('is_active', true)->select('id', 'name')->get();

        return response()->json([
            'settings' => $settings,
            'exceptions' => $exceptions,
            'users' => $users,
            'representatives' => $representatives,
        ]);
    }

    /**
     * Store or update main commission setting.
     */
    public function storeOrUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'commission_value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $setting = $this->commissionService->getCommissionSettings()->first();

        try {
            if ($setting) {
                // Assuming is_active comes as part of request if updating status
                $validated['is_active'] = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : $setting->is_active;
                $this->commissionService->updateCommissionSetting($setting, $validated);
            } else {
                $validated['is_active'] = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : true;
                $this->commissionService->createCommissionSetting($validated);
            }

            return response()->json([
                'message' => 'تم تحديث إعداد العمولة بنجاح.',
                'settings' => $this->commissionService->getCommissionSettings()->first()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store a newly created commission exception.
     */
    public function storeException(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'representative_id' => ['nullable', 'exists:representatives,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'commission_value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['representative_id']) && empty($validated['user_id'])) {
            return response()->json(['error' => 'يجب اختيار مندوب أو موظف.'], 422);
        }

        if (!empty($validated['representative_id']) && !empty($validated['user_id'])) {
            return response()->json(['error' => 'يجب اختيار مندوب أو موظف فقط، وليس كلاهما.'], 422);
        }

        $validated['is_active'] = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : true;

        try {
            $exception = $this->commissionService->createCommissionException($validated);
            // Load relations for response
            $exception->load(['representative', 'user']);

            return response()->json([
                'message' => 'تم إضافة الاستثناء بنجاح.',
                'exception' => $exception,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Update the specified commission exception.
     */
    public function updateException(Request $request, int $id): JsonResponse
    {
        $exception = $this->commissionService->getCommissionException($id);

        if (!$exception) {
            return response()->json(['error' => 'الاستثناء غير موجود.'], 404);
        }

        $validated = $request->validate([
            'commission_value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        try {
            $this->commissionService->updateCommissionException($exception, $validated);

            // Reload to get updated db values
            $exception->refresh();
            $exception->load(['representative', 'user']);

            return response()->json([
                'message' => 'تم تحديث الاستثناء بنجاح.',
                'exception' => $exception,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified commission exception.
     */
    public function destroyException(int $id): JsonResponse
    {
        $exception = $this->commissionService->getCommissionException($id);

        if (!$exception) {
            return response()->json(['error' => 'الاستثناء غير موجود.'], 404);
        }

        try {
            $this->commissionService->deleteCommissionException($exception);

            return response()->json([
                'message' => 'تم حذف الاستثناء بنجاح.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
