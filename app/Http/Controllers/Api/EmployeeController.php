<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SmartBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        return User::with('balances')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:manager,accountant,supervisor,driver,porter',
            'salary_amount' => 'required|numeric',
            'salary_due_day' => 'required|integer|min:1|max:31',
            'initial_balance' => 'nullable|numeric',
            'balance_type' => 'nullable|in:credit,debt,none',
            'currency' => 'nullable|in:IQD,USD'
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Create User
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'salary_amount' => $request->salary_amount,
                'salary_due_day' => $request->salary_due_day,
                'status' => 'active',
            ]);

            // 2. Initialize Smart Balance
            $balanceAmount = 0;
            if ($request->balance_type === 'credit') {
                $balanceAmount = $request->initial_balance; // له (Positive)
            } elseif ($request->balance_type === 'debt') {
                $balanceAmount = -$request->initial_balance; // عليه (Negative)
            }

            $user->balances()->create([
                'currency' => $request->currency ?? 'IQD',
                'balance' => $balanceAmount,
                'last_transaction_at' => now(),
            ]);

            return response()->json([
                'message' => 'تم إضافة الموظف بنجاح.',
                'user' => $user->load('balances'),
            ], 201);
        });
    }
}
