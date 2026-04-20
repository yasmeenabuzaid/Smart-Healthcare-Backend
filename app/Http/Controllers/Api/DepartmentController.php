<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartmentSchedule;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DepartmentScheduleRequest;

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
 
}