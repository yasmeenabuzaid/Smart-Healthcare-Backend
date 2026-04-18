<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    // الحقول المسموح إدخالها
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'hospital_id',
    ];


    protected $hidden = [
        'password',
    ];


    protected $casts = [
        'password' => 'hashed',
    ];


    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
