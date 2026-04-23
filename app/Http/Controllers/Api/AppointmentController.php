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

    public function myAppointments()
    {
        try {
            $userId = auth()->id();

            $appointments = Appointment::select('id', 'department_id', 'date', 'user_id')
            ->with([
                'department:id,hospital_id,name_ar,name_en',
                'department.hospital:id,name_ar,name_en'
            ])
            ->where('user_id', $userId)
            ->orderBy('date')
            ->get();

            $groupedAppointments = $appointments
                ->groupBy(function ($appointment) {
                    return $appointment->department->hospital->id;
                })
                ->map(function ($hospitalAppointments) {
                    $hospital = $hospitalAppointments->first()->department->hospital;

                    return [
                        'hospital_id' => $hospital->id,
                        'hospital_name_ar' => $hospital->name_ar,
                        'hospital_name_en' => $hospital->name_en,
                        'appointments' => $hospitalAppointments->map(function ($appointment) {
                            return [
                                'appointment_id' => $appointment->id,
                                'department_name_ar' => $appointment->department->name_ar,
                                'department_name_en' => $appointment->department->name_en,
                                'date' => $appointment->date,
                            ];
                        })->values()
                    ];
                })->values();

            return response()->json([
                'status' => 'success',
                'message' => 'Appointments retrieved successfully',
                'data' => $groupedAppointments
            ]);

        } catch (\Exception $e) {

            Log::error('AppointmentController::myAppointments failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve appointments',
                'data' => null
            ], 500);
        }
    }
}