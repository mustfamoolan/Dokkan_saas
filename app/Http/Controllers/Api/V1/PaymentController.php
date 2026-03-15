<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PaymentResource;
use App\Models\CustomerPayment;
use App\Models\SupplierPayment;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponseTrait;

    public function customerPayments(Request $request)
    {
        $payments = CustomerPayment::with('customer', 'cashbox')
            ->latest()
            ->paginate($request->get('limit', 15));

        return $this->success(PaymentResource::collection($payments)->response()->getData(true));
    }

    public function supplierPayments(Request $request)
    {
        $payments = SupplierPayment::with('supplier', 'cashbox')
            ->latest()
            ->paginate($request->get('limit', 15));

        return $this->success(PaymentResource::collection($payments)->response()->getData(true));
    }
}
