<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Feedback; 

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:complaint,suggestion,inquiry',
                'scope' => 'required|in:system,hospital,department',
                'message' => 'required|string|max:2000',
                'hospital_id' => 'nullable|exists:hospitals,id',
                'department_id' => 'nullable|exists:departments,id',
            ]);

         $validated['user_id'] = auth()->id() ?? 1; 
         //it should be auth()->id() in production, but for testing we put 1 to avoid errors if auth fails
$validated['status'] = 'pending';

            $feedback = DB::table('feedback')->insert($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'it has been sent successfully, it will be reviewed as soon as possible.',
            ], 201);

        } catch (\Exception $e) {
            Log::error('Submit Feedback Failed:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while submitting the request, please try again later.',
            ], 500);
        }
    }
}