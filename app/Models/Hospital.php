<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $table = 'hospitals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'logo',
        'cover_image',
        'phone',
        'emergency_phone',
        'hospital_email',
        'website_link',
        'city_id',
        'address_ar',
        'address_en',
        'latitude',
        'longitude',
        'license_number',
        'hospital_type_id',
        'status',
        'admin_notes',
        'user_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function type()
    {
        return $this->belongsTo(HospitalType::class, 'hospital_type_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

