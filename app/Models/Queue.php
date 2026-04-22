<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\User;
use App\Models\Appointment;

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
        'is_present',
        'is_called',
        'is_served',
        'called_at',
        'served_at',
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
