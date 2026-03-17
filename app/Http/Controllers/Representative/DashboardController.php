<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $representative = Auth::guard('representative')->user();
        
        // Scope orders and customers based on representative using orders as the link
        $ordersCount = $representative->orders()->count();
        // Since customers might not be directly linked to representative yet, we take customers from their orders
        $customersCount = \App\Models\Customer::whereHas('deliveryOrders', function($q) use ($representative) {
            $q->where('representative_id', $representative->id);
        })->count();

        return view('representative.dashboard', compact('representative', 'ordersCount', 'customersCount'));
    }
}
