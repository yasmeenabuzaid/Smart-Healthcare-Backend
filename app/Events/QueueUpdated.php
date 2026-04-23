<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

class QueueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $department;

    public function __construct(Department $department)
    {
        $this->department = $department;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return  new Channel('department.'.$this->department->id);
    }

    public function broadcastAs()
    {
        return 'queue.updated';
    }

    public function broadcastWith()
    {
        Log::info('BROADCAST FIRED', [
            'department_id' => $this->department->id,
            'current_queue_number' => $this->department->current_queue_number
        ]);
        
        return [
            'department_id' => $this->department->id,
            'current_queue_number' => $this->department->current_queue_number,
            'current_queue_id' => $this->department->current_queue_id,
        ];
    }
}
