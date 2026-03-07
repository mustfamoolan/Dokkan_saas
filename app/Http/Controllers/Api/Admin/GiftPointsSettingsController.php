<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Orders\GiftPointsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GiftPointsSettingsController extends Controller
{
    public function __construct(
        protected GiftPointsService $pointsService
    ) {
    }

    /**
     * Get gift points settings and exceptions.
     */
    public function index(): JsonResponse
    {
        $settings = $this->pointsService->getSettings()->first();
        $exceptions = $this->pointsService->getExceptions();

        return response()->json([
            'settings' => $settings,
            'exceptions' => $exceptions,
        ]);
    }

    /**
     * Store or update main gift points setting.
     */
    public function storeOrUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'points_per_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $setting = $this->pointsService->getSettings()->first();

        try {
            if ($setting) {
                $validated['is_active'] = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : $setting->is_active;
                $this->pointsService->updateSetting($setting, $validated);
            } else {
                $validated['is_active'] = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : true;
                $this->pointsService->createSetting($validated);
            }

            return response()->json([
                'message' => 'تم تحديث إعدادات نقاط الهدايا بنجاح.',
                'settings' => $this->pointsService->getSettings()->first()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store a newly created gift points exception.
     */
    public function storeException(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'representative_id' => ['nullable', 'exists:representatives,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'points_per_order' => ['required', 'integer', 'min:0'],
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
            $exception = $this->pointsService->createException($validated);
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
     * Update the specified gift points exception.
     */
    public function updateException(Request $request, int $id): JsonResponse
    {
        $exception = $this->pointsService->getException($id);

        if (!$exception) {
            return response()->json(['error' => 'الاستثناء غير موجود.'], 404);
        }

        $validated = $request->validate([
            'points_per_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        try {
            $this->pointsService->updateException($exception, $validated);
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
     * Remove the specified gift points exception.
     */
    public function destroyException(int $id): JsonResponse
    {
        $exception = $this->pointsService->getException($id);

        if (!$exception) {
            return response()->json(['error' => 'الاستثناء غير موجود.'], 404);
        }

        try {
            $this->pointsService->deleteException($exception);

            return response()->json([
                'message' => 'تم حذف الاستثناء بنجاح.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
