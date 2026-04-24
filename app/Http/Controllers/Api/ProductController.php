<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with(['category', 'supplier', 'latestPrice', 'stock'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'sku' => 'nullable|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'units_per_carton' => 'integer',
            'weight_per_carton' => 'nullable|numeric',
            'pricing.cost' => 'required|numeric',
            'pricing.retail_price' => 'required|numeric',
            'pricing.wholesale_price' => 'required|numeric',
            'stock.cartons' => 'integer',
            'stock.extra_units' => 'integer',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $product = Product::create($request->only([
                'name', 'sku', 'barcode', 'category_id', 'supplier_id', 
                'units_per_carton', 'weight_per_carton', 'status'
            ]));

            // Save Price
            $product->prices()->create($request->input('pricing'));

            // Save Stock
            $stockData = $request->input('stock', []);
            $unitsPerCarton = $product->units_per_carton ?: 1;
            $totalUnits = ( ($stockData['cartons'] ?? 0) * $unitsPerCarton ) + ($stockData['extra_units'] ?? 0);
            
            $product->stock()->create(array_merge($stockData, ['total_units' => $totalUnits]));

            return $product->load(['latestPrice', 'stock']);
        });
    }

    public function show(Product $product)
    {
        return $product->load(['category', 'supplier', 'latestPrice', 'stock', 'prices']);
    }

    public function update(Request $request, Product $product)
    {
        // For simplicity, just updating core info for now
        $product->update($request->all());
        
        // If pricing is provided, add new price entry (historical tracking)
        if ($request->has('pricing')) {
            $product->prices()->create($request->input('pricing'));
        }

        return $product->load(['latestPrice', 'stock']);
    }
}
