<?php

namespace App\Http\Controllers\Subscriber\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $regEnabled = Setting::where('key', 'registration_enabled')->first()->value ?? 'true';
        if ($regEnabled !== 'true') {
            return view('subscriber.auth.registration-disabled');
        }

        return view('subscriber.auth.register');
    }

    public function register(Request $request)
    {
        $regEnabled = Setting::where('key', 'registration_enabled')->first()->value ?? 'true';
        if ($regEnabled !== 'true') {
            abort(403, 'Registration is currently disabled.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:subscribers,phone',
            'email' => 'nullable|email|unique:subscribers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $subscriber = Subscriber::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'is_active' => true,
        ]);

        Auth::guard('subscriber')->login($subscriber);

        return redirect()->route('subscriber.onboarding.store-setup');
    }
}
