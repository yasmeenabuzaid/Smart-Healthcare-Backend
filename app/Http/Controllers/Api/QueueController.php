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

            $queue = DB::transaction(function () use ($department, $departmentId, $today, $userId, $schedule) {

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

                return Queue::create([
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
                'data' => [
                    'queue_number' => $queue->queue_number,
                    'expected_time' => Carbon::parse($queue->expected_time)->format('h:i A'),
                ]            
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
                    'message' => 'Failed to book queue, please try again later.'
                ], 500),
            };
        }
    }

    public function arrive(int $queueId)
    {
        try {
            $userId = auth()->id();
    
            $queue = Queue::where('id', $queueId)
                ->where('user_id', $userId)
                ->first();
    
            if (!$queue) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Queue not found'
                ], 404);
            }
    
            if ($queue->is_arrived) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Already marked as arrived'
                ], 422);
            }

            if ($queue->date !== Carbon::today()->toDateString()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Arrival can only be confirmed on queue date'
                ], 422);
            }

            $queue->update([
                'is_arrived' => true,
                'arrived_at' => now(),
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Arrival confirmed',
                'data' => null
            ]);
    
        } catch (\Exception $e) {
    
            Log::error('QueueController::arrive failed', [
                'error' => $e->getMessage()
            ]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to confirm arrival, please try again later.'
            ], 500);
        }
    }

    public function checkIn(int $queueId)
    {
        try {
            $userId = auth()->id();
    
            $queue = Queue::where('id', $queueId)
                ->where('user_id', $userId)
                ->first();
    
            if (!$queue) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Queue not found'
                ], 404);
            }
    
            if ($queue->is_checked_in) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You already checked in'
                ], 422);
            }

            if ($queue->date !== Carbon::today()->toDateString()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Check-in can only be confirmed on queue date'
                ], 422);
            }

            $queue->update([
                'is_checked_in' => true,
                'checked_in_at' => now(),
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Checked in successfully',
                'data' => null
            ]);
    
        } catch (\Exception $e) {
    
            Log::error('QueueController::checkIn failed', [
                'error' => $e->getMessage()
            ]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to check in, please try again later.'
            ], 500);
        }
    }

    public function myQueuesToday()
    {
        try {
            $userId = auth()->id();
            $today = Carbon::today()->toDateString();

            $queues = Queue::select('id', 'department_id', 'date', 'user_id', 'expected_time', 'queue_number')
            ->with([
                'department:id,hospital_id,name_ar,name_en',
                'department.hospital:id,name_ar,name_en'
            ])
            ->where('user_id', $userId)
            ->where('date', $today)
            ->orderBy('expected_time')
            ->get();

            $grouped = $queues
                ->groupBy(function ($queue) {
                    return $queue->department->hospital->id;
                })
                ->map(function ($hospitalQueues) {
                    $hospital = $hospitalQueues->first()->department->hospital;

                    return [
                        'hospital_id' => $hospital->id,
                        'hospital_name_ar' => $hospital->name_ar,
                        'hospital_name_en' => $hospital->name_en,
                        'queues' => $hospitalQueues->map(function ($queue) {
                            return [
                                'queue_id' => $queue->id,
                                'department_name_ar' => $queue->department->name_ar,
                                'department_name_en' => $queue->department->name_en,
                                'queue_number' => $queue->queue_number,
                                'expected_time' => Carbon::parse($queue->expected_time)->format('h:i A'),
                            ];
                        })->values()
                    ];
                })->values();

            return response()->json([
                'status' => 'success',
                'message' => 'Today queues retrieved successfully',
                'data' => $grouped
            ]);

        } catch (\Exception $e) {

            Log::error('QueueController::todayQueues failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve queues, please try again later.',
                'data' => null
            ], 500);
        }
    }
}