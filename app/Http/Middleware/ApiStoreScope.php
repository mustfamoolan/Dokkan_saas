<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiStoreScope
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subscriber = Auth::guard('sanctum')->user();

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Ensure the subscriber has an active store
        if (!$subscriber->store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found or setup incomplete.',
            ], 403);
        }

        // Multi-tenancy isolation is primarily handled by HasStoreScope trait on models,
        // but we can set a global app state or just rely on the authenticated user's store_id.
        // We'll also check if the subscriber is active.
        if (!$subscriber->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber account is inactive.',
            ], 403);
        }

        return $next($request);
    }
}
