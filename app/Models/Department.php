<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hospital_id',
        'name_ar',
        'name_en',
        'requires_appointment',
        'current_queue_number',
        'current_queue_id',
        'is_active',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function schedules()
    {
        return $this->hasMany(DepartmentSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function currentQueue()
    {
        return $this->belongsTo(Queue::class, 'current_queue_id');
    }

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'employee_departments',
            'department_id',
            'employee_id'
        );
    }
}
