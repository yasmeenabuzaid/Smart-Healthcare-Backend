<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartmentSchedule;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Department;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{   
    public function store(StoreAppointmentRequest $request, int $departmentId)
    {
        try {
            $userId = auth()->id();
            $exists  = Department::where('id', $departmentId)->exists();

            if (!$exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department not found'
                ], 404);
            }

            $date = Carbon::parse($request->date);
            $today = Carbon::today()->startOfDay();
          
            if ($date->lessThanOrEqualTo($today)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot book past dates'
                ], 422);
            }
    
            // day check
            DB::transaction(function () use ($departmentId, $date, $userId) {
                
                $dayOfWeek = strtolower($date->format('D'));
                
                $schedule = DepartmentSchedule::where('department_id', $departmentId)
                    ->where('day_of_week', $dayOfWeek)
                    ->first();
        
                if (!$schedule || $schedule->is_closed) {
                    throw new \Exception('DAY_NOT_AVAILABLE');
                }
        
                // capacity check 
                $count = Appointment::where('department_id', $departmentId)
                    ->whereDate('date', $date)
                    ->lockForUpdate()
                    ->count();
        
                if ($count >= $schedule->max_patients) {
                    throw new \Exception('FULLY_BOOKED');
                }
    
                $alreadyBooked = Appointment::where('user_id', $userId)
                    ->where('department_id', $departmentId)
                    ->whereDate('date', $date)
                    ->lockForUpdate()
                    ->exists();
                
                if ($alreadyBooked ) {
                    throw new \Exception('ALREADY_BOOKED');
                }
        
                // create appointment 
                Appointment::create([
                    'user_id' => $userId,
                    'department_id' => $departmentId,
                    'date' => $date->toDateString(),
                ]);
            });
            return response()->json([
                'status' => 'success',
                'message' => 'Appointment booked successfully',
                'data' => null
            ]);
    
        } catch (\Exception $e) {
    
            Log::error('AppointmentController::store failed', [
                'error' => $e->getMessage()
            ]);
    
            return match ($e->getMessage()) {
    
                'DAY_NOT_AVAILABLE' => response()->json([
                    'status' => 'error',
                    'message' => 'This day is not available'
                ], 422),
    
                'FULLY_BOOKED' => response()->json([
                    'status' => 'error',
                    'message' => 'This day is fully booked'
                ], 422),
    
                'ALREADY_BOOKED' => response()->json([
                    'status' => 'error',
                    'message' => 'You already booked this day'
                ], 422),
    
                default => response()->json([
                    'status' => 'error',
                    'message' => 'Failed to booked appointment'
                ], 500),
            };
        }
    }
}