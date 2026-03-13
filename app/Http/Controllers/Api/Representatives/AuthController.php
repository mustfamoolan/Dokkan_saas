<?php

namespace App\Http\Controllers\Api\Representatives;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Representatives\RepresentativeResource;

class AuthController extends Controller
{
    /**
     * Handle representative login request
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login is email or phone
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Check if representative exists and is active
        $representative = Representative::where($field, $login)
            ->where('is_active', true)
            ->first();

        if (!$representative || !Hash::check($password, $representative->password)) {
            throw ValidationException::withMessages([
                'login' => ['بيانات الاعتماد المقدمة غير صحيحة.'],
            ]);
        }

        // Create token
        $token = $representative->createToken('representative-api-token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'representative' => new RepresentativeResource($representative),
            'token' => $token,
        ]);
    }

    /**
     * Get authenticated representative profile
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'representative' => new RepresentativeResource($request->user()),
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }
}
