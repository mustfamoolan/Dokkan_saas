<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $customers = Customer::latest()
            ->paginate($request->get('limit', 15));

        return $this->success(CustomerResource::collection($customers)->response()->getData(true));
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return $this->success(new CustomerResource($customer));
    }
}
