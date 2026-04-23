<?php

namespace App\Services;

use App\Models\Queue;
use Carbon\Carbon;
use App\Models\Department;
use App\Events\QueueUpdated;
use Illuminate\Support\Facades\DB;

class QueueService
{
    function moveToNextQueue($departmentId)
    {
        DB::transaction(function () use ($departmentId) {
            $department = Department::where('id', $departmentId)
                ->lockForUpdate()
                ->first();

            if (!$department) {
                return;
            }   

            $today = Carbon::today()->toDateString();

            $current = $department->current_queue_number;

            if (is_null($current)) {
                $nextQueue = Queue::where('department_id', $departmentId)
                    ->whereDate('date', $today)
                    ->whereIn('status', ['waiting', 'arrived'])
                    ->orderBy('queue_number')
                    ->first();
            } else {
                $nextQueue = Queue::where('department_id', $departmentId)
                    ->whereDate('date', $today)
                    ->where('queue_number', '>', $current)
                    ->whereIn('status', ['waiting', 'arrived'])
                    ->orderBy('queue_number')
                    ->lockForUpdate()
                    ->first();
            }

            if (!$nextQueue) {
                $department->update([
                    'current_queue_number' => null,
                    'current_queue_id' => null,
                ]);

                event(new QueueUpdated($department));
                return;
            }

            $department->update([
                'current_queue_number' => $nextQueue?->queue_number,
                'current_queue_id' => $nextQueue?->id,
            ]);

            event(new QueueUpdated($department));
        });
    }
}