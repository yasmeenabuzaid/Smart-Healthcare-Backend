<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartmentSchedule;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DepartmentScheduleRequest;
use App\Http\Requests\GetDepartmentCalendarRequest;
use App\Models\Department;
use App\Models\Appointment;
use Carbon\Carbon;

class DepartmentController extends Controller
{   
    public function getDepartmentSchedule(DepartmentScheduleRequest $request)
    {     
        try {
        
            $departmentId = $request->department_id;

            $schedules = DepartmentSchedule::select([
                    'id', 'department_id', 'day_of_week', 'start_time', 
                    'end_time','service_type_ar', 'service_type_en'
                ])
                ->where('department_id', $departmentId) 
                ->orderBy('day_of_week')                  
                ->get();
        
            return response()->json([
                'status' => 'success',
                'message' => 'Department schedule retrieved successfully',
                'data' => $schedules
            ]);
        
        } catch (\Exception $e) {
        
            Log::error('getDepartmentSchedule failed', [
                'error' => $e->getMessage(),
            ]);
        
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve department schedule',
                'data' => null
            ], 500);
        }
    }

    public function calendar(GetDepartmentCalendarRequest $request, Department $department)
    {     
        try {
            $month = Carbon::createFromFormat('Y-m', $request->month);
            $start = $month->copy()->startOfMonth();
            $end   = $month->copy()->endOfMonth();

            $today = Carbon::today();
            
            if ($month->isSameMonth($today)) {
                $start = $today->copy();
            }
            
            $schedules = DepartmentSchedule::where('department_id', $department->id)
                ->get()
                ->keyBy('day_of_week');
    
            $appointmentsCount = Appointment::where('department_id', $department->id)
                ->whereBetween('date', [$start, $end])
                ->selectRaw('date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date') // ['2026-04-01' => 5]
                ->toArray(); 
    
            $result = [];
    
            for ($date = $start->copy(); $date <= $end; $date->addDay()) {
    
                $dateString = $date->toDateString();
                $dayOfWeek = strtolower($date->format('D')); // mon, tue...
    
                $schedule = $schedules->get($dayOfWeek);
    
                // default
                $isClosed = false;
    
                // 1. no schedule OR closed day
                if (!$schedule || $schedule->is_closed) {
                    $isClosed = true;
                } else {
    
                    // 2. capacity check
                    $count = $appointmentsCount[$dateString] ?? 0;
    
                    if ($count >= $schedule->max_patients) {
                        $isClosed = true;
                    }
                }
    
                $result[] = [
                    'date' => $dateString,
                    'is_closed' => $isClosed,
                ];
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Department calendar retrieved successfully',
                'data' => $result
            ]);
        
        } catch (\Exception $e) {
        
            Log::error('calendar failed', [
                'error' => $e->getMessage(),
            ]);
        
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve department calendar',
                'data' => null
            ], 500);
        }
    }
 
}