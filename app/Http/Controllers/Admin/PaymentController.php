<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentReceipt;
use App\Models\Subscription;
use App\Http\Requests\Admin\StorePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['subscriber', 'store', 'subscription'])->latest()->paginate(10);
        return view('admin.pages.payments.index', compact('payments'));
    }

    public function create()
    {
        $subscriptions = Subscription::with(['subscriber', 'store'])->where('status', '!=', 'cancelled')->get();
        return view('admin.pages.payments.create', compact('subscriptions'));
    }

    public function store(StorePaymentRequest $request)
    {
        $subscription = Subscription::find($request->subscription_id);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'subscription_id' => $subscription->id,
                'subscriber_id' => $subscription->subscriber_id,
                'store_id' => $subscription->store_id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('receipts', 'public');
                PaymentReceipt::create([
                    'payment_id' => $payment->id,
                    'file_path' => $path,
                    'uploaded_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.payments')->with('success', 'تم تسجيل الدفع بنجاح وهو قيد المراجعة.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تسجيل الدفع.');
        }
    }

    public function show(Payment $payment)
    {
        $payment->load(['subscriber', 'store', 'subscription', 'receipt', 'reviewer']);
        return view('admin.pages.payments.show', compact('payment'));
    }

    public function approve(Request $request, Payment $payment)
    {
        if (!Auth::guard('admin')->user()->can('review payments')) {
            abort(403);
        }

        $payment->update([
            'status' => 'approved',
            'reviewed_by_admin_id' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
            'notes' => $payment->notes . "\n[تم الموافقة بواسطة: " . Auth::guard('admin')->user()->name . "]"
        ]);

        return redirect()->back()->with('success', 'تم اعتماد الدفع بنجاح.');
    }

    public function reject(Request $request, Payment $payment)
    {
        if (!Auth::guard('admin')->user()->can('review payments')) {
            abort(403);
        }

        $payment->update([
            'status' => 'rejected',
            'reviewed_by_admin_id' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
            'notes' => $payment->notes . "\n[تم الرفض بواسطة: " . Auth::guard('admin')->user()->name . "]\nالسبب: " . $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'تم رفض الدفع.');
    }
}
