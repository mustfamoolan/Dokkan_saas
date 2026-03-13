<?php

namespace App\Http\Controllers\Api\Representatives;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the authenticated representative.
     */
    public function index(Request $request): JsonResponse
    {
        $representativeId = auth()->id();
        
        $query = Order::with(['orderItems.product', 'governorate', 'district'])
            ->where('representative_id', $representativeId)
            ->latest();

        // Filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json([
            'data' => OrderResource::collection($orders->items()),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
            'stats' => [
                'total' => Order::where('representative_id', $representativeId)->count(),
                'pending' => Order::where('representative_id', $representativeId)
                    ->whereIn('status', ['new', 'prepared'])->count(),
                'completed' => Order::where('representative_id', $representativeId)
                    ->where('status', 'completed')->count(),
            ],
        ]);
    }

    /**
     * Display the specified order for the representative.
     */
    public function show(Order $order): JsonResponse
    {
        // Ensure the order belongs to this representative
        if ($order->representative_id !== auth()->id()) {
            return response()->json(['message' => 'غير مصرح لك بالوصول لهذا الطلب'], 403);
        }

        $order->load(['orderItems.product', 'governorate', 'district', 'gift', 'giftBox']);

        return response()->json([
            'data' => new OrderResource($order),
        ]);
    }
}
