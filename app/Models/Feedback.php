<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Department;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'scope',
        'hospital_id',
        'department_id',
        'type',
        'message',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
