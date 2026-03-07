<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddQuantityRequest;
use App\Http\Requests\Inventory\StoreProductRequest;
use App\Http\Requests\Inventory\UpdateProductRequest;
use App\Http\Resources\Inventory\ProductResource;
use App\Models\Product;
use App\Services\Inventory\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'search',
            'category_id',
            'subcategory_id',
            'supplier_id',
            'tag_id',
            'author',
            'publisher',
            'product_type',
            'is_active',
            'low_stock',
            'stock_status',
            'per_page'
        ]);

        // Cast per_page to integer to avoid issues with empty strings or nulls
        if (isset($filters['per_page'])) {
            $filters['per_page'] = (int) $filters['per_page'];
        }

        $products = $this->productService->getAllProducts($filters);
        $stats = $this->productService->getInventoryStats();

        // Returning the collection directly handles pagination and 'data' wrapping correctly for the Flutter app
        return ProductResource::collection($products)->additional([
            'stats' => $stats
        ])->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());

        return response()->json([
            'message' => 'تم إنشاء المنتج بنجاح',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $product = $this->productService->getProduct($product);

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->updateProduct($product, $request->validated());

        return response()->json([
            'message' => 'تم تحديث المنتج بنجاح',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->productService->deleteProduct($product);

        return response()->json([
            'message' => 'تم حذف المنتج بنجاح',
        ]);
    }

    /**
     * Add quantity to product
     */
    public function addQuantity(AddQuantityRequest $request, Product $product): JsonResponse
    {
        $purchaseHistory = $this->productService->addQuantity($product, $request->quantity, $request->validated());

        return response()->json([
            'message' => 'تم إضافة الكمية بنجاح',
            'data' => $purchaseHistory,
        ]);
    }

    /**
     * Get products with low stock
     */
    public function getLowStock(Request $request): JsonResponse
    {
        $perPage = $request->has('per_page') ? (int) $request->per_page : 15;
        $filters = ['low_stock' => true, 'per_page' => $perPage];
        $products = $this->productService->getAllProducts($filters);

        return ProductResource::collection($products)->response()->setStatusCode(200);
    }
}
