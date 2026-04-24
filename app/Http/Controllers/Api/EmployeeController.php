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

            // 2. Handle Avatar Upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
                $user->save();
            }

            // 3. Initialize Smart Balance
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

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|unique:users,phone,' . $employee->id,
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|in:manager,accountant,supervisor,driver,porter',
            'salary_amount' => 'sometimes|numeric',
            'salary_due_day' => 'sometimes|integer|min:1|max:31',
            'status' => 'sometimes|in:active,inactive',
            'avatar' => 'nullable|image|max:2048'
        ]);

        if ($request->has('name')) $employee->name = $request->name;
        if ($request->has('phone')) $employee->phone = $request->phone;
        if ($request->filled('password')) $employee->password = Hash::make($request->password);
        if ($request->has('role')) $employee->role = $request->role;
        if ($request->has('salary_amount')) $employee->salary_amount = $request->salary_amount;
        if ($request->has('salary_due_day')) $employee->salary_due_day = $request->salary_due_day;
        if ($request->has('status')) $employee->status = $request->status;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $employee->avatar = $path;
        }

        $employee->save();

        return response()->json([
            'message' => 'تم تحديث بيانات الموظف بنجاح.',
            'user' => $employee->load('balances'),
        ]);
    }

    public function show(User $employee)
    {
        return $employee->load('balances');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'تم حذف الموظف بنجاح']);
    }
}
