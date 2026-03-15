<?php

namespace App\Http\Controllers\Subscriber\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('subscriber')->check()) {
            return redirect()->route('subscriber.onboarding.status');
        }
        return view('subscriber.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('subscriber')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('subscriber.onboarding.status'));
        }

        return back()->withErrors([
            'phone' => 'بيانات الاعتماد غير متطابقة.',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::guard('subscriber')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('subscriber.login');
    }
}
