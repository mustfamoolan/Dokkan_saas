<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\Store;
use App\Http\Requests\Admin\StoreSubscriberRequest;
use App\Http\Requests\Admin\UpdateSubscriberRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::with('store')->latest()->paginate(10);
        return view('admin.pages.subscribers.index', compact('subscribers'));
    }

    public function create()
    {
        return view('admin.pages.subscribers.create');
    }

    public function store(StoreSubscriberRequest $request)
    {
        DB::transaction(function () use ($request) {
            $subscriber = Subscriber::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password, // Hashed by model cast
                'status' => $request->status,
                'is_active' => $request->is_active,
                'notes' => $request->notes,
            ]);

            $logoPath = null;
            if ($request->hasFile('store_logo')) {
                $logoPath = $request->file('store_logo')->store('stores', 'public');
            }

            Store::create([
                'subscriber_id' => $subscriber->id,
                'name' => $request->store_name,
                'phone' => $request->store_phone,
                'address' => $request->store_address,
                'logo' => $logoPath,
                'currency' => $request->currency,
                'locale' => $request->locale,
                'timezone' => $request->timezone,
                'status' => $request->store_status,
            ]);
        });

        return redirect()->route('admin.subscribers')->with('success', 'تم إضافة المشترك والمتجر بنجاح.');
    }

    public function show(Subscriber $subscriber)
    {
        $subscriber->load('store');
        return view('admin.pages.subscribers.show', compact('subscriber'));
    }

    public function edit(Subscriber $subscriber)
    {
        $subscriber->load('store');
        return view('admin.pages.subscribers.edit', compact('subscriber'));
    }

    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber)
    {
        DB::transaction(function () use ($request, $subscriber) {
            $subscriberData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => $request->status,
                'is_active' => $request->is_active,
                'notes' => $request->notes,
            ];

            if ($request->filled('password')) {
                $subscriberData['password'] = $request->password;
            }

            $subscriber->update($subscriberData);

            $storeData = [
                'name' => $request->store_name,
                'phone' => $request->store_phone,
                'address' => $request->store_address,
                'currency' => $request->currency,
                'locale' => $request->locale,
                'timezone' => $request->timezone,
                'status' => $request->store_status,
            ];

            if ($request->hasFile('store_logo')) {
                // Delete old logo if exists
                if ($subscriber->store->logo) {
                    Storage::disk('public')->delete($subscriber->store->logo);
                }
                $storeData['logo'] = $request->file('store_logo')->store('stores', 'public');
            }

            $subscriber->store->update($storeData);
        });

        return redirect()->route('admin.subscribers')->with('success', 'تم تحديث بيانات المشترك والمتجر بنجاح.');
    }
}
