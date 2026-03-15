<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Http\Requests\Admin\StorePlanRequest;
use App\Http\Requests\Admin\UpdatePlanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->latest()->paginate(10);
        return view('admin.pages.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.pages.plans.create');
    }

    public function store(StorePlanRequest $request)
    {
        DB::transaction(function () use ($request) {
            if ($request->is_default) {
                Plan::where('is_default', true)->update(['is_default' => false]);
            }

            Plan::create($request->validated());
        });

        return redirect()->route('admin.plans')->with('success', 'تم إضافة الباقة بنجاح.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.pages.plans.edit', compact('plan'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        DB::transaction(function () use ($request, $plan) {
            if ($request->is_default) {
                Plan::where('id', '!=', $plan->id)->where('is_default', true)->update(['is_default' => false]);
            }

            $plan->update($request->validated());
        });

        return redirect()->route('admin.plans')->with('success', 'تم تحديث الباقة بنجاح.');
    }
}
