<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\SmartBalance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        // Return suppliers with their balances
        $suppliers = Supplier::with('balances')->get()->map(function($sup) {
            // Flatten balance for frontend compatibility if needed
            // For now, let's just return the raw balances relationship
            return $sup;
        });
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:suppliers,phone',
            'currency' => 'required|string|in:USD,IQD',
            'balance' => 'required|numeric',
            'type' => 'required|string|in:credit,debit,zero',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function() use ($request) {
            $supplier = Supplier::create($request->only(['name', 'phone', 'location', 'category', 'status']));

            // Handle Initial Balance
            $amount = $request->balance;
            if ($request->type === 'debit') {
                $amount = -$amount;
            }

            $supplier->balances()->create([
                'currency' => $request->currency,
                'balance' => $amount,
                'last_transaction_at' => now(),
            ]);

            return response()->json($supplier->load('balances'), 201);
        });
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json(['message' => 'المورد غير موجود'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:suppliers,phone,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function() use ($request, $supplier) {
            $supplier->update($request->only(['name', 'phone', 'location', 'category', 'status']));

            // If balance/currency provided, update it
            if ($request->has('balance') && $request->has('currency')) {
                $amount = $request->balance;
                if ($request->type === 'debit') {
                    $amount = -$amount;
                }

                $supplier->balances()->updateOrCreate(
                    ['currency' => $request->currency],
                    [
                        'balance' => $amount,
                        'last_transaction_at' => now()
                    ]
                );
            }

            return response()->json($supplier->load('balances'));
        });
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json(['message' => 'المورد غير موجود'], 404);
        }
        $supplier->delete();
        return response()->json(['message' => 'تم حذف المورد بنجاح']);
    }
}
