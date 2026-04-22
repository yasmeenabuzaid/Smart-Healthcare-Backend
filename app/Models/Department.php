<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hospital;
use App\Models\Feedback;
use App\Models\DepartmentSchedule;
use App\Models\Appointment;
use App\Models\Queue;

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
}
