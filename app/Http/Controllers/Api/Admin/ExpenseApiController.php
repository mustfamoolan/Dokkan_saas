<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseApiController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Expense::with('creator')->latest('expense_date');

        if ($request->filled('start_date')) {
            $query->where('expense_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('expense_date', '<=', $request->end_date);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->paginate($request->input('per_page', 15));

        return \App\Http\Resources\Admin\ExpenseResource::collection($expenses);
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

        $expense = \App\Models\Expense::create($validated);

        return new \App\Http\Resources\Admin\ExpenseResource($expense);
    }

    public function update(Request $request, $id)
    {
        $expense = \App\Models\Expense::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return new \App\Http\Resources\Admin\ExpenseResource($expense);
    }

    public function destroy($id)
    {
        $expense = \App\Models\Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'تم حذف المصروف بنجاح']);
    }

    public function categories()
    {
        $categories = \App\Models\Expense::distinct()->pluck('category')->filter();
        return response()->json(['data' => $categories]);
    }
}
