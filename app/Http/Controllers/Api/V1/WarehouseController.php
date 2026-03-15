<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\WarehouseResource;
use App\Models\Warehouse;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $warehouses = Warehouse::latest()->get();
        return $this->success(WarehouseResource::collection($warehouses));
    }
}
