<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $products = Product::with('category', 'stocks')
            ->latest()
            ->paginate($request->get('limit', 15));

        return $this->success(ProductResource::collection($products)->response()->getData(true));
    }

    public function show($id)
    {
        $product = Product::with('category', 'stocks')->findOrFail($id);
        return $this->success(new ProductResource($product));
    }
}
