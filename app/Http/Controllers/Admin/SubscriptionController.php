<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Subscriber;
use App\Models\Store;
use App\Models\Plan;
use App\Http\Requests\Admin\StoreSubscriptionRequest;
use App\Http\Requests\Admin\UpdateSubscriptionRequest;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['subscriber', 'store', 'plan'])->latest()->paginate(10);
        return view('admin.pages.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $subscribers = Subscriber::with('store')->where('is_active', true)->get();
        $plans = Plan::where('is_active', true)->get();
        return view('admin.pages.subscriptions.create', compact('subscribers', 'plans'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        Subscription::create($request->validated());

        return redirect()->route('admin.subscriptions')->with('success', 'تم إنشاء الاشتراك بنجاح.');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['subscriber', 'store', 'plan']);
        return view('admin.pages.subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $subscription->load(['subscriber', 'store', 'plan']);
        $plans = Plan::where('is_active', true)->get();
        return view('admin.pages.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->validated());

        return redirect()->route('admin.subscriptions')->with('success', 'تم تحديث الاشتراك بنجاح.');
    }
}
