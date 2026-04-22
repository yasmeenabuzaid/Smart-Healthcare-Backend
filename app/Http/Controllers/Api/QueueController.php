<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepartmentSchedule;
use Illuminate\Support\Facades\Log;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{   
    public function store(int $departmentId)
    {
        try {
            $userId = auth()->id();

            $department = Department::select('id', 'requires_appointment')
                ->where('id', $departmentId)
                ->first();

            if (!$department) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department not found'
                ], 404);
            }

            $today = Carbon::today();
            $now = Carbon::now();
            $dayOfWeek = strtolower($today->format('D'));

            $schedule = DepartmentSchedule::where('department_id', $departmentId)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            if (!$schedule || $schedule->is_closed) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department is closed today'
                ], 422);
            }

            $queueOpenTime = Carbon::parse($today->toDateString() . ' ' . $schedule->start_time)
                ->subMinutes(30);

            if ($now->lt($queueOpenTime)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Queue opens 30 minutes before start time'
                ], 422);
            }

            $queueCloseTime = Carbon::parse($today->toDateString() . ' ' . $schedule->end_time);
            
            if ($now->gt($queueCloseTime)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department is closed now'
                ], 422);
            }

            DB::transaction(function () use ($department, $departmentId, $today, $userId, $schedule) {

                $appointmentId = null;

                if ($department->requires_appointment) {
                    $appointment = Appointment::where('user_id', $userId)
                        ->where('department_id', $departmentId)
                        ->whereDate('date', $today)
                        ->first();

                    if (!$appointment) {
                        throw new \Exception('NO_APPOINTMENT');
                    }

                    $appointmentId = $appointment->id;
                }

                $alreadyQueued = Queue::where('user_id', $userId)
                    ->where('department_id', $departmentId)
                    ->whereDate('date', $today)
                    ->lockForUpdate()
                    ->exists();

                if ($alreadyQueued) {
                    throw new \Exception('ALREADY_QUEUED');
                }

                $lastQueue = Queue::where('department_id', $departmentId)
                    ->whereDate('date', $today)
                    ->orderByDesc('queue_number')
                    ->lockForUpdate()
                    ->first();

                $nextQueueNumber = $lastQueue ? $lastQueue->queue_number + 1 : 1;

                if ($nextQueueNumber > $schedule->max_patients) {
                    throw new \Exception('QUEUE_FULL');
                }

                $expectedTime = Carbon::parse($today->toDateString() . ' ' . $schedule->start_time)
                    ->addMinutes(($nextQueueNumber - 1) * $schedule->avg_visit_duration);

                Queue::create([
                    'appointment_id' => $appointmentId,
                    'user_id' => $userId,
                    'department_id' => $departmentId,
                    'queue_number' => $nextQueueNumber,
                    'expected_time' => $expectedTime,
                    'date' => $today->toDateString(),
                ]);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Queue booked successfully',
                'data' => null
            ]);

        } catch (\Exception $e) {

            Log::error('QueueController::store failed', [
                'error' => $e->getMessage()
            ]);

            return match ($e->getMessage()) {

                'NO_APPOINTMENT' => response()->json([
                    'status' => 'error',
                    'message' => 'You do not have an appointment today'
                ], 422),

                'ALREADY_QUEUED' => response()->json([
                    'status' => 'error',
                    'message' => 'You already took a queue today'
                ], 422),

                'QUEUE_FULL' => response()->json([
                    'status' => 'error',
                    'message' => 'Queue is full'
                ], 422),

                default => response()->json([
                    'status' => 'error',
                    'message' => 'Failed to book queue'
                ], 500),
            };
        }
    }
}