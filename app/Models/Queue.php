<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;
    protected $table = 'queues';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'appointment_id',
        'user_id',
        'department_id',
        'queue_number',
        'expected_time',
        'date',
        'status',
        'arrived_at',
        'called_at',
        'done_at',
        'skipped_at',
        'is_arrived',
        'is_called',
        'is_done',
        'is_skipped',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
