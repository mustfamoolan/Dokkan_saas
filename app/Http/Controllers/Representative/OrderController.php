<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $representative = Auth::guard('representative')->user();
        
        $orders = $representative->orders()->with(['customer', 'status'])->latest()->paginate(15);
        
        return view('representative.orders.index', compact('orders'));
    }
}
