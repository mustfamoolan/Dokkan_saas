<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Expense::with('creator')->latest('expense_date');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('start_date')) {
            $query->where('expense_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('expense_date', '<=', $request->end_date);
        }

        $expenses = $query->paginate(15);
        $expenseCategories = \App\Models\Expense::distinct()->pluck('category')->filter();

        return view('admin.expenses.index', compact('expenses', 'expenseCategories'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        \App\Models\Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'تم إضافة المصروف بنجاح');
    }

    public function edit(\App\Models\Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(Request $request, \App\Models\Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'تم تحديث المصروف بنجاح');
    }

    public function destroy(\App\Models\Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'تم حذف المصروف بنجاح');
    }
}
