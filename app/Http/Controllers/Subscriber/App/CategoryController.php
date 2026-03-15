<?php

namespace App\Http\Controllers\Subscriber\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscriber\StoreCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('sort_order')->paginate(20);
        return view('subscriber.app.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('subscriber.app.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        ProductCategory::create($request->validated());
        return redirect()->route('subscriber.app.categories.index')->with('success', 'تم إضافة الصنف بنجاح.');
    }

    public function edit(ProductCategory $category)
    {
        return view('subscriber.app.categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, ProductCategory $category)
    {
        $category->update($request->validated());
        return redirect()->route('subscriber.app.categories.index')->with('success', 'تم تحديث الصنف بنجاح.');
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'لا يمكن حذف الصنف لاحتوائه على منتجات.');
        }
        $category->delete();
        return redirect()->route('subscriber.app.categories.index')->with('success', 'تم حذف الصنف بنجاح.');
    }
}
