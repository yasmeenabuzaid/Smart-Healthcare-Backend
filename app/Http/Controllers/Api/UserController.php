<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateProfileRequest;

class UserController extends Controller
{
    public function show()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                    'data' => null
                ], 401);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'User profile retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ]
            ]);

        } catch (\Exception $e) {

            Log::error('UserController::userProfile failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user profile, please try again later.',
                'data' => null
            ], 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
           /** @var \App\Models\User $user */
            $user = auth()->user();
    
            $user->update($request->only(['phone', 'email']));
    
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.',
                'data' => $user->fresh()
            ], 200);
    
        } catch (\Exception $e) {
    
            Log::error('UserController::updateProfile failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile, please try again later.',
                'data' => null
            ], 500);
        }
    }
}
