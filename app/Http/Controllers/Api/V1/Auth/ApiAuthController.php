<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        $request->validate([
            'phone'       => 'required|string',
            'password'    => 'required|string',
            'device_name' => 'required|string',
        ]);

        $subscriber = Subscriber::where('phone', $request->phone)->first();

        if (!$subscriber || !Hash::check($request->password, $subscriber->password)) {
            return $this->error('بيانات الاعتماد غير صحيحة.', 401);
        }

        if (!$subscriber->is_active) {
            return $this->error('الحساب معطل.', 403);
        }

        $token = $subscriber->createToken($request->device_name)->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => [
                'id'    => $subscriber->id,
                'name'  => $subscriber->name,
                'phone' => $subscriber->phone,
                'store' => $subscriber->store ? [
                    'id'   => $subscriber->store->id,
                    'name' => $subscriber->store->name,
                ] : null,
            ],
        ], 'تم تسجيل الدخول بنجاح.');
    }

    public function me(Request $request)
    {
        $subscriber = $request->user();
        
        return $this->success([
            'id'    => $subscriber->id,
            'name'  => $subscriber->name,
            'phone' => $subscriber->phone,
            'store' => $subscriber->store,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'تم تسجيل الخروج بنجاح.');
    }
}
