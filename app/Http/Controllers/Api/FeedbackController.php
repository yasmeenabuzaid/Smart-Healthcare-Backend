<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;

class FeedbackController extends Controller
{   
    public function store(FeedbackRequest $request)
    {
        try {
            $userId = auth()->id();
            $validated = $request->validated();

            Feedback::create([
                'scope' => $validated['scope'],
                'hospital_id' => $validated['scope'] === 'hospital'
                    ? ($validated['hospital_id'] ?? null)
                    : null,
                'department_id' => $validated['scope'] === 'department'
                    ? ($validated['department_id'] ?? null)
                    : null,
                'type' => $validated['type'],
                'message' => $validated['message'],
                'user_id' => $userId,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Feedback submitted successfully',
                'data' => null
            ], 201);
    
        } catch (\Exception $e) {
    
            Log::error('FeedbackController::store failed:', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit feedback',
                'data' => null
            ], 500);
        }
    }
}