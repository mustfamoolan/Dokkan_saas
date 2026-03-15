<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PurchaseInvoiceResource;
use App\Models\PurchaseInvoice;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $purchases = PurchaseInvoice::with('supplier', 'warehouse')
            ->latest()
            ->paginate($request->get('limit', 15));

        return $this->success(PurchaseInvoiceResource::collection($purchases)->response()->getData(true));
    }

    public function show($id)
    {
        $purchase = PurchaseInvoice::with('supplier', 'warehouse', 'items.product')->findOrFail($id);
        return $this->success(new PurchaseInvoiceResource($purchase));
    }
}
