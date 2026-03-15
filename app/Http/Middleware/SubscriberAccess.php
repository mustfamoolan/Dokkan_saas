<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class SubscriberAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $subscriber = Auth::guard('subscriber')->user();

        if (!$subscriber) {
            return redirect()->route('subscriber.login');
        }

        // 1. Check Store
        if (!$subscriber->store) {
            return redirect()->route('subscriber.onboarding.store-setup');
        }

        // 2. Check Account Status
        if (!$subscriber->is_active) {
            return response()->view('subscriber.app.status.suspended');
        }

        // 3. Check Subscription Status
        $subscription = Subscription::where('store_id', $subscriber->store->id)->latest()->first();

        if (!$subscription) {
            return redirect()->route('subscriber.onboarding.plan-selection');
        }

        if ($subscription->status === 'pending') {
            return redirect()->route('subscriber.onboarding.status');
        }

        if ($subscription->status === 'suspended') {
            return response()->view('subscriber.app.status.suspended');
        }

        if ($subscription->status === 'expired' || ($subscription->ends_at && $subscription->ends_at->isPast())) {
            return response()->view('subscriber.app.status.expired');
        }

        if ($subscription->status !== 'active' && $subscription->status !== 'trial') {
            return response()->view('subscriber.app.status.no-access');
        }

        return $next($request);
    }
}
