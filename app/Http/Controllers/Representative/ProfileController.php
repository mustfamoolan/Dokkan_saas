<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $representative = Auth::guard('representative')->user();
        return view('representative.profile.index', compact('representative'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password:representative'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $representative = Auth::guard('representative')->user();
        $representative->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }
}
