<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone', 
            'national_number' => 'required|string|unique:users,national_number',
            'password' => 'required|string|min:6', 
        ], [
            'phone.unique' => 'Phone number is already in use.', 
            'national_number.unique' => 'National number is already registered.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone, 
            'role_id' => 4,
            'national_number' => $request->national_number,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('customer-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'user' => $user->only('id', 'name', 'national_number', 'phone'), 
            'accessToken' => $token,
        ], 201);
    }
}