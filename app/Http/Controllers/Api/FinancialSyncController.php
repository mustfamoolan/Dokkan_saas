<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialBox;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialSyncController extends Controller
{
    public function index()
    {
        return response()->json([
            'boxes' => FinancialBox::all(),
            'categories' => FinancialCategory::all(),
            'recent_transactions' => FinancialTransaction::with(['box', 'category'])->latest()->limit(50)->get()
        ]);
    }

    public function sync(Request $request)
    {
        $validated = $request->validate([
            'transactions' => 'required|array',
            'transactions.*.box_id' => 'required',
            'transactions.*.type' => 'required|in:IN,OUT,TRANSFER',
            'transactions.*.amount' => 'required|numeric',
            'transactions.*.description' => 'nullable|string',
            'transactions.*.category_slug' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->transactions as $transData) {
                $category = null;
                if (!empty($transData['category_slug'])) {
                    $category = FinancialCategory::where('slug', $transData['category_slug'])->first();
                }

                FinancialTransaction::create([
                    'financial_box_id' => $transData['box_id'],
                    'financial_category_id' => $category ? $category->id : null,
                    'type' => $transData['type'],
                    'amount' => $transData['amount'],
                    'description' => $transData['description'],
                    'related_type' => $transData['related_type'] ?? null,
                    'related_id' => $transData['related_id'] ?? null,
                    'balance_after' => $transData['balance_after'] ?? 0,
                ]);

                // Update box balance on server
                $box = FinancialBox::find($transData['box_id']);
                if ($box) {
                    if ($transData['type'] === 'IN') $box->increment('balance', $transData['amount']);
                    else if ($transData['type'] === 'OUT') $box->decrement('balance', $transData['amount']);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Sync successful']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e.getMessage()], 500);
        }
    }
}
