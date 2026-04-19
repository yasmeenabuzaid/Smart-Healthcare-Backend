<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiLoginController extends Controller
{
    public function __construct()
    {
    }

    public function requestLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'national_number' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::where('national_number', $request->national_number)->first();

        if (!$user) {
            return response()->json(['error' => 'this national number is not registered'], 404);
        }

        $phone = $user->phone_number;
        $maskedPhone = substr($phone, 0, 3) . '***' . substr($phone, -4);

        return response()->json([
            'success' => true,
            'masked_phone' => $maskedPhone
        ], 200);
    }

    public function verifyLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'national_number' => ['required', 'string'],
            'password' => ['required', 'string'], 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::where('national_number', $request->national_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('customer-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'accessToken' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }
}