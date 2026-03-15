<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SupplierResource;
use App\Models\Supplier;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $suppliers = Supplier::latest()
            ->paginate($request->get('limit', 15));

        return $this->success(SupplierResource::collection($suppliers)->response()->getData(true));
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return $this->success(new SupplierResource($supplier));
    }
}
