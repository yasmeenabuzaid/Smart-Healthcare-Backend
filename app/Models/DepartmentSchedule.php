<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class DepartmentSchedule extends Model
{
    use HasFactory;
    protected $table = 'department_schedules';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'department_id',
        'day_of_week',
        'start_time',
        'end_time',
        'service_type_ar',
        'service_type_en',
        'avg_visit_duration',
        'max_patients',
        'is_active',
        'is_closed',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
