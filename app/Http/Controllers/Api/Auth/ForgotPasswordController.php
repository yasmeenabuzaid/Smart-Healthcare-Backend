<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPasswordMail;


class ForgotPasswordController extends Controller
{

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $token = Password::createToken($user); 

        $resetLink = config('app.frontend_url') . '/auth/reset-password?token=' . $token . '&email=' . urlencode($user->email);

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($resetLink));
        } catch (\Exception $e) {
            Log::error('Mail sending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reset password link has been sent to your email.'
        ]);
    }


    
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset( 
            $request->only('email', 'password', 'password_confirmation', 'token'), 
            function ($user, $password) { 
                $user->password = Hash::make($password); 
                $user->save(); 
            } 
        ); 
        
        if ($status === Password::INVALID_TOKEN) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'Invalid token or token has expired.' 
            ], 400); 
        } 
        
        if ($status === Password::INVALID_USER) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'User not found.' 
            ], 404); 
        } 
        
        return response()->json([ 'success' => true, 'message' => 'Password changed successfully.' ]); 
    }
}
