<?php

namespace App\Http\Controllers\Representative\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('representative.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('representative')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('rep.dashboard'));
        }

        return back()->withErrors([
            'phone' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::guard('representative')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('rep.login');
    }
}
