<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $representative = Auth::guard('representative')->user();
        
        // Find customers who have placed orders assigned to this representative
        $customers = \App\Models\Customer::whereHas('deliveryOrders', function($q) use ($representative) {
            $q->where('representative_id', $representative->id);
        })->withCount(['deliveryOrders' => function($q) use ($representative) {
            $q->where('representative_id', $representative->id);
        }])->paginate(15);
        
        return view('representative.customers.index', compact('customers'));
    }
}
