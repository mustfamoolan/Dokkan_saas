<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SalesInvoiceResource;
use App\Models\SalesInvoice;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $sales = SalesInvoice::with('customer', 'warehouse')
            ->latest()
            ->paginate($request->get('limit', 15));

        return $this->success(SalesInvoiceResource::collection($sales)->response()->getData(true));
    }

    public function show($id)
    {
        $sale = SalesInvoice::with('customer', 'warehouse', 'items.product')->findOrFail($id);
        return $this->success(new SalesInvoiceResource($sale));
    }
}
