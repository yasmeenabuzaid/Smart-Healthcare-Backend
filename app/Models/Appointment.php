<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\User;
use App\Models\Queue;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'department_id',
        'date',
        'time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
