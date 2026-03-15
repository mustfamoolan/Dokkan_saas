<?php

namespace App\Http\Controllers\Subscriber;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\PaymentReceipt;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{

    public function status()
    {
        $subscriber = Auth::guard('subscriber')->user();
        $store = $subscriber->store;

        if (!$store) {
            return redirect()->route('subscriber.onboarding.store-setup');
        }

        $subscription = $subscriber->store->subscriptions()->latest()->first();

        if (!$subscription) {
            return redirect()->route('subscriber.onboarding.plan-selection');
        }

        if ($subscription->status === 'active' || $subscription->status === 'trial') {
            return redirect()->route('subscriber.app.dashboard');
        }

        return view('subscriber.onboarding.status', compact('subscriber', 'store', 'subscription'));
    }

    public function showStoreSetup()
    {
        if (Auth::guard('subscriber')->user()->store) {
            return redirect()->route('subscriber.onboarding.plan-selection');
        }
        return view('subscriber.onboarding.store-setup');
    }

    public function saveStoreSetup(Request $request)
    {
        if (Auth::guard('subscriber')->user()->store) {
            return redirect()->route('subscriber.onboarding.plan-selection');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
            'logo' => 'nullable|image|max:1024',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store_logos', 'public');
        }

        $subscriber = Auth::guard('subscriber')->user();
        
        $store = Store::create([
            'subscriber_id' => $subscriber->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'logo' => $logoPath,
            'currency' => Setting::where('key', 'default_currency')->first()->value ?? 'USD',
            'locale' => Setting::where('key', 'default_locale')->first()->value ?? 'ar',
            'timezone' => Setting::where('key', 'default_timezone')->first()->value ?? 'UTC',
            'status' => 'active',
        ]);

        return redirect()->route('subscriber.onboarding.plan-selection');
    }

    public function showPlanSelection()
    {
        $subscriber = Auth::guard('subscriber')->user();
        $store = $subscriber->store;

        if (!$store) {
            return redirect()->route('subscriber.onboarding.store-setup');
        }

        if (Subscription::where('store_id', $store->id)->whereIn('status', ['active', 'trial', 'pending'])->exists()) {
            return redirect()->route('subscriber.onboarding.status');
        }

        $plans = Plan::where('is_visible', true)->get();
        return view('subscriber.onboarding.plan-selection', compact('plans'));
    }

    public function selectPlan(Plan $plan)
    {
        $subscriber = Auth::guard('subscriber')->user();
        $store = $subscriber->store;

        if (!$store) {
            return redirect()->route('subscriber.onboarding.store-setup');
        }

        if (Subscription::where('store_id', $store->id)->whereIn('status', ['active', 'trial', 'pending'])->exists()) {
            return redirect()->route('subscriber.onboarding.status');
        }

        if ($plan->is_free) {
            $trialEnabled = Setting::where('key', 'trial_enabled')->first()->value ?? 'true';
            $trialDays = (int) (Setting::where('key', 'trial_days')->first()->value ?? 14);
            $autoActivate = Setting::where('key', 'auto_activate_accounts')->first()->value ?? 'true';

            Subscription::create([
                'subscriber_id' => $subscriber->id,
                'store_id' => $store->id,
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
                'status' => $autoActivate === 'true' ? 'active' : 'pending',
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'is_trial' => false,
                'auto_renew' => true,
            ]);

            return redirect()->route('subscriber.onboarding.status');
        }

        return redirect()->route('subscriber.onboarding.payment', $plan->id);
    }

    public function showPayment(Plan $plan)
    {
        $subscriber = Auth::guard('subscriber')->user();
        $store = $subscriber->store;

        if (!$store) return redirect()->route('subscriber.onboarding.store-setup');
        
        $settings = Setting::whereIn('key', [
            'payment_receiver_name', 'payment_phone', 
            'payment_account_number', 'payment_instructions',
            'default_currency'
        ])->pluck('value', 'key');

        return view('subscriber.onboarding.payment', compact('plan', 'settings'));
    }

    public function submitPayment(Request $request, Plan $plan)
    {
        $request->validate([
            'receipt' => 'required|image|max:2048',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $subscriber = Auth::guard('subscriber')->user();
        $store = $subscriber->store;

        DB::beginTransaction();
        try {
            $amount = $request->billing_cycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;
            
            $payment = Payment::create([
                'subscription_id' => 0, // Will update after creating subscription
                'subscriber_id' => $subscriber->id,
                'store_id' => $store->id,
                'amount' => $amount,
                'currency' => Setting::where('key', 'default_currency')->first()->value ?? 'USD',
                'payment_method' => 'manual',
                'status' => 'pending',
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
            ]);

            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('receipts', 'public');
                PaymentReceipt::create([
                    'payment_id' => $payment->id,
                    'file_path' => $path,
                    'uploaded_at' => now(),
                ]);
            }

            $subscription = Subscription::create([
                'subscriber_id' => $subscriber->id,
                'store_id' => $store->id,
                'plan_id' => $plan->id,
                'billing_cycle' => $request->billing_cycle,
                'status' => 'pending',
                'starts_at' => now(),
                'ends_at' => $request->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
                'is_trial' => false,
                'auto_renew' => true,
            ]);

            $payment->update(['subscription_id' => $subscription->id]);

            DB::commit();
            return redirect()->route('subscriber.onboarding.status');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إرسال الدفع.');
        }
    }
}
